<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: form_select.php
| Author: Frederick MC Chan (Chan)
| Co-Author: Takács Ákos (Rimelek)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

/**
 * Select2 dynamics plugin version 3.5 (stable)
 *
 * Note on Tags Support
 * $options['tags'] = default $input_value must not be multidimensional array but only as $value = array(TRUE,'2','3');
 * For tagging - set both tags and multiple to TRUE
 * http://select2.github.io/select2/
 *
 * @param        $input_name
 * @param string $label
 * @param        $input_value
 * @param array $options
 *
 * Setting up a select chain
 *
 * There will be 2 select box, Parent Select and Child Select
 * Parent Select has options value of: array(1 => 'Parent A', 2 => 'Parent B')
 * Parent Child has options value of: array(3 => 'Child A', 4 => 'Child B');
 * The way that these two select chain to each other is via $options['chain_index'] with a value of:
 * array(3 => 1, 4 => 1); ***
 * The above array is the 'id of child A' => 'id of parent of A'
 * key - current option value (i.e. +60)
 * value - parent value that this option value is chained against (i.e. Malaysia)
 *
 * To build a chain index
 * $options['chainable']    - true
 * $options['chain_to_id']  - parent id
 * $options['chain_index'] - a list of array of current id and the parent id as value ( see get_form_select_chain_index() )
 *
 * Implementation
 * 1. Do nothing for Parent Select
 * 2. In Child Select add:
 * $options['chainable'] - set to true
 * $options['chain_to_id'] - unique input_id of the Parent Select
 * $options['chain_index'] - the chain array (see ***)
 *
 * Example code
 * form_select('parent', 'Parent Sample', $parent_callback_value, ['options'=>$parent_opts]);
 * form_select('child', 'Child Sample', $child_callback_value, ['options' => $child_opts, 'chainable'=>TRUE, 'chain_to_id'=>'parent',
 *     'chain_index'=>$chain_index_opts]);
 *
 * @return string
 *
 * @package dynamics/select2
 */
