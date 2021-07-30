<?php
//cattr
//woocommerce/includes/admin/class-wc-admin-taxonomies.php
function custom_has_custom_attribute_types()
{
    $types = custom_get_attribute_types();

    return 1 < count($types) || !array_key_exists('select', $types);
}
function custom_get_attribute_types()
{
    return (array) apply_filters(
        'custom_attributes_type_selector',
        array(
            'select' => __('Select', 'woocommerce'),
        )
    );
}
function custom_get_attribute_taxonomies()
{
    $attribute_taxonomies = get_transient('custom_attribute_taxonomies');

    if (false === $attribute_taxonomies) {
        global $wpdb;

        $attribute_taxonomies = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}custom_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;");

        set_transient('custom_attribute_taxonomies', $attribute_taxonomies);
    }

    return (array) array_filter(apply_filters('custom_attribute_taxonomies', $attribute_taxonomies));
}


function custom_attribute_install()
{
    global $wpdb;
    $collate = '';
    $tables = "CREATE TABLE {$wpdb->prefix}custom_attribute_taxonomies (
    attribute_id BIGINT UNSIGNED NOT NULL auto_increment,
    attribute_name varchar(200) NOT NULL,
    attribute_label varchar(200) NULL,
    attribute_type varchar(20) NOT NULL,
    attribute_orderby varchar(20) NOT NULL,
    attribute_public int(1) NOT NULL DEFAULT 1,
    PRIMARY KEY  (attribute_id),
    KEY attribute_name (attribute_name(20))
    ) $collate;";
    echo $tables;
}
// custom_attribute_install();
// die();

