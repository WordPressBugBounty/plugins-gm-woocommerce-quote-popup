<?php

if ( ! defined( 'ABSPATH' ) ) exit;
class GMWQP_API {
    
    public function __construct() {
        // Register the REST API route on plugin activation
        add_action('rest_api_init', array($this, 'GMWQP_rest_api_init'));
    }

    public function GMWQP_rest_api_init() {
        // Register a custom route for saving settings
        register_rest_route('gmwqp/v1', '/save-settings', array(
            'methods' => 'POST',
            'callback' => array($this, 'GMWQP_save_multiple_settings'),
            'permission_callback' => array($this, 'GMWQP_permission_callback'),
        ));

        register_rest_route('gmwqp/v1', '/save-customfield', array(
            'methods' => 'POST',
            'callback' => array($this, 'GMWQP_save_custom_field'),
            'permission_callback' => array($this, 'GMWQP_permission_callback'),
        ));
        register_rest_route('gmwqp/v1', '/delete-customfield', array(
            'methods' => 'POST',
            'callback' => array($this, 'GMWQP_delete_custom_field'),
            'permission_callback' => array($this, 'GMWQP_permission_callback'),
        ));

        register_rest_route('gmwqp/v1', '/deleteallenquirys', array(
            'methods' => 'POST',
            'callback' => array($this, 'GMWQP_deleteallenquirys'),
            'permission_callback' => array($this, 'GMWQP_permission_callback'),
        ));

        // Register a custom route for fetching settings (GET)
        register_rest_route('gmwqp/v1', '/get-settings', array(
            'methods' => 'GET',
            'callback' => array($this, 'GMWQP_get_settings'),
            'permission_callback' => array($this, 'GMWQP_permission_callback'),
        ));
        register_rest_route('gmwqp/v1', '/get-enquiries', array(
            'methods'             => WP_REST_Server::READABLE, // Instead of 'GET', use predefined constant
            'callback'            => array($this, 'GMWQP_get_enquirys'),
            'permission_callback' => array($this, 'GMWQP_permission_callback'),
            'args'                => array(  // Define expected query parameters
                'page' => array(
                    'description'       => 'Current page number',
                    'type'              => 'integer',
                    'default'           => 1,
                    'sanitize_callback' => 'absint',
                    'validate_callback' => function($param, $request, $key) {
                        return $param > 0;
                    },
                ),
                'per_page' => array(
                    'description'       => 'Number of items per page',
                    'type'              => 'integer',
                    'default'           => 10,
                    'sanitize_callback' => 'absint',
                    'validate_callback' => function($param, $request, $key) {
                        return $param > 0 && $param <= 100; // Restrict max per_page to 100
                    },
                ),
                'start_date' => array(
                    'description'       => 'Filter by start date',
                    'type'              => 'string',
                    'format'            => 'date',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'end_date' => array(
                    'description'       => 'Filter by end date',
                    'type'              => 'string',
                    'format'            => 'date',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'filter_key' => array(
                    'description'       => 'Filter by field key',
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                'filter_value' => array(
                    'description'       => 'Filter by field value',
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        ));

    }

    // Save multiple settings
    public function GMWQP_save_multiple_settings($request) {
         // Get nonce from request headers
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Invalid nonce',
        ), 403); // Invalid nonce error
        }
        // Retrieve the settings array from the request body
        $settings = $request->get_param('settings');

        if (!is_array($settings)) {
            return new WP_Error('invalid_settings', 'Settings must be an array', array('status' => 400));
        }

        $response = array();

        // Loop through each setting and save it
        foreach ($settings as $option_name => $option_value) {
            // Sanitize the option name
            $option_name = sanitize_key($option_name);
           /* echo "<pre>";
            print_r($option_value);
            echo "</pre>";*/
            // Sanitize the option value based on your use case. Example:
            if (is_array($option_value)) {
                $option_value = array_map('sanitize_text_field', $option_value); // Sanitize array values
            } else {
                if ($option_name=='gmwqp_email_body') {
                    $option_value = wp_kses_post($option_value); // Sanitize single value
                }elseif($option_name=='gmwqp_content_beforeform'){
                    $option_value = wp_kses_post($option_value); 
                }elseif($option_name=='gmwqp_content_afterform'){
                    $option_value = wp_kses_post($option_value); 
                }else{
                    $option_value = sanitize_text_field($option_value);
                }
            }

            // Save the option
            $result = update_option($option_name, $option_value);

            // Add the result to the response
            $response[$option_name] = $result ? 'saved' : 'failed';
        }

        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Settings processed',
            'results' => $response,
        ));
    }