function form_select( $input_name, $label, $input_value, $options = [] ) {

    $locale = fusion_get_locale();

    $title = $label ? stripinput( $label ) : ucfirst( strtolower( str_replace( "_", " ", $input_name ) ) );

    $input_value = clean_input_value( $input_value );

    $list = [];

    static $select_db = [];

    $options += [
        'input_name'           => clean_input_name( $input_name ),
        'input_id'             => clean_input_id( $input_name ),
        'type'                 => 'dropdown',
        'options'              => [],
        'required'             => FALSE,
        'regex'                => '',
        'placeholder'          => $locale['choose'],
        'deactivate'           => FALSE,
        'safemode'             => FALSE,
        'allowclear'           => FALSE,
        'multiple'             => FALSE,
        'width'                => '',
        'inner_width'          => '250px',
        'keyflip'              => FALSE,
        'tags'                 => FALSE,
        'jsonmode'             => FALSE,
        'chainable'            => FALSE,      // Set to True to Enable Chaining
        'chain_to_id'          => '',         // Set which select it has to be chained to.
        'db'                   => '',         // Specify which DB to map
        'id_col'               => '',         // Chain Primary Key Column Name
        'cat_col'              => '',         // Chain Category Column Name
        'title_col'            => '',         // Chain Title Column Name
        'custom_query'         => '',         // SQL query replacements
        'value_filter'         => ['col' => '', 'value' => NULL], // Specify if building opts has to specifically match these conditions
        'optgroup'             => FALSE,      // Enable the option group output - if db, id_col, cat_col, and title_col is specified.
        'option_pattern'       => "&#8212;",
        'display_search_count' => "5",
        'max_select'           => FALSE,
        'error_text'           => $locale['error_input_default'],
        'add_error_notice'     => FALSE,
        'error_text_notice'    => '',
        'floating_label'       => FALSE,
        'class'                => '',
        'inline'               => FALSE,
        'tip'                  => '',
        'ext_tip'              => '',
        'delimiter'            => ',',
        'callback_check'       => '',
        'stacked'              => '',
        'onchange'             => '',
        'select2_disabled'     => FALSE, // if select2_disabled is set to true, then we will not use the select2 plugin
        'parent_value'         => $locale['root'],
        'add_parent_opts'      => FALSE,
        'disable_opts'         => '',
        'hide_disabled'        => FALSE,
        'no_root'              => FALSE,
        'show_current'         => FALSE,
        'current_value'        => 0, // if set then current item will follow this value instead of the inputvalue.
        'db_cache'             => TRUE,
        'data'                 => [],
    ];

    $options['template_type'] = 'dropdown';


    if (!function_exists( 'get_option_label' )) {
        function get_option_label( $value ) {
            return $value ? html_entity_decode( parse_label( $value ) ) : '';
        }
    }

    $disable_opts = '';
    if ($options['disable_opts']) {
        $disable_opts = is_array( $options['disable_opts'] ) ? $options['disable_opts'] : explode( ',', $options['disable_opts'] );
    }

    // New DB Caching Function.
    if ($options['db'] && $options['id_col'] && $options['title_col']) {

        // Cache result
        $cache = !$options['custom_query'];

        if (empty( $select_db[$options['db']] ) || (!$cache or $options['db_cache'] === FALSE)) {
            if (!empty( $options['cat_col'] )) {
                $select_db[$options['db']] = dbquery_tree_full( $options['db'], $options['id_col'], $options['cat_col'], "ORDER BY " . $options['cat_col'] . " ASC, " . $options['id_col'] . " ASC, " . $options['title_col'] . " ASC", ($options['custom_query'] ?: "") );
            } else {

                // if there is a custom query
                $query = ($options['custom_query'] ?? "SELECT * FROM " . $options['db'] . " ORDER BY " . $options['id_col'] . " ASC, " . $options['title_col'] . " ASC");
                $sel_result = dbquery( $query );
                // then make into hierarchy
                if (dbrows( $sel_result )) {
                    while ($data = dbarray( $sel_result )) {
                        $list[0][$data[$options['id_col']]] = $data;
                    }
                    $select_db[$options['db']] = $list;
                }
            }


            /*
             * Build opt functions
             */
            if (!function_exists( 'get_form_select_opts' )) {
                // @todo: implement all options settings inherited from dbquery_select_hierarchy
                function get_form_select_opts( $data, $options, $id = 0, $level = 0 ) {
                    $list = [];
                    //array('text' => 'Parent Text', 'children' => array(1 => 'Child A' , 2 => 'Child B'));
                    if (!empty( $data[$id] )) {
                        foreach ($data[$id] as $key => $value) {
                            // Displays defined pattern
                            $pattern = "";
                            if ($options['option_pattern']) {
                                $pattern = str_repeat( $options['option_pattern'], $level ) . " ";
                            }

                            // Build List
                            if (!empty( $options['value_filter']['col'] ) && (!empty( $options['value_filter']['value'] ) || $options['value_filter']['value'] !== NULL)) {
                                if (isset( $value[$options['value_filter']['col']] ) && $value[$options['value_filter']['col']] == $options['value_filter']['value']) {
                                    $list[$key] = $pattern . get_option_label( $value[$options['title_col']] );
                                }
                            } else {
                                $list[$key] = $pattern . get_option_label( $value[$options['title_col']] );
                            }
                            // Build Child
                            if (isset( $data[$key] )) {
                                $child = get_form_select_opts( $data, $options, $key, $level + 1 );
                                if ($options['optgroup']) {
                                    $list[$key] = [
                                        'text'     => $list[$key],
                                        'children' => $child,
                                    ];
                                } else {
                                    $list = (!empty( $list )) ? $list + $child : $child;
                                }
                            }
                        }
                    } else {
                        // the list does not start with a root
                        foreach (array_keys( $data ) as $id) {
                            foreach ($data[$id] as $key => $value) {
                                // Displays defined pattern
                                $pattern = "";
                                if ($options['option_pattern']) {
                                    $pattern = str_repeat( $options['option_pattern'], $level ) . " ";
                                }
                                // Build List
                                if (!empty( $options['value_filter']['col'] ) && (!empty( $options['value_filter']['value'] ) || $options['value_filter']['value'] !== NULL)) {
                                    if (isset( $value[$options['value_filter']['col']] ) && $value[$options['value_filter']['col']] == $options['value_filter']['value']) {
                                        $list[$key] = $pattern . get_option_label( $value[$options['title_col']] );
                                    }
                                } else {
                                    $list[$key] = $pattern . get_option_label( $value[$options['title_col']] );
                                }
                                // Build Child
                                if (isset( $data[$key] )) {
                                    $child = get_form_select_opts( $data, $options, $key, $level + 1 );
                                    if ($options['optgroup']) {
                                        $list[$key] = [
                                            'text'     => $list[$key],
                                            'children' => $child,
                                        ];
                                    } else {
                                        $list = (!empty( $list )) ? $list + $child : $child;
                                    }
                                }
                            }
                        }
                    }

                    return (array)$list;
                }
            }
            /**
             * Build Chainable Reference
             * array key    current id
             *      value   parent id
             */
            if (!function_exists( 'get_form_select_chain_index' )) {
                function get_form_select_chain_index( $data, $options ) {
                    $list = [];
                    if (!empty( $data )) {
                        $data = flatten_array( $data );
                        foreach ($data as $value) {
                            $list[$value[$options['id_col']]] = $value[$options['cat_col']];
                        }
                    }

                    return $list;
                }
            }
        }

        // Automatic build chain index.
        if ($options['chainable'] && $options['chain_to_id'] && empty( $options['chain_index'] )) {
            $options['chain_index'] = get_form_select_chain_index( $select_db[$options['db']], $options );
        }

        if (!empty( $select_db[$options['db']] )) {
            // Build options and override declared options
            $options['options'] = get_form_select_opts( $select_db[$options['db']], $options );
        } else {
            $options['options'] = ['0' => $locale['no_opts']];
            $options['deactivate'] = 1;
        }
    } else if (empty( $options['options'] ) && $options['tags'] == FALSE) {
        $options['options'] = ['0' => $locale['no_opts']];
        if (!$options['jsonmode']) {
            $options['deactivate'] = TRUE;
        }
    }

    if ($options['chainable'] && $options['chain_to_id'] && !empty( $options['chain_index'] )) {
        fusion_load_script( DYNAMICS . "assets/chainselect/jquery.chained.js" );
        add_to_jquery( "$('#" . $options['input_id'] . "').chained('#" . $options['chain_to_id'] . "');" );
    }

    // Optgroup with Hierarchy
    if (!function_exists( 'form_select_build_optgroup' )) {
        function form_select_build_optgroup( $array, $input_value, $options ) {
            $html = '';
            $disable_opts = '';
            if ($options['disable_opts']) {
                $disable_opts = is_array( $options['disable_opts'] ) ? $options['disable_opts'] : explode( ',', $options['disable_opts'] );
            }

            foreach ($array as $arr => $value) {

                // where options is more than one value, pass to data attributes.
                $data_attributes = '';
                if (!empty( $value ) && is_array( $value )) {
                    $data_options = [];
                    foreach ($value as $datakey => $dataval) {
                        // This probably is incorrect and need to be revised. Need documentation link on this to fix.
                        $data_options[] = "data-$datakey='" . (is_array( $dataval ) ? implode( ',', $dataval ) : $dataval) . "'";
                    }

                    $data_attributes = " " . implode( ' ', $data_options ) . " ";
                }

                $select = "";
                $chain = (isset( $options['chain_index'][$arr] ) ? " class='" . $options['chain_index'][$arr] . "' " : "");
                $text_value = get_option_label( $value['text'] ?? $value );
                // if you have data attributes, you must have text key
                if (!empty( $text_value ) && !is_array( $text_value )) {

                    $current_value = $options['current_value'] ?: $input_value;

                    if ($options['keyflip']) { // flip mode = store array values
                        if ($input_value !== '') {
                            $select = ($input_value == $text_value) ? " selected" : "";
                        }
                        $disabled = $disable_opts && in_array( $arr, $disable_opts );
                        $hide = $disabled && $options['hide_disabled'];
                        $item = (!$hide ? "<option" . $data_attributes . " value='$text_value'" . $chain . $select . ($disabled ? 'disabled' : '') . ">" . html_entity_decode( $text_value ) . " " . ($options['show_current'] && $current_value == $text_value ? '(Current Item)' : '') . "</option>\n" : "");

                    } else {
                        if ($input_value !== '') {
                            $input_value = stripinput( $input_value ); // not sure if it can turn false to zero not null.
                            $select = (isset( $input_value ) && $input_value == $arr) ? ' selected' : '';
                        }
                        $disabled = $disable_opts && in_array( $arr, $disable_opts );
                        $hide = $disabled && $options['hide_disabled'];
                        $item = (!$hide ? "<option" . $data_attributes . " value='$arr'" . $chain . $select . ($disabled ? 'disabled' : '') . ">" . html_entity_decode( $text_value ) . ($options['show_current'] && $current_value == $text_value ? ' (Current Item)' : '') . "</option>\n" : "");
                        //$item = "<option value='$arr'".$chain.$select.">$text_value</option>\n";
                    }

                    if (isset( $value['children'] )) {

                        $opt_html = "<optgroup label='" . $value['text'] . "'>\n";
                        $opt_html .= $item;
                        $opt_html .= form_select_build_optgroup( $value['children'], $input_value, $options );
                        $opt_html .= "</optgroup>\n";

                        $html .= $opt_html;
                    } else {
                        $html .= $item;
                    }
                }
            }

            return $html;
        }
    }

    if ($options['multiple']) {
        if ($input_value !== NULL) {
            $input_value = explode( $options['delimiter'], $input_value );
        } else {
            $input_value = [];
        }
    }

    // always trim id
    $options['input_id'] = trim( $options['input_id'], "[]" );
    $allowclear = ($options['placeholder'] && $options['multiple'] || $options['allowclear']) ? "allowClear:true," : '';

    list( $options['error_class'], $options['error_text'] ) = form_errors( $options );

    // option html
    if (!$options['jsonmode'] && !$options['tags']) {

        $options['options_html'] = ($options['allowclear'] ? "<option value=''></option>\n" : '');

        // add parent value
        if ($options['no_root'] == FALSE && !empty( $options['cat_col'] ) || $options['add_parent_opts'] === TRUE) { // api options to remove root from selector. used in items creation.
            $this_select = '';
            if ($input_value !== NULL) {
                if ($input_value !== '') {
                    $this_select = ' selected';
                }
            }

            $options['options_html'] .= "<option value='0' " . $this_select . " >" . $options['parent_value'] . "</option>\n";
        }

        /**
         * Supported Formatting
         * ---------------------
         * Have an array that looks like this in 'options' key
         * array('text' => 'Parent Text', 'children' => array(1 => 'Child A' , 2 => 'Child B'));
         * or
         * array(1 => 'Option A', 2 => 'Option B');
         */
        if (is_array( $options['options'] )) {
            // Test if this is an optgroup
            $test_array = $options['options'];
            foreach ($test_array as $v) {
                if (isset( $v['text'] )) {
                    $options['optgroup'] = TRUE;
                    break;
                }
            }

            $current_value = $options['current_value'] ?: $input_value;

            if ($options['optgroup']) {

                $options['options_html'] .= form_select_build_optgroup( $options['options'], $input_value, $options );

            } else {

                foreach ($options['options'] as $arr => $v) { // outputs: key, value, class - in order

                    $select = '';
                    $chain = '';
                    // Chain method always bind to option's array key
                    if (isset( $options['chain_index'][$arr] )) {
                        $chain = ' class="' . $options['chain_index'][$arr] . '" ';
                    }

                    $v = get_option_label( $v );

                    // do a disable for filter_opts item.
                    if ($options['keyflip']) { // flip mode = store array values
                        if ($input_value !== '') {
                            $select = ($input_value == $v) ? " selected" : "";
                        }
                        $disabled = $disable_opts && in_array( $arr, $disable_opts );
                        $hide = $disabled && $options['hide_disabled'];
                        $options['options_html'] .= (!$hide ? "<option value='$v'" . $chain . $select . ($disabled ? 'disabled' : '') . ">" . html_entity_decode( $v ) . " " . ($options['show_current'] && $current_value == $v ? '(Current Item)' : '') . "</option>\n" : "");
                    } else {
                        if ($input_value !== '') {
                            //$input_value = stripinput($input_value); // not sure if can turn FALSE to zero not null.
                            $select = (isset( $input_value ) && $input_value == $arr) ? ' selected ' : '';
                        }
                        $disabled = $disable_opts && in_array( $arr, $disable_opts );
                        $hide = $disabled && $options['hide_disabled'];
                        $options['options_html'] .= (!$hide ? "<option value='$arr'" . $chain . $select . ($disabled ? 'disabled' : '') . ">" . html_entity_decode( $v ) . " " . ($options['show_current'] && $current_value == $v ? '(Current Item)' : '') . "</option>\n" : "");
                    }
                }
            }
        }
    }

    set_field_config( [
        'input_name'     => $options['input_name'],
        'title'          => clean_input_name( $title ),
        'id'             => $options['input_id'],
        'type'           => $options['type'],
        'regex'          => $options['regex'],
        'required'       => $options['required'],
        'safemode'       => $options['safemode'],
        'error_text'     => $options['error_text'],
        'callback_check' => $options['callback_check'],
        'delimiter'      => $options['delimiter'],
    ] );

    // Initialize Select2
    if ($options['select2_disabled'] === FALSE) {
        // Select 2 Multiple requires hidden DOM.
        if ($options['jsonmode'] === FALSE) {
            // not json mode (normal)
            $max_js = '';
            if ($options['multiple'] && $options['max_select']) {
                $max_js = "maximumSelectionSize : " . $options['max_select'] . ",";
            }

            $tag_js = '';
            if ($options['tags']) {
                $tag_value = json_encode( array_values( $options['options'] ) );
                // The format yield must be : `tags:["red", "green", "blue", "orange", "white", "black", "purple", "cyan", "teal"]`
                $tag_js = ($tag_value) ? "tags: " . html_entity_decode( $tag_value ) . "" : "tags: []";
            }

            if ($options['required']) {
                add_to_jquery( "
                if ($('#" . $options['input_id'] . "').select2('val')) { $('dummy-" . $options['input_id'] . "').val($('#" . $options['input_id'] . "').select2('val'));} else { $('dummy-" . $options['input_id'] . "').val('');}
                $('#" . $options['input_id'] . "').select2({
                    " . ($options['placeholder'] ? "placeholder: '" . $options['placeholder'] . "'," : '') . "
                    minimumResultsForSearch: " . $options['display_search_count'] . ",
                    " . $max_js . "
                    " . $allowclear . "
                    " . $tag_js . "
                }).bind('change', function(e) {	$('#dummy-" . $options['input_id'] . "').val($(this).val()); });
                " );
            } else {
                add_to_jquery( "
                $('#" . $options['input_id'] . "').select2({
                    " . ($options['placeholder'] ? "placeholder: '" . $options['placeholder'] . "'," : '') . "
                    minimumResultsForSearch: " . $options['display_search_count'] . ",
                    " . $max_js . "
                    " . $allowclear . "
                    " . $tag_js . "
                });
            " );
            }

        } else {
            // json mode
            add_to_jquery( "
                var this_data = [{id:0, text: '" . $options['placeholder'] . "'}];
                $('#" . $options['input_id'] . "').select2({
                placeholder: '" . $options['placeholder'] . "',
                data: this_data
                });
            " );
        }

        // For Multiple Callback JS
        if (is_array( $input_value ) && $options['multiple']) { // stores as value;
            $vals = '';
            foreach ($input_value as $arr => $val) {
                $val = html_entity_decode( $val );
                $vals .= ($arr == count( $input_value ) - 1) ? "'$val'" : "'$val',";
            }
            add_to_jquery( "$('#" . $options['input_id'] . "').select2('val', [$vals]);" );
        }
    }

    load_select2_script();

    ksort( $options );

    return fusion_get_template( 'form_inputs', [
        'input_name'    => $input_name,
        'input_label'   => $label,
        'input_value'   => $options['priority_value'] ?? $input_value,
        'input_options' => $options,
    ] );

}

/**
 * Selector for registered user
 *
 * @param        $input_name
 * @param string $label
 * @param bool $input_value - user id
 * @param array $options
 *
 * @return string
 */
function form_user_select( $input_name, $label = "", $input_value = FALSE, array $options = [] ) {

    $locale = fusion_get_locale();
    $settings = fusion_get_settings();
    $input_value = clean_input_value( $input_value );

    $options += [
        'input_name'        => clean_input_name( $input_name ),
        'input_id'          => clean_input_id( $input_name ),
        'placeholder'       => $locale['sel_user'],
        'inner_width'       => '250px',
        'file'              => '',
        'allow_self'        => FALSE,
        'callback_function' => '',
        'image_path'        => IMAGES . "avatars/",
        'type'              => 'dropdown',
        'jsonmode'          => TRUE,
        'max_select'        => 3,
        'allowclear'        => TRUE,
    ];

    // Compulsory settings
    $options['select2_disabled'] = TRUE;
    $options['multiple'] = TRUE;
    $options['tags'] = TRUE;

    $length = "minimumInputLength: 1,";

    $root_prefix = $settings['site_seo'] == 1 ? fusion_get_settings( 'siteurl' ) . "includes/" : INCLUDES;
    $root_img = $settings['site_seo'] == 1 && !defined( 'ADMIN_PANEL' ) ? fusion_get_settings( 'siteurl' ) : '';
    $path = !empty( $options['file'] ) ? $options['file'] : $root_prefix . "dynamics/assets/users/users.json.php" . ($options['allow_self'] ? "?allow_self=true" : '');

    // json value only.
    $encoded = json_encode( [] );
    if (!empty( $input_value )) {
        $encoded = $options['callback_function'] && is_callable( $options['callback_function'] ) ? $options['callback_function']( $input_value ) : user_search( $input_value, $options['delimiter'] );
    }

    $allowclear = ($options['placeholder'] && $options['multiple'] || $options['allowclear']) ? "allowClear:true," : '';

    //set values via JS
    add_to_jquery( "function avatar(item) {
        if(!item.id) {return item.text;}
        var avatar = item.avatar;
        var level = item.level;
        return '<table><tr><td style=\"\"><img alt=\"\" style=\"height:35px;\" class=\"img-rounded\" src=\"" . $root_img . $options['image_path'] . "' + avatar + '\"/></td><td style=\"padding-left:10px; padding-right:10px;\"><div><strong>' + item.text + '</strong></div><small>' + level + '</small></div></td></tr></table>';
    }

    $('#" . $options['input_id'] . "').select2({
        $length
        multiple: true,
        " . ($options['max_select'] !== FALSE ? "maximumSelectionSize: " . $options['max_select'] . "," : '') . "
        placeholder: '" . $options['placeholder'] . "',
        ajax: {
            url: '$path',
            dataType: 'json',
            data: function (term, page) {
                return {q: term};
            },
            results: function (data, page) {
                return {results: data};
            }
        },
        formatSelection: avatar,
        escapeMarkup: function(m) { return m; },
        formatResult: avatar,
         $allowclear
    })" . (!empty( $encoded ) ? ".select2('data', $encoded );" : '') );

    return form_select( $input_name, $label, $input_value, $options );
}

/* Returns Json Encoded Object used in form_select_user */
function user_search( $users, $delimiter ) {
    $user_opts = [];

    $users = explode( $delimiter, $users );

    if (!empty( $users )) {
        foreach ($users as $user) {
            $result = dbquery( "SELECT user_id, user_name, user_avatar, user_level FROM " . DB_USERS . " WHERE user_status=:status AND user_id=:id", [':status' => 0, ':id' => $user] );
            if (dbrows( $result ) > 0) {
                while ($udata = dbarray( $result )) {
                    $user_avatar = !empty( $udata['user_avatar'] ) ? $udata['user_avatar'] : "no-avatar.jpg";
                    $user_name = $udata['user_name'];
                    $user_level = getuserlevel( $udata['user_level'] );
                    $user_opts[] = [
                        'id'     => $udata['user_id'],
                        'text'   => $user_name,
                        'avatar' => $user_avatar,
                        "level"  => $user_level
                    ];
                }
            }
        }
    }

    return json_encode( $user_opts );
}

/**
 * Select2 hierarchy
 * Returns a full hierarchy nested dropdown.
 *
 * @param        $input_name
 * @param string $label
 * @param string $input_value
 * @param array $options
 * @param        $db - your db
 * @param        $name_col - the option text to show
 * @param        $id_col - unique id
 * @param        $cat_col - parent id
 *                         ## The rest of the Params are used by the function itself -- no need to handle ##
 * @param bool $self_id - not required -- import this to form_seelct
 * @param bool $id - not required
 * @param bool $level - not required
 * @param bool $index - not required
 * @param bool $data - not required
 *
 * @return string
 * @deprecated and will be removed
 * @todo: Select 2 is able to do this now, and this function should be deprecated.
 *
 */
function form_select_tree( $input_name, $label, $input_value, array $options, $db, $name_col, $id_col, $cat_col, $self_id = FALSE, $id = FALSE, $level = FALSE, $index = [], $data = FALSE ) {

    if (defined('DEVELOPER_MODE')) {
        // Adds developer notice for community to change implementation
        set_error(E_NOTICE, 'Deprecation notice: The input '.$input_name.' on this page need to be altered to form_select function which also supports hierarchy and DB. This function will be deprecated and removed by the developer team soon.', FUSION_SELF, '0');
    }

    $html = '';
    $locale = fusion_get_locale();
    $title = $label ? stripinput( $label ) : ucfirst( strtolower( str_replace( "_", " ", $input_name ) ) );
    $default_options = [
        'required'        => FALSE,
        'regex'           => '',
        'input_id'        => $input_name,
        'placeholder'     => $locale['choose'],
        'deactivate'      => FALSE,
        'safemode'        => FALSE,
        'allowclear'      => FALSE,
        'multiple'        => FALSE,
        'width'           => '',
        'inner_width'     => '250px',
        'keyflip'         => FALSE,
        'tags'            => FALSE,
        'jsonmode'        => FALSE,
        'chainable'       => FALSE,
        'max_select'      => FALSE,
        'error_text'      => $locale['error_input_default'],
        'class'           => '',
        'inline'          => FALSE,
        'tip'             => '',
        'delimiter'       => ',',
        'callback_check'  => '',
        'file'            => '',
        'parent_value'    => $locale['root'],
        'add_parent_opts' => FALSE,
        'disable_opts'    => '',
        'hide_disabled'   => FALSE,
        'no_root'         => FALSE,
        'show_current'    => FALSE,
        'query'           => '',
        'full_query'      => '',
    ];
    $options += $default_options;

    $options['input_id'] = trim( $options['input_id'], "[]" );
    if ($options['multiple']) {
        if ($input_value) {
            $input_value = explode( '|', $input_value );
        } else {
            $input_value = [];
        }
    }
    if (!$options['width']) {
        $options['width'] = $default_options['width'];
    }
    $allowclear = ($options['placeholder'] && $options['multiple'] || $options['allowclear']) ? "allowClear:true" : '';
    $disable_opts = '';
    if ($options['disable_opts']) {
        $disable_opts = is_array( $options['disable_opts'] ) ? $options['disable_opts'] : explode( ',', $options['disable_opts'] );
    }
    /* Child patern */
    $opt_pattern = str_repeat( "&#8212;", $level );

    if (!$level) {
        $level = 0;
        if (!isset( $index[$id] )) {
            $index[$id] = ['0' => $locale['no_opts']];
        }

        $error_class = '';
        if (\Defender::inputHasError( $input_name )) {
            $error_class = "has-error ";
            if (!empty( $options['error_text'] )) {
                $new_error_text = \Defender::getErrorText( $input_name );
                if (!empty( $new_error_text )) {
                    $options['error_text'] = $new_error_text;
                }
                addnotice( "danger", $options['error_text'] );
            }
        }

        $html = "<div id='" . $options['input_id'] . "-field' class='form-group " . ($options['inline'] && $label ? 'row ' : '') . $error_class . $options['class'] . "' " . ($options['inline'] && $options['width'] && !$label ? "style='width: " . $options['width'] . "'" : '') . ">\n";
        $html .= ($label) ? "<label class='control-label " . ($options['inline'] ? 'col-xs-12 col-sm-12 col-md-3 col-lg-3' : '') . "' for='" . $options['input_id'] . "'>" . $label . ($options['required'] == TRUE ? "<span class='required'>&nbsp;*</span>" : '') . " " . ($options['tip'] ? "<i class='pointer fa fa-question-circle' title=" . $options['tip'] . "></i>" : '') . "</label>\n" : '';
        $html .= $options['inline'] && $label ? "<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>\n" : "";
    }
    if ($level == 0) {
        add_to_jquery( "
        $('#" . $options['input_id'] . "').select2({
        placeholder: '" . $options['placeholder'] . "',
        $allowclear
        });
        " );
        if (is_array( $input_value ) && $options['multiple']) { // stores as value;
            $vals = '';
            foreach ($input_value as $arr => $val) {
                $vals .= ($arr == count( $input_value ) - 1) ? "'$val'" : "'$val',";
            }
            add_to_jquery( "$('#" . $options['input_id'] . "').select2('val', [$vals]);" );
        }
        $html .= "<select name='$input_name' id='" . $options['input_id'] . "' style='width: " . (!empty( $options['inner_width'] ) ? $options['inner_width'] : $default_options['inner_width']) . "'" . ($options['deactivate'] ? " disabled" : "") . ($options['multiple'] ? " multiple" : "") . ">";
        $html .= $options['allowclear'] ? "<option value=''></option>\n" : '';
        if ($options['no_root'] == FALSE) { // api options to remove root from selector. used in items creation.
            $this_select = '';
            if ($input_value !== NULL) {
                if ($input_value !== '') {
                    $this_select = 'selected';
                }
            }
            $html .= ($options['add_parent_opts'] == TRUE) ? "<option value='0' " . $this_select . ">$opt_pattern " . $locale['parent'] . "</option>\n" : "<option value='0' " . $this_select . " >$opt_pattern " . $options['parent_value'] . "</option>\n";
        }

        $index = dbquery_tree( $db, $id_col, $cat_col, $options['query'], $options['full_query'] );
        if (!empty( $index )) {
            $data = dropdown_select( $db, $id_col, $name_col, $cat_col, implode( ',', flatten_array( $index ) ), $options['query'], $options['full_query'] );
        }
    }

    if (!$id) {
        $id = 0;
    }

    if (isset( $index[$id] ) && !empty( $data )) {
        foreach ($index[$id] as $value) {
            // value is the array
            //$hide = $disable_branch && $value == $self_id ? 1 : 0;
            $name = $data[$value][$name_col];

            $name = PHPFusion\QuantumFields::parseLabel( $name );
            $select = ($input_value !== "" && ($input_value == $value)) ? 'selected' : '';
            $disabled = $disable_opts && in_array( $value, $disable_opts );
            $hide = $disabled && $options['hide_disabled'];
            // do a disable for filter_opts item.
            $html .= (!$hide) ? "<option value='$value' " . $select . " " . ($disable_opts && in_array( $value, $disable_opts ) ? 'disabled' : '') . " >$opt_pattern $name " . ($options['show_current'] && $self_id == $value ? '(Current Item)' : '') . "</option>\n" : '';
            if (isset( $index[$value] ) && (!$hide)) {
//                $html .= form_select_tree( $input_name, $label, $input_value, $options, $db, $name_col, $id_col, $cat_col, $self_id, $value, $level + TRUE, $index, $data );
            }
        }
    }
    if (!$level) {
        $html .= "</select>\n";
        $html .= (($options['required'] == 1 && \Defender::inputHasError( $input_name )) || \Defender::inputHasError( $input_name )) ? "<div id='" . $options['input_id'] . "-help' class='label label-danger p-5 display-inline-block'>" . $options['error_text'] . "</div>" : "";
        $html .= $options['inline'] && $label ? "</div>\n" : '';
        $html .= "</div>\n";
        if ($options['required']) {
            $html .= "<input class='req' id='dummy-" . $options['input_id'] . "' type='hidden'>\n"; // for jscheck
        }
        $input_name = ($options['multiple']) ? str_replace( "[]", "", $input_name ) : $input_name;
        \Defender::add_field_session(
            [
                'input_name'     => $input_name,
                'title'          => trim( $title, '[]' ),
                'id'             => $options['input_id'],
                'type'           => 'dropdown',
                'regex'          => $options['regex'],
                'required'       => $options['required'],
                'safemode'       => $options['safemode'],
                'error_text'     => $options['error_text'],
                'callback_check' => $options['callback_check'],
                'delimiter'      => $options['delimiter'],
            ]
        );
    }

    load_select2_script();

    return $html;
}

/*
 * Optimized performance by adding a self param to implode to fetch only certain rows
 */
function dropdown_select( $db, $id_col, $name_col, $cat_col, $index_values, $filter = '', $query_replace = '' ) {
    $data = [];
    $query = "SELECT $id_col, $name_col, $cat_col FROM " . $db . " " . ($filter ? $filter . " AND " : 'WHERE') . " $id_col IN ($index_values) ORDER BY $name_col ASC";
    if (!empty( $query_replace )) {
        $query = $query_replace;
    }
    $result = dbquery( $query );
    while ($row = dbarray( $result )) {
        $id = $row[$id_col];
        $data[$id] = $row;
    }

    return $data;
}