function custom_get_attribute_type_label($type)
{
    $types = custom_get_attribute_types();

    return isset($types[$type]) ? $types[$type] : __('Select', 'woocommerce');
}
function custom_attribute_taxonomy_name($attribute_name)
{
    return $attribute_name ? 'cpa_' . custom_sanitize_taxonomy_name($attribute_name) : '';
}
function custom_sanitize_taxonomy_name($taxonomy)
{
    return apply_filters('custom_sanitize_taxonomy_name', urldecode(sanitize_title(urldecode($taxonomy))), $taxonomy);
}
function custom_attribute_orderby($name)
{
    global $wc_product_attributes, $wpdb;

    $name = str_replace('cpa_', '', sanitize_title($name));

    if (isset($wc_product_attributes['cpa_' . $name])) {
        $orderby = $wc_product_attributes['cpa_' . $name]->attribute_orderby;
    } else {
        $orderby = $wpdb->get_var($wpdb->prepare("SELECT attribute_orderby FROM {$wpdb->prefix}custom_attribute_taxonomies WHERE attribute_name = %s;", $name));
    }

    return apply_filters('custom_attribute_orderby', $orderby, $name);
}
function cattr_clean($var)
{
    if (is_array($var)) {
        return array_map('cattr_clean', $var);
    } else {
        return is_scalar($var) ? sanitize_text_field($var) : $var;
    }
}
function custom_create_attribute($args)
{
    global $wpdb;

    $args   = wp_unslash($args);
    $id     = !empty($args['id']) ? intval($args['id']) : 0;
    $format = array('%s', '%s', '%s', '%s', '%d');

    // Name is required.
    if (empty($args['name'])) {
        return new WP_Error('missing_attribute_name', __('Please, provide an attribute name.', 'woocommerce'), array('status' => 400));
    }

    // Set the attribute slug.
    if (empty($args['slug'])) {
        $slug = custom_sanitize_taxonomy_name($args['name']);
    } else {
        $slug = preg_replace('/^pa\_/', '', custom_sanitize_taxonomy_name($args['slug']));
    }

    // Validate slug.
    if (strlen($slug) >= 28) {
        /* translators: %s: attribute slug */
        return new WP_Error('invalid_product_attribute_slug_too_long', sprintf(__('Slug "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce'), $slug), array('status' => 400));
    } elseif (custom_check_if_attribute_name_is_reserved($slug)) {
        /* translators: %s: attribute slug */
        return new WP_Error('invalid_product_attribute_slug_reserved_name', sprintf(__('Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce'), $slug), array('status' => 400));
    } elseif ((0 === $id && taxonomy_exists(custom_attribute_taxonomy_name($slug))) || (isset($args['old_slug']) && $args['old_slug'] !== $slug && taxonomy_exists(custom_attribute_taxonomy_name($slug)))) {
        /* translators: %s: attribute slug */
        return new WP_Error('invalid_product_attribute_slug_already_exists', sprintf(__('Slug "%s" is already in use. Change it, please.', 'woocommerce'), $slug), array('status' => 400));
    }

    // Validate type.
    if (empty($args['type']) || !array_key_exists($args['type'], custom_get_attribute_types())) {
        $args['type'] = 'select';
    }

    // Validate order by.
    if (empty($args['order_by']) || !in_array($args['order_by'], array('menu_order', 'name', 'name_num', 'id'), true)) {
        $args['order_by'] = 'menu_order';
    }

    $data = array(
        'attribute_label'   => $args['name'],
        'attribute_name'    => $slug,
        'attribute_type'    => $args['type'],
        'attribute_orderby' => $args['order_by'],
        'attribute_public'  => isset($args['has_archives']) ? (int) $args['has_archives'] : 0,
    );

    // Create or update.
    if (0 === $id) {
        $results = $wpdb->insert(
            $wpdb->prefix . 'custom_attribute_taxonomies',
            $data,
            $format
        );

        if (is_wp_error($results)) {
            return new WP_Error('cannot_create_attribute', $results->get_error_message(), array('status' => 400));
        }

        $id = $wpdb->insert_id;

        /**
         * Attribute added.
         *
         * @param int   $id   Added attribute ID.
         * @param array $data Attribute data.
         */
        do_action('customaction_attribute_added', $id, $data);
    } else {
        $results = $wpdb->update(
            $wpdb->prefix . 'custom_attribute_taxonomies',
            $data,
            array('attribute_id' => $id),
            $format,
            array('%d')
        );

        if (false === $results) {
            return new WP_Error('cannot_update_attribute', __('Could not update the attribute.', 'woocommerce'), array('status' => 400));
        }

        // Set old slug to check for database changes.
        $old_slug = !empty($args['old_slug']) ? custom_sanitize_taxonomy_name($args['old_slug']) : $slug;

        /**
         * Attribute updated.
         *
         * @param int    $id       Added attribute ID.
         * @param array  $data     Attribute data.
         * @param string $old_slug Attribute old name.
         */
        do_action('customaction_attribute_updated', $id, $data, $old_slug);

        if ($old_slug !== $slug) {
            // Update taxonomies in the wp term taxonomy table.
            $wpdb->update(
                $wpdb->term_taxonomy,
                array('taxonomy' => custom_attribute_taxonomy_name($data['attribute_name'])),
                array('taxonomy' => 'cpa_' . $old_slug)
            );

            // Update taxonomy ordering term meta.
            $table_name = get_option('db_version') < 34370 ? $wpdb->prefix . 'customaction_termmeta' : $wpdb->termmeta;
            $wpdb->update(
                $table_name,
                array('meta_key' => 'order_pa_' . sanitize_title($data['attribute_name'])), // WPCS: slow query ok.
                array('meta_key' => 'order_pa_' . sanitize_title($old_slug)) // WPCS: slow query ok.
            );

            // Update product attributes which use this taxonomy.
            $old_taxonomy_name = 'cpa_' . $old_slug;
            $new_taxonomy_name = 'cpa_' . $data['attribute_name'];
            $metadatas         = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_product_attributes' AND meta_value LIKE %s",
                    '%' . $wpdb->esc_like($old_taxonomy_name) . '%'
                ),
                ARRAY_A
            );
            foreach ($metadatas as $metadata) {
                $product_id        = $metadata['post_id'];
                $unserialized_data = maybe_unserialize($metadata['meta_value']);
                if (!$unserialized_data || !is_array($unserialized_data) || !isset($unserialized_data[$old_taxonomy_name])) {
                    continue;
                }

                $unserialized_data[$new_taxonomy_name] = $unserialized_data[$old_taxonomy_name];
                unset($unserialized_data[$old_taxonomy_name]);
                $unserialized_data[$new_taxonomy_name]['name'] = $new_taxonomy_name;
                update_post_meta($product_id, '_product_attributes', $unserialized_data);
            }

            // Update variations which use this taxonomy.
            $wpdb->update(
                $wpdb->postmeta,
                array('meta_key' => 'attribute_pa_' . sanitize_title($data['attribute_name'])), // WPCS: slow query ok.
                array('meta_key' => 'attribute_pa_' . sanitize_title($old_slug)) // WPCS: slow query ok.
            );
        }
    }

    // Clear cache and flush rewrite rules.
    wp_schedule_single_event(time(), 'customaction_flush_rewrite_rules');
    delete_transient('custom_attribute_taxonomies');

    return $id;
}

function custom_delete_attribute($id)
{
    global $wpdb;

    $name = $wpdb->get_var(
        $wpdb->prepare(
            "
		SELECT attribute_name
		FROM {$wpdb->prefix}custom_attribute_taxonomies
		WHERE attribute_id = %d
	",
            $id
        )
    );

    $taxonomy = wc_attribute_taxonomy_name($name);

    /**
     * Before deleting an attribute.
     *
     * @param int    $id       Attribute ID.
     * @param string $name     Attribute name.
     * @param string $taxonomy Attribute taxonomy name.
     */
    do_action('customaction_before_attribute_delete', $id, $name, $taxonomy);

    if ($name && $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}custom_attribute_taxonomies WHERE attribute_id = %d", $id))) {
        if (taxonomy_exists($taxonomy)) {
            $terms = get_terms($taxonomy, 'orderby=name&hide_empty=0');
            foreach ($terms as $term) {
                wp_delete_term($term->term_id, $taxonomy);
            }
        }

        /**
         * After deleting an attribute.
         *
         * @param int    $id       Attribute ID.
         * @param string $name     Attribute name.
         * @param string $taxonomy Attribute taxonomy name.
         */
        do_action('customaction_attribute_deleted', $id, $name, $taxonomy);
        wp_schedule_single_event(time(), 'customaction_flush_rewrite_rules');
        delete_transient('wc_attribute_taxonomies');

        return true;
    }

    return false;
}



function custom_check_if_attribute_name_is_reserved($attribute_name)
{
    // Forbidden attribute names.
    $reserved_terms = array(
        'attachment',
        'attachment_id',
        'author',
        'author_name',
        'calendar',
        'cat',
        'category',
        'category__and',
        'category__in',
        'category__not_in',
        'category_name',
        'comments_per_page',
        'comments_popup',
        'cpage',
        'day',
        'debug',
        'error',
        'exact',
        'feed',
        'hour',
        'link_category',
        'm',
        'minute',
        'monthnum',
        'more',
        'name',
        'nav_menu',
        'nopaging',
        'offset',
        'order',
        'orderby',
        'p',
        'page',
        'page_id',
        'paged',
        'pagename',
        'pb',
        'perm',
        'post',
        'post__in',
        'post__not_in',
        'post_format',
        'post_mime_type',
        'post_status',
        'post_tag',
        'post_type',
        'posts',
        'posts_per_archive_page',
        'posts_per_page',
        'preview',
        'robots',
        's',
        'search',
        'second',
        'sentence',
        'showposts',
        'static',
        'subpost',
        'subpost_id',
        'tag',
        'tag__and',
        'tag__in',
        'tag__not_in',
        'tag_id',
        'tag_slug__and',
        'tag_slug__in',
        'taxonomy',
        'tb',
        'term',
        'type',
        'w',
        'withcomments',
        'withoutcomments',
        'year',
    );

    return in_array($attribute_name, $reserved_terms, true);
}
function custom_update_attribute($id, $args)
{
    global $wpdb;

    $attribute = custom_get_attribute($id);

    $args['id'] = $attribute ? $attribute->id : 0;

    if ($args['id'] && empty($args['name'])) {
        $args['name'] = $attribute->name;
    }

    $args['old_slug'] = $wpdb->get_var(
        $wpdb->prepare(
            "
				SELECT attribute_name
				FROM {$wpdb->prefix}custom_attribute_taxonomies
				WHERE attribute_id = %d
			",
            $args['id']
        )
    );

    return custom_create_attribute($args);
}

function custom_get_attribute($id)
{
    global $wpdb;

    $data = $wpdb->get_row(
        $wpdb->prepare(
            "
		SELECT *
		FROM {$wpdb->prefix}custom_attribute_taxonomies
		WHERE attribute_id = %d
	 ",
            $id
        )
    );

    if (is_wp_error($data) || is_null($data)) {
        return null;
    }

    $attribute               = new stdClass();
    $attribute->id           = (int) $data->attribute_id;
    $attribute->name         = $data->attribute_label;
    $attribute->slug         = custom_attribute_taxonomy_name($data->attribute_name);
    $attribute->type         = $data->attribute_type;
    $attribute->order_by     = $data->attribute_orderby;
    $attribute->has_archives = (bool) $data->attribute_public;

    return $attribute;
}