    // Save multiple settings
    public function GMWQP_save_custom_field($request) {
         // Get nonce from request headers
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Invalid nonce',
            ), 403); // Invalid nonce error
        }
        global $gmwqp_arr;
        $settings = $request->get_param('settings');
        // Sanitize and retrieve the data from the AJAX request
        $field_name = isset($settings['name']) && $settings['name'] != 'null' ? sanitize_text_field($settings['name']) : '';
        $field_label = sanitize_text_field($settings['label']);
        $field_order = sanitize_text_field($settings['order']);
        $field_type = sanitize_text_field($settings['type']);
        $field_required = sanitize_text_field($settings['required']);
        $field_enable = sanitize_text_field($settings['enable']);
        $field_options = sanitize_textarea_field($settings['options']);
        // Validate the required fields
        if (empty($field_label)) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Field label is required.',
            ), 403);
        }
        if (empty($field_order) ) {
             return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Field order is required.',
            ), 403);
        }
        if (empty($field_type) ) {
             return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Field type is required.',
            ), 403);
        }
        if (empty($field_required) ) {
             return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Field required is required.',
            ), 403);
        }
        if (empty($field_enable) ) {
             return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Field enable is required.',
            ), 403);
        }

        $gmwqp_field_customizer_enble = $gmwqp_arr['gmwqp_field_customizer_enble'];
        $gmwqp_field_customizer_required = $gmwqp_arr['gmwqp_field_customizer_required'];
        $gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];
        $gmwqp_field_customizer_type = $gmwqp_arr['gmwqp_field_customizer_type'];
        $gmwqp_field_customizer_order = $gmwqp_arr['gmwqp_field_customizer_order'];
        $gmwqp_field_customizer_option = $gmwqp_arr['gmwqp_field_customizer_option'];
        if (!is_array($gmwqp_field_customizer_option)) {
            $gmwqp_field_customizer_option = []; 
        }
        if($field_name!=''){
            $unid = $field_name;
        }else{
            $unid = 'field_'.uniqid();
        }
        
        $gmwqp_field_customizer_required[$unid]=$field_required;
        $gmwqp_field_customizer_field[$unid]=$field_label;
        $gmwqp_field_customizer_type[$unid]=$field_type;
        $gmwqp_field_customizer_order[$unid]=$field_order;
        $gmwqp_field_customizer_option[$unid] = $field_options;
        $gmwqp_field_customizer_enble[$unid]=$field_enable;

        update_option( 'gmwqp_field_customizer_enble', $gmwqp_field_customizer_enble );
        update_option( 'gmwqp_field_customizer_required', $gmwqp_field_customizer_required );
        update_option( 'gmwqp_field_customizer_field', $gmwqp_field_customizer_field );
        update_option( 'gmwqp_field_customizer_type', $gmwqp_field_customizer_type );
        update_option( 'gmwqp_field_customizer_order', $gmwqp_field_customizer_order );
        update_option( 'gmwqp_field_customizer_option', $gmwqp_field_customizer_option );

        return rest_ensure_response(array(
            'success' => true,
            'message' => 'Settings processed',
        ));
    }
    
   public function GMWQP_deleteallenquirys($request) {
        // Get nonce from request headers
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Invalid nonce',
            ), 403); // Invalid nonce error
        }

        global $wpdb;
        
        // Get the 'settings' parameter from the request
        $settings = $request->get_param('settings');
        // Get the ID parameter from the settings
        $id = isset($settings['id']) ? $settings['id'] : null;

        if ($id) {
            // Delete meta data for the specific post (enquiry) by ID
            $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->postmeta} WHERE post_id = %d", $id));

            // Delete the specific post (enquiry) by ID
            $result = $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->posts} WHERE ID = %d AND post_type = 'gmwqp_enquiry'", $id));

            // Check if the specific post was deleted
            if ($result === false) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => 'Error deleting the enquiry.',
                ), 500);
            }

            // Return success message for single enquiry deletion
            return new WP_REST_Response(array(
                'success' => true,
                'message' => 'Enquiry deleted successfully.',
            ), 200);
        } else {
            // If no ID is provided, delete all enquiries and their meta data
            $wpdb->query("DELETE pm FROM {$wpdb->postmeta} pm
                          INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                          WHERE p.post_type = 'gmwqp_enquiry'");

            // Delete all posts of type 'gmwqp_enquiry'
            $result = $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'gmwqp_enquiry'");

            // Check if posts were deleted
            if ($result === false) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => 'Error deleting enquiries.',
                ), 500);
            }

            // Return success message for deleting all enquiries
            return new WP_REST_Response(array(
                'success' => true,
                'message' => 'All enquiries and their related meta data deleted successfully.',
            ), 200);
        }
    }



    // Save multiple settings
    public function GMWQP_delete_custom_field($request) {
         // Get nonce from request headers
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Invalid nonce',
            ), 403); // Invalid nonce error
        }
        $settings = $request->get_param('settings');
        global $gmwqp_arr;
        $field_name = sanitize_text_field($settings['name']);

        // Validate the required fields
        if (empty($field_name)) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Field name is required.',
            ), 403);
        }
        $gmwqp_field_customizer_enble = $gmwqp_arr['gmwqp_field_customizer_enble'];
        $gmwqp_field_customizer_required = $gmwqp_arr['gmwqp_field_customizer_required'];
        $gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];
        $gmwqp_field_customizer_type = $gmwqp_arr['gmwqp_field_customizer_type'];
        $gmwqp_field_customizer_order = $gmwqp_arr['gmwqp_field_customizer_order'];
        $gmwqp_field_customizer_option = $gmwqp_arr['gmwqp_field_customizer_option'];


        if(!is_array($gmwqp_field_customizer_option) && trim($gmwqp_field_customizer_option)==''){
            $gmwqp_field_customizer_option = array();
        }
        if(array_key_exists($field_name,$gmwqp_field_customizer_enble)){
            unset($gmwqp_field_customizer_enble[$field_name]);
        }
        if(array_key_exists($field_name,$gmwqp_field_customizer_required)){
            unset($gmwqp_field_customizer_required[$field_name]);
        }
        if(array_key_exists($field_name,$gmwqp_field_customizer_field)){
            unset($gmwqp_field_customizer_field[$field_name]);
        }
        if(array_key_exists($field_name,$gmwqp_field_customizer_type)){
            unset($gmwqp_field_customizer_type[$field_name]);
        }
        if(array_key_exists($field_name,$gmwqp_field_customizer_order)){
            unset($gmwqp_field_customizer_order[$field_name]);
        }
        if(array_key_exists($field_name,$gmwqp_field_customizer_option)){
            unset($gmwqp_field_customizer_option[$field_name]);
        }
        //print_r($gmwqp_field_customizer_type);
        
        update_option( 'gmwqp_field_customizer_enble', $gmwqp_field_customizer_enble );
        update_option( 'gmwqp_field_customizer_required', $gmwqp_field_customizer_required );
        update_option( 'gmwqp_field_customizer_field', $gmwqp_field_customizer_field );
        update_option( 'gmwqp_field_customizer_type', $gmwqp_field_customizer_type );
        update_option( 'gmwqp_field_customizer_order', $gmwqp_field_customizer_order );
        update_option( 'gmwqp_field_customizer_option', $gmwqp_field_customizer_option );
         return rest_ensure_response(array(
            'success' => true,
            'message' => 'Field deleted successfully.',
        ));
    }

    // Retrieve settings
    public function GMWQP_get_settings($request) {
        global $gmwqp_arr,$gmwqp_translation;
         // Get nonce from request headers
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Invalid nonce',
            ), 403); // Invalid nonce error
        }
        $response = array();

        // Retrieve each option and add it to the response array
        foreach ($gmwqp_arr as $key_option=>$value_option) {
            $response[$key_option] = $value_option;
        }
        
        $gmwqp_field_customizer_enble = $gmwqp_arr['gmwqp_field_customizer_enble'];
        $gmwqp_field_customizer_required = $gmwqp_arr['gmwqp_field_customizer_required'];
        $gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];
        $gmwqp_field_customizer_type = $gmwqp_arr['gmwqp_field_customizer_type'];
        $gmwqp_field_customizer_order = $gmwqp_arr['gmwqp_field_customizer_order'];
        $gmwqp_field_customizer_option = $gmwqp_arr['gmwqp_field_customizer_option'];
        $gmwqp_fields =array();
        foreach ($gmwqp_field_customizer_enble as $keylooparrm => $valuelooparrm) {
           
                $fieldssetup =array();
                $fieldssetup['enable']=$gmwqp_field_customizer_enble[$keylooparrm];
                $fieldssetup['name']=$keylooparrm;
                $fieldssetup['type']=$gmwqp_field_customizer_type[$keylooparrm];
                $fieldssetup['order']=$gmwqp_field_customizer_order[$keylooparrm];
                $fieldssetup['label']=esc_html($gmwqp_field_customizer_field[$keylooparrm]);
                $fieldssetup['required']=(isset($gmwqp_field_customizer_required[$keylooparrm]))?$gmwqp_field_customizer_required[$keylooparrm]:'';
                $fromtype = array("select", "radio", "multiselect", "checkbox");
                if (in_array( $gmwqp_field_customizer_type[$keylooparrm], $fromtype)){
                    $fieldssetup['options']=explode("\n",$gmwqp_field_customizer_option[$keylooparrm]);
                }else{
                    $fieldssetup['options'] = array();
                }
                $gmwqp_fields[]=$fieldssetup;
           
            
        }
        $response['custom_fields'] = $gmwqp_fields;
        return rest_ensure_response($response);
    }

    // Retrieve settings
    public function GMWQP_get_enquirys($request) {
        global $gmwqp_arr, $gmwqp_translation;
        
        // Get nonce from request headers
        $nonce = $request->get_header('X-WP-Nonce');

        // Verify nonce
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Invalid nonce',
            ), 403); // Invalid nonce error
        }

        $response = [];

        // Pagination parameters
        $page      = max(1, intval($request->get_param('page'))); // Default to page 1
        $per_page  = max(1, intval($request->get_param('per_page'))); // Default to 10
        $offset    = ($page - 1) * $per_page;
        $start_date = $request->get_param('start_date');
        $end_date = $request->get_param('end_date');
        $filter_key = $request->get_param('filter_key');
        $filter_value = $request->get_param('filter_value');

        // Build query args
        $args = array(
            'post_type'      => 'gmwqp_enquiry',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => $page,
            'offset'         => $offset,
        );

        // Add date query if date filter is set
        if (!empty($start_date) || !empty($end_date)) {
            $date_query = array(
                'inclusive' => true,
            );
            if (!empty($start_date)) {
                $date_query['after'] = $start_date;
            }
            if (!empty($end_date)) {
                $date_query['before'] = $end_date;
            }
             $args['date_query'] = array($date_query);
        }

        if (!empty($filter_key) && !empty($filter_value)) {
            $args['meta_query'] = array(
                array(
                    'key'     => $filter_key,
                    'value'   => $filter_value,
                    'compare' => 'LIKE'
                )
            );
        }

        // Fetch query to get posts and total count
        $query = new WP_Query($args);
        $total_posts = $query->found_posts;
        $total_pages = ceil($total_posts / $per_page);
        $fields = $query->posts;

        $gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];

        // Ensure the option is an array
        if (!is_array($gmwqp_field_customizer_field)) {
            $gmwqp_field_customizer_field = [];
        }

        // Initialize data array
        $data = array_map(function ($field) use ($gmwqp_field_customizer_field) {
            $item = [
                'id'    => $field->ID,
                'date'  => get_the_date('Y-m-d H:i:s', $field->ID), // Add date to item
            ];

            // Retrieve custom meta fields
            foreach ($gmwqp_field_customizer_field as $keymk => $valuemk) {
                $valuekey = get_post_meta($field->ID, $keymk, true);
                $item[$keymk] = is_array($valuekey) ? implode(",", $valuekey) : esc_html($valuekey);
            }

            return $item;
        }, $fields);

        // Add data and pagination info to the response
        $response['enquiry'] = $data;
        $response['pagination'] = [
            'total_items' => $total_posts,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'per_page' => $per_page,
        ];
        $response['header'] = [
        ];
         $response['header']['id'] ='Id';
         $response['header']['date'] ='Date'; // Add Date header
        foreach ($gmwqp_field_customizer_field as $keymk => $valuemk) {
             $response['header'][$keymk] = $valuemk;
        }
        return rest_ensure_response($response);
    }


    // Permission callback: only allow admins
    public function GMWQP_permission_callback($request) {
        return current_user_can('manage_options'); // Allow only admins
    }
}
