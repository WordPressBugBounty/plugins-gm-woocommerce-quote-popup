<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class GMWQP_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;
        parent::__construct(array(
            'singular' => 'person',
            'plural' => 'persons',
        ));
    }
    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }
    
    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        
        return sprintf('%s',
            $item['name']
        );
    }
    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }
    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
           
        );
        $gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
        
        foreach ($gmwqp_field_customizer_field as $keymk => $valuemk) {
            $columns[$keymk] = esc_html($valuemk);
        }
        $columns['product_gmwqp'] = 'Products';
        $columns['date_insert'] = 'Date';
        return $columns;
    }
    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            /*'ID' => array('ID', true),
            'post_title' => array('post_title', false),
            'age' => array('ID', false),*/
        );
        return $sortable_columns;
    }
    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }
    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function sanitize_price_field( $meta_value ) {

      foreach ( (array) $meta_value as $k => $v ) {
        if ( is_array( $v ) ) {
          $meta_value[$k] =  sanitize_price_field( $v );
        } else {
          $meta_value[$k] = sanitize_text_field( $v );
        }
      }

      return $meta_value;

    }
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'posts'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $nonce = isset($_REQUEST['delete_enquiry_nonce']) ? $_REQUEST['delete_enquiry_nonce'] : '';
            if (!wp_verify_nonce($nonce, 'delete_enquiry')) {
                // Nonce verification failed, handle the error (e.g., display an error message)
                wp_die('Security check failed');
            }
            $ids = isset($_REQUEST['id']) ? $this->sanitize_price_field($_REQUEST['id']) : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE ID IN($ids)");
            }
        }
        
    }
    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'posts'; // do not forget about tables prefix
        $per_page = 20; // constant, how much records will be shown per page
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);
        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where post_type='gmwqp_enquiry'");
        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'ID';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT ID FROM $table_name where post_type='gmwqp_enquiry' ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        
        foreach ($this->items as $keya => $valuea) {
            $this->items[$keya]['id'] =  $valuea['ID'];
            $gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
            
            foreach ($gmwqp_field_customizer_field as $keymk => $valuemk) {
                $valuekey = get_post_meta(  $valuea['ID'], $keymk,true );
                $this->items[$keya][$keymk]  = (is_array($valuekey))?implode(",",$valuekey):esc_html($valuekey);
            }
            $this->items[$keya]['product_gmwqp'] = get_post_meta(  $valuea['ID'], 'product_gmwqp',true );
            $this->items[$keya]['date_insert'] = get_the_date( 'd-m-Y', $valuea['ID'] );
           /* $this->items[$keya]['gmwqp_name'] = get_post_meta(  $valuea['ID'], 'gmwqp_name',true );
            $this->items[$keya]['gmwqp_email'] = get_post_meta(  $valuea['ID'], 'gmwqp_email',true );
            $this->items[$keya]['subject'] = get_post_meta(  $valuea['ID'], 'subject',true );
            $this->items[$keya]['product_gmwqp'] = get_post_meta(  $valuea['ID'], 'product_gmwqp',true );
            $this->items[$keya]['gmwqp_mobile'] = get_post_meta(  $valuea['ID'], 'gmwqp_mobile',true );
            $this->items[$keya]['gmwqp_enquirytext'] = get_post_meta(  $valuea['ID'], 'gmwqp_enquirytext',true );*/
        }
        /*echo "<pre>";

        print_r($this->items);
        echo "</pre>";*/
        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}
$table = new GMWQP_List_Table();
$table->prepare_items();
$message = '';
if ('delete' === $table->current_action()) {
    $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'cltd_example'), count($_REQUEST['id'])) . '</p></div>';
}
echo $message; ?>
<a href="<?php echo get_home_url().'?action=download_enquiery_data';?>" class="button button-primary" style="margin-top:10px;">Download All Records</a>
<form id="persons-table" method="POST" action="<?php echo admin_url('admin.php?page=GMWQP&view=list'); ?>">
    <?php wp_nonce_field('delete_enquiry', 'delete_enquiry_nonce'); ?>
    <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>"/>
    <?php $table->display() ?>
</form>