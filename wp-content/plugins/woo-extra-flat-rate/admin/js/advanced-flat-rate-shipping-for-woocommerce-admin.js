(function ($) {
    'use strict';
    jQuery(".multiselect2").select2();

    function allowSpeicalCharacter(str) {
        return str.replace('&#8211;', '–').replace("&gt;", ">").replace("&lt;", "<").replace("&#197;", "Å");
    }

    function productFilter() {
        jQuery('.product_fees_conditions_values_product').each(function () {
            $('.product_fees_conditions_values_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'afrsm_pro_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            $.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });
    }

    function varproductFilter() {
        $('.product_fees_conditions_values_var_product').each(function () {
            $('.product_fees_conditions_values_var_product').select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'afrsm_pro_product_fees_conditions_varible_values_product_ajax__premium_only'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            $.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });
    }
    function setAllAttributes(element, attributes) {
        Object.keys(attributes).forEach(function (key) {
            element.setAttribute(key, attributes[key]);
            // use val
        });
        return element;
    }
    function numberValidateForAdvanceRules() {
        $('.number-field').keypress(function (e) {
            var regex = new RegExp("^[0-9-%.]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $('.qty-class').keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $('.weight-class, .price-class').keypress(function (e) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
    }

    $(window).load(function () {
        jQuery(".multiselect2").select2();

        $('a[href="admin.php?page=afrsm-pro-list"]').parent().addClass('current');
        $('a[href="admin.php?page=afrsm-pro-list"]').addClass('current');

        if (jQuery('#shipping-methods-listing tbody tr').length <= 0) {
            jQuery('#delete-shipping-method').hide();
            jQuery('.shipping-methods-order').hide();
        }

        /*Start: Get last url parameters*/
        function getUrlVars() {
            var vars = [], hash, get_current_url;
            get_current_url = coditional_vars.current_url;
            var hashes = get_current_url.slice(get_current_url.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        var ele = $('#total_row').val();
        var count;
        if (ele > 2) {
            count = ele;
        } else {
            count = 2;
        }
        $('body').on('click', '#fee-add-field', function () {
            var fee_add_field = $('#tbl-shipping-method tbody').get(0);

            var tr = document.createElement("tr");
            tr = setAllAttributes(tr, {"id": "row_" + count});
            fee_add_field.appendChild(tr);

            // generate td of condition
            var td = document.createElement("td");
            td = setAllAttributes(td, {});
            tr.appendChild(td);
            var conditions = document.createElement("select");
            conditions = setAllAttributes(conditions, {
                "rel-id": count,
                "id": "product_fees_conditions_condition_" + count,
                "name": "fees[product_fees_conditions_condition][]",
                "class": "product_fees_conditions_condition"
            });
            conditions = insertOptions(conditions, get_all_condition());
            td.appendChild(conditions);
            // td ends

            // generate td for equal or no equal to
            td = document.createElement("td");
            td = setAllAttributes(td, {});
            tr.appendChild(td);
            var conditions_is = document.createElement("select");
            conditions_is = setAllAttributes(conditions_is, {
                "name": "fees[product_fees_conditions_is][]",
                "class": "product_fees_conditions_is product_fees_conditions_is_" + count
            });
            conditions_is = insertOptions(conditions_is, condition_types());
            td.appendChild(conditions_is);
            // td ends

            // td for condition values
            td = document.createElement("td");
            td = setAllAttributes(td, {"id": "column_" + count});
            tr.appendChild(td);
            condition_values(jQuery('#product_fees_conditions_condition_' + count));

            var condition_key = document.createElement("input");
            condition_key = setAllAttributes(condition_key, {
                "type": "hidden",
                "name": "condition_key[value_" + count + "][]",
                "value": "",
            });
            td.appendChild(condition_key);
            var conditions_values_index = jQuery(".product_fees_conditions_values_" + count).get(0);
            jQuery(".product_fees_conditions_values_" + count).trigger('change');
            jQuery(".multiselect2").select2();
            // td ends

            // td for delete button
            td = document.createElement("td");
            tr.appendChild(td);
            var delete_button = document.createElement("a");
            delete_button = setAllAttributes(delete_button, {
                "id": "fee-delete-field",
                "rel-id": count,
                "title": coditional_vars.delete,
                "class": "delete-row",
                "href": "javascript:;"
            });
            var deleteicon = document.createElement('i');
            deleteicon = setAllAttributes(deleteicon, {
                "class": "fa fa-trash"
            });
            delete_button.appendChild(deleteicon);
            td.appendChild(delete_button);
            // td ends

            numberValidateForAdvanceRules();
            count++;
        });

        $('body').on('change', '.product_fees_conditions_condition', function () {
            condition_values(this);
        });

        /* description toggle */
        $('span.advanced_flat_rate_shipping_for_woocommerce_tab_description').click(function (event) {
            event.preventDefault();
            $(this).next('p.description').toggle();
        });

        /*Extra Validation*/
        numberValidateForAdvanceRules();

        //remove tr on delete icon click
        $('body').on('click', '.delete-row', function () {
            $(this).parent().parent().remove();
        });

        function insertOptions(parentElement, options) {
            for (var i = 0; i < options.length; i++) {
                if (options[i].type == 'optgroup') {
                    var optgroup = document.createElement("optgroup");
                    optgroup = setAllAttributes(optgroup, options[i].attributes);
                    for (var j = 0; j < options[i].options.length; j++) {
                        var option = document.createElement("option");
                        option = setAllAttributes(option, options[i].options[j].attributes);
                        option.textContent = options[i].options[j].name;
                        optgroup.appendChild(option);
                    }
                    parentElement.appendChild(optgroup);
                } else {
                    var option = document.createElement("option");
                    option = setAllAttributes(option, options[i].attributes);
                    option.textContent = allowSpeicalCharacter(options[i].name);
                    parentElement.appendChild(option);
                }

            }
            return parentElement;

        }

        function allowSpeicalCharacter(str) {
            return str.replace('&#8211;', '–').replace("&gt;", ">").replace("&lt;", "<").replace("&#197;", "Å");
        }

        function get_all_condition() {
            return [
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.location_specific},
                    "options": [
                        {"name": coditional_vars.country, "attributes": {"value": "country"}},
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.product_specific},
                    "options": [
                        {"name": coditional_vars.cart_contains_product, "attributes": {"value": "product"}},
                        {"name": coditional_vars.cart_contains_category_product, "attributes": {"value": "category"}},
                        {"name": coditional_vars.cart_contains_tag_product, "attributes": {"value": "tag"}},
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.user_specific},
                    "options": [
                        {"name": coditional_vars.user, "attributes": {"value": "user"}},
                    ]
                },
                {
                    "type": "optgroup",
                    "attributes": {"label": coditional_vars.cart_specific},
                    "options": [
                        {"name": coditional_vars.cart_subtotal_before_discount, "attributes": {"value": "cart_total"}},
                        {"name": coditional_vars.quantity, "attributes": {"value": "quantity"}},
                    ]
                },
            ];
        }

        function condition_values(element) {
            var condition = $(element).val();
            var count = $(element).attr('rel-id');
            var column = jQuery('#column_' + count).get(0);
            jQuery(column).empty();
            var loader = document.createElement('img');
            loader = setAllAttributes(loader, {'src': coditional_vars.plugin_url + 'images/ajax-loader.gif'});
            column.appendChild(loader);

            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsm_pro_product_fees_conditions_values_ajax',
                    'condition': condition,
                    'count': count
                },
                contentType: "application/json",
                success: function (response) {
                    var condition_values;
                    jQuery('.product_fees_conditions_is_' + count).empty();
                    var column = jQuery('#column_' + count).get(0);
                    var condition_is = jQuery('.product_fees_conditions_is_' + count).get(0);
                    if (condition == 'cart_total'
                            || condition == 'quantity'
                            ) {
                        condition_is = insertOptions(condition_is, condition_types(true));
                    } else {
                        condition_is = insertOptions(condition_is, condition_types(false));
                    }
                    jQuery('.product_fees_conditions_is_' + count).trigger("chosen:updated");
                    jQuery(column).empty();

                    var condition_values_id = '';
                    var extra_class = '';
                    if (condition == 'product') {
                        condition_values_id = 'product-filter-' + count;
                        extra_class = 'product_fees_conditions_values_product';
                    }

                    if (isJson(response)) {
                        condition_values = document.createElement("select");
                        condition_values = setAllAttributes(condition_values, {
                            "name": "fees[product_fees_conditions_values][value_" + count + "][]",
                            "class": "afrsm_select product_fees_conditions_values product_fees_conditions_values_" + count + " multiselect2 " + extra_class,
                            "multiple": "multiple",
                            "id": condition_values_id
                        });
                        column.appendChild(condition_values);
                        var data = JSON.parse(response);
                        condition_values = insertOptions(condition_values, data);
                    } else {
                        var input_extra_class;
                        if (condition == 'quantity') {
                            input_extra_class = ' qty-class'
                        }
                        if (condition == 'weight') {
                            input_extra_class = ' weight-class'
                        }
                        if (condition == 'cart_total' || condition == 'cart_totalafter') {
                            input_extra_class = ' price-class'
                        }

                        condition_values = document.createElement(jQuery.trim(response));
                        condition_values = setAllAttributes(condition_values, {
                            "name": "fees[product_fees_conditions_values][value_" + count + "]",
                            "class": "product_fees_conditions_values" + input_extra_class,
                            "type": "text",

                        });
                        column.appendChild(condition_values);
                    }
                    column = $('#column_' + count).get(0);
                    var input_node = document.createElement('input');
                    input_node = setAllAttributes(input_node, {
                        'type': 'hidden',
                        'name': 'condition_key[value_' + count + '][]',
                        'value': ''
                    });
                    column.appendChild(input_node);

                    jQuery(".multiselect2").select2();
                    productFilter();
                    numberValidateForAdvanceRules();
                }
            });
        }

        function condition_types(text = false) {
            if (text == true) {
                return [
                    {"name": coditional_vars.equal_to, "attributes": {"value": "is_equal_to"}},
                    {"name": coditional_vars.less_or_equal_to, "attributes": {"value": "less_equal_to"}},
                    {"name": coditional_vars.less_than, "attributes": {"value": "less_then"}},
                    {"name": coditional_vars.greater_or_equal_to, "attributes": {"value": "greater_equal_to"}},
                    {"name": coditional_vars.greater_than, "attributes": {"value": "greater_then"}},
                    {"name": coditional_vars.not_equal_to, "attributes": {"value": "not_in"}},
                ];
            } else {
                return [
                    {"name": coditional_vars.equal_to, "attributes": {"value": "is_equal_to"}},
                    {"name": coditional_vars.not_equal_to, "attributes": {"value": "not_in"}},
                ];

        }
        }

        productFilter();

        function isJson(str) {
            try {
                JSON.parse(str);
            } catch (err) {
                return false;
            }
            return true;
        }

        var default_placeholder = jQuery('#fee_settings_product_cost').attr('placeholder');
        $('#fee_settings_select_fee_type').change(function () {
            if (jQuery(this).val() == 'fixed') {
                jQuery('#fee_settings_product_cost').attr('placeholder', default_placeholder);
            } else if (jQuery(this).val() == 'percentage') {
                jQuery('#fee_settings_product_cost').attr('placeholder', '%');
            }

        });

        $('body').on('click', '.condition-check-all', function () {
            $('input.multiple_delete_fee:checkbox').not(this).prop('checked', this.checked);
        });

        $('#delete-shipping-method').click(function () {
            if (0 == $('.multiple_delete_fee:checkbox:checked').length) {
                alert('Please select at least one shipping method');
                return false;
            }
            if (confirm('Are You Sure You Want to Delete?')) {
                var allVals = [];
                $(".multiple_delete_fee:checked").each(function () {
                    allVals.push($(this).val());
                });
                $.ajax({
                    type: 'GET',
                    url: coditional_vars.ajaxurl,
                    data: {
                        'action': 'afrsm_pro_wc_multiple_delete_shipping_method',
                        'nonce': coditional_vars.dsm_ajax_nonce,
                        'allVals': allVals
                    },
                    success: function (response) {
                        if (1 == response) {
                            alert('Delete Successfully');
                            $(".multiple_delete_fee").prop("checked", false);
                            location.reload();
                        }
                    }
                });
            }
        });

        saveAllIdOrderWise('on_load');

        /*Start code for save all method as per sequence in list*/
        function saveAllIdOrderWise(position) {
            var smOrderArray = [];

            $('table#shipping-methods-listing tbody tr').each(function () {
                smOrderArray.push(this.id);
            });
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsm_pro_sm_sort_order',
                    'smOrderArray': smOrderArray
                },
                success: function (response) {
                    if ('on_click' === jQuery.trim(position)) {
                        alert(coditional_vars.success_msg1);
                    }
                }
            });
        }

        /*End code for save all method as per sequence in list*/

        $(".tablesorter").tablesorter({
            headers: {
                0: {
                    sorter: false
                },
                4: {
                    sorter: false
                }
            }
        });
        var fixHelperModified = function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        };
        //Make diagnosis table sortable
        $("table#shipping-methods-listing tbody").sortable({
            helper: fixHelperModified
        });
        $("table#shipping-methods-listing tbody").disableSelection();

        $(document).on('click', '.shipping-methods-order', function () {
            saveAllIdOrderWise('on_click');
        });

        //Save Master Settings
        $(document).on('click', '#save_master_settings', function () {
            var shipping_display_mode = $('#shipping_display_mode').val();
            var chk_enable_logging;
            if ($('#chk_enable_logging').prop("checked") == true) {
                chk_enable_logging = 'on';
            } else {
                chk_enable_logging = 'off';
            }
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsm_pro_save_master_settings',
                    'shipping_display_mode': shipping_display_mode,
                    'chk_enable_logging': chk_enable_logging,
                },
                success: function (response) {
                    var div = document.createElement('div');
                    div = setAllAttributes(div, {
                        "class": "ms-msg"
                    });
                    div.textContent = coditional_vars.success_msg2;
                    $(div).insertBefore(".afrsm-section-left .afrsm-main-table");
                    $("html, body").animate({scrollTop: 0}, "slow");
                    setTimeout(function () {
                        $('.ms-msg').remove();
                    }, 2000);
                }
            });
        });

        /* Add AP Category functionality end here */

        $(document).on('click', '#clone_shipping_method', function () {
            var current_shipping_id = $(this).attr('data-attr');
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsm_pro_clone_shipping_method',
                    'current_shipping_id': current_shipping_id
                }, beforeSend: function () {
                    var div = document.createElement("div");
                    div = setAllAttributes(div, {
                        "class": "loader-overlay",
                    });

                    var img = document.createElement("img");
                    img = setAllAttributes(img, {
                        "id": "before_ajax_id",
                        "src": coditional_vars.ajax_icon
                    });

                    div.appendChild(img);
                    jQuery("#shipping-methods-listing").after(div);
                }, complete: function () {
                    jQuery(".afrsm-main-table img#before_ajax_id").remove();
                },
                success: function (response) {
                    var response_data = JSON.parse(response);
                    if ("true" === jQuery.trim(response_data['0'])) {
                        location.href = response_data['1'];
                    }
                }
            });
        });
    });
    jQuery(window).on('load', function () {
        jQuery(".multiselect2").select2();

        function allowSpeicalCharacter(str) {
            return str.replace('&#8211;', '–').replace("&gt;", ">").replace("&lt;", "<").replace("&#197;", "Å");
        }

        jQuery('.product_fees_conditions_values_product').each(function () {
            jQuery(".product_fees_conditions_values_product").select2();
            jQuery(".product_fees_conditions_values_product").select2({
                ajax: {
                    url: coditional_vars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            value: params.term,
                            action: 'afrsm_pro_product_fees_conditions_values_product_ajax'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            jQuery.each(data, function (index, text) {
                                options.push({id: text[0], text: allowSpeicalCharacter(text[1])});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });

        /*Start: Change shipping status form list section*/
        $(document).on('click', '#shipping_status_id', function () {
            var current_shipping_id = $(this).attr('data-smid');
            var current_value = $(this).prop("checked");
            $.ajax({
                type: 'GET',
                url: coditional_vars.ajaxurl,
                data: {
                    'action': 'afrsm_pro_change_status_from_list_section',
                    'current_shipping_id': current_shipping_id,
                    'current_value': current_value
                }, beforeSend: function () {
                    var div = document.createElement("div");
                    div = setAllAttributes(div, {
                        "class": "loader-overlay",
                    });

                    var img = document.createElement("img");
                    img = setAllAttributes(img, {
                        "id": "before_ajax_id",
                        "src": coditional_vars.ajax_icon
                    });

                    div.appendChild(img);
                    jQuery("#shipping-methods-listing").after(div);
                }, complete: function () {
                    jQuery(".afrsm-main-table .loader-overlay").remove();
                }, success: function (response) {
                    alert(jQuery.trim(response));
                }
            });
        });
        /*End: Change shipping status form list section*/
    });
})(jQuery);