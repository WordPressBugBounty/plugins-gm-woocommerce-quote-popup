<?php

/**
 * This class is loaded on the front-end since its main job is 
 * to display the Admin to box.
 */

class GMWQP_Frontend  extends GMWQP_Shortcode{

	public $global_include_category;
	public $global_exclude_category;

	public function __construct () {

		$this->global_include_category = get_option( 'gmwqp_include_category' );
		$this->global_exclude_category = get_option( 'gmwqp_exclude_category' );
		add_filter( 'init', array( $this, 'initcut' ));
		add_filter( 'wp', array( $this, 'wpcut' ));
		add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'add_script_variation_name' ));
		
	}

	public function wpcut(){
		$gmwqp_disable_cart_checkout_page = get_option( 'gmwqp_disable_cart_checkout_page' );
		$gmwqp_redirect_disable_cart_checkout_page = get_option( 'gmwqp_redirect_disable_cart_checkout_page' );
		if($gmwqp_disable_cart_checkout_page == 'yes'){
			if(is_cart() || is_checkout()){
				
				wp_redirect(get_permalink($gmwqp_redirect_disable_cart_checkout_page));
				exit;
			}	
		}
		
	}

	public function initcut(){

		$gmwqp_display = get_option( 'gmwqp_display' );
		$gmwqp_sp_bl = get_option( 'gmwqp_sp_bl' );
		$gmwqp_enable_setting = get_option( 'gmwqp_enable_setting' );
		$gmwqp_usershow = get_option( 'gmwqp_usershow' );
		$showforuser = 'yes';
		if($gmwqp_usershow=='logged_user' && !is_user_logged_in()){
			$showforuser = 'no';
		}
		if($gmwqp_usershow=='logged_out' && is_user_logged_in()){
			$showforuser = 'no';
		}
		add_filter( 'woocommerce_after_shop_loop_item', array( $this, 'gmwqp_addcssloop' ), 10, 3 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'gmwqp_addcsssingle' ) ,35);

		if($gmwqp_enable_setting=='yes' && $showforuser == 'yes'){
			if($gmwqp_display=='all'){
				add_filter( 'woocommerce_after_shop_loop_item', array( $this, 'gmwqp_after_button' ), 10, 3 );
			}
			if($gmwqp_sp_bl=='after_add_cart'){
				add_action( 'woocommerce_single_product_summary', array( $this, 'gmwqp_after_addtocart' ),35 );
			}elseif($gmwqp_sp_bl=='tabshow'){
				add_action( 'woocommerce_product_tabs', array( $this, 'gmwqp_new_tab' ) );
			}
		}
		
		add_action( 'wp_enqueue_scripts',  array( $this, 'gmwqp_insta_scritps' ) );
		add_shortcode('gmwqp_enquiry_single_product', array( $this, 'gmwqp_enquiry_single_product_shortcode' ));
	}

	public function gmwqp_insta_scritps () {
		wp_enqueue_style('gmwqp-stylee', GMWQP_PLUGIN_URL . '/assents/css/style.css', array(), '1.0.0', 'all');
		wp_enqueue_script('gmwqp-script', GMWQP_PLUGIN_URL . '/assents/js/script.js', array(), '1.0.0', true );
		wp_localize_script( 'gmwqp-script', 'gmwqp_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

	function gmwqp_enquiry_single_product_shortcode($atts){
		$output ='';
		global $post;
		if (isset($atts['id']) && $atts['id']!='') {
			$product_id = $atts['id'];
			$product = wc_get_product( $product_id );
		}else{
			$product_id = $post->ID;
			$product = wc_get_product( $product_id );
		}
		if(!empty($product)){
			if($this->gmwqp_is_exclude($product->get_id())==true){
			return;
			}
			ob_start();
			if (get_option('gmwqp_button_label')!='') {
					$gmwqp_button_label = esc_html(get_option('gmwqp_button_label'));
			}else{
				$gmwqp_button_label = __('Inquiry!', 'gmwqp' );
			}
			
			?>
			<div class="gmwqp_inquirybtn_loop">
				<a href="#" class="button gmwqp_inq wp-block-button__link wp-element-button " title="<?php echo $product->get_name(); ?>" attr_id="<?php echo $product->get_id();?>"><?php echo $gmwqp_button_label;?></a>
			</div>
			
			<?php
			
			$output = ob_get_contents();
			ob_end_clean();
		}
		return $output;
	}
	
	public function gmwqp_new_tab( $tabs ) {
		$gmwqp_button_label = esc_html(get_option('gmwqp_button_label'));
	    $tabs['desc_tab'] = array(
	        'title'     => __( $gmwqp_button_label, 'woocommerce' ),
	        'priority'  => 50,
	        'callback'  => array( $this, 'gmwqp_new_enquiry' )
	    );
	    return $tabs;
	}

	public function gmwqp_new_enquiry()  {
		
	    $prod_id = get_the_ID();
	    $product_title = get_the_title();
	    ?>
	    <div class="gmwqp_form_dls">
	    	<?php
			$this->gmwqp_form_footer($product_title,true,$prod_id);
			?>
	    </div>
	    <?php
	}
	
	
	public function gmwqp_after_addtocart() {
		global $product;

		if($this->gmwqp_is_exclude($product->get_id())==true){
			return;
		}

		if (get_option('gmwqp_button_label')!='') {
			$gmwqp_button_label = esc_html(get_option('gmwqp_button_label'));
		}else{
			$gmwqp_button_label = __('Inquiry!', 'gmwqp' );
		}
		?>
		<div class="gmwqp_inquirybtn">
			<a href="#" class="button gmwqp_inq wp-block-button__link wp-element-button " title="<?php echo $product->get_name(); ?>"  attr_id="<?php echo $product->get_id();?>"><?php echo $gmwqp_button_label;?></a>
		</div>
		<?php
	}

	

	
	

	
	public function gmwqp_after_button(  ){
		global $product;

		if($this->gmwqp_is_exclude($product->get_id())==true){
			return;
		}
		
		if (get_option('gmwqp_button_label')!='') {
				$gmwqp_button_label = esc_html(get_option('gmwqp_button_label'));
		}else{
			$gmwqp_button_label = __('Inquiry!', 'gmwqp' );
		}
		

		?>
		<div class="gmwqp_inquirybtn_loop">
			<a href="#" class="button gmwqp_inq wp-block-button__link wp-element-button " title="<?php echo $product->get_name(); ?>" attr_id="<?php echo $product->get_id();?>"><?php echo $gmwqp_button_label;?></a>
		</div>
		
		<?php
		
	}
	public function gmwqp_addcssloop(  ){
		global $product;
		if($this->gmwqp_is_exclude($product->get_id())==true){
			return;
		}
		?>
		<style type="text/css">
			<?php
			if (get_option( 'gmwqp_remove_price' ) == "yes") {
			?>
			.post-<?php echo  $product->get_id();?> .price{
				display: none !important;
			}
			<?php 
			}
			?>
		</style>
		<?php
	}
	public function gmwqp_addcsssingle(  ){
		global $product;
		if($this->gmwqp_is_exclude($product->get_id())==true){
			return;
		}
		?>
		<style type="text/css">
			<?php
			if (get_option( 'gmwqp_remove_price' ) == "yes") {
			?>
			.post-<?php echo  $product->get_id();?> .price{
				display: none !important;
			}
			<?php 
			}
			?>
		</style>
		<?php
	}
	

	

	

	public function gmwqp_is_exclude($product_id){
		$gmwqp_include_exclude = get_option( 'gmwqp_include_exclude' );
		$gmwqp_show_product_outofstock = get_option( 'gmwqp_show_product_outofstock' );
		$product = wc_get_product( $product_id );
		$isretus = false;
		$product_cats_ids = wc_get_product_term_ids( $product_id, 'product_cat' );
		if ($gmwqp_include_exclude=='include') {
			$includeids = (empty($this->global_include_category))?array():$this->global_include_category;
			$is_include = array_intersect($includeids,$product_cats_ids);
			if(count($is_include)==0){
				$isretus = true;
			}
		}elseif($gmwqp_include_exclude=='exclude'){
			$excludeids = (empty($this->global_exclude_category))?array():$this->global_exclude_category;
			$is_exclude = array_intersect($excludeids,$product_cats_ids);
			if(count($is_exclude)>0){
				$isretus = true;
			}
		}else{
			$isretus = false;
		}
		//print_r($product);
	//	echo $product->is_in_stock()."sssssssssssss" ;
		if($gmwqp_show_product_outofstock=='yes'){
			if ( ! $product->managing_stock() && ! $product->is_in_stock() ){
				//echo "ggg";
			}else{
				//echo "bb";
				$isretus = true;
			}
		}
		//echo $isretus;
		
		return $isretus;
	}

	public function add_script_variation_name() {
	   global $product;
	   if ( $product->is_type( 'variable' ) ) {
	   		/*echo "<pre>";
	   		print_r($product->get_name());
	   		echo "</pre>";*/
	   		ob_start();
	   		$separator = ' - ';
			?>
			var name = '<?php global $product; echo $product->get_name(); ?>';

	        jQuery('form.cart').on('show_variation', function(event, data) {
	            var text = '';

	            jQuery.each( data.attributes, function( key, value ) {
	                text += '<?php echo $separator; ?>' + value;
	            });
	            setInterval(function () {
	            	jQuery('.gmqqp_product_vl').val( name + text );
	            }, 2000);
	            

	        }).on('hide_variation', function(event, data) {
	            jQuery('.gmqqp_product_vl').val( name );
	        });
			<?php
			$output = ob_get_contents();
			ob_end_clean();
	      	wc_enqueue_js($output);
	   }
	}
}



 
