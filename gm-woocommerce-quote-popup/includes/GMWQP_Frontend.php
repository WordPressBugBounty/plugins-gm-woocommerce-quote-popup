<?php

/**
 * This class is loaded on the front-end since its main job is 
 * to display the Admin to box.
 */

class GMWQP_Frontend  extends GMWQP_Shortcode{

	public $global_include_category;
	public $global_exclude_category;

	public function __construct () {
		
		add_filter( 'init', array( $this, 'initcut' ));
		add_filter( 'wp', array( $this, 'wpcut' ));
		add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'add_script_variation_name' ));
	}
	public function wpcut(){
		global $gmwqp_arr;
		$this->global_include_category = $gmwqp_arr['gmwqp_include_category'];
		$this->global_exclude_category = $gmwqp_arr['gmwqp_exclude_category'];
		$gmwqp_disable_cart_checkout_page = $gmwqp_arr['gmwqp_disable_cart_checkout_page'];
		$gmwqp_redirect_disable_cart_checkout_page = $gmwqp_arr['gmwqp_redirect_disable_cart_checkout_page'];
		if($gmwqp_disable_cart_checkout_page == 'yes' && $gmwqp_redirect_disable_cart_checkout_page!=''){
			if(is_cart() || is_checkout()){
				
				wp_redirect($gmwqp_redirect_disable_cart_checkout_page);
				exit;
			}	
		}
		
	}

	public function initcut(){
		global $gmwqp_arr;
		$gmwqp_display = $gmwqp_arr['gmwqp_display'];
		$gmwqp_sp_bl = $gmwqp_arr['gmwqp_sp_bl'];
		$gmwqp_enable_setting = $gmwqp_arr['gmwqp_enable_setting'];
		$gmwqp_usershow = $gmwqp_arr['gmwqp_usershow'];
		$showforuser = 'yes';
		if($gmwqp_usershow=='logged_user' && !is_user_logged_in()){
			$showforuser = 'no';
		}
		if($gmwqp_usershow=='logged_out' && is_user_logged_in()){
			$showforuser = 'no';
		}

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
		// Standalone bundle (React is bundled): no WordPress script dependencies.
		$css = GMWQP_PLUGIN_DIR . 'build/frontend/frontend.css';
		$js  = GMWQP_PLUGIN_DIR . 'build/frontend/frontend.js';
		wp_enqueue_style( 'gmwqp-frontend', GMWQP_PLUGIN_URL . 'build/frontend/frontend.css', array(), file_exists( $css ) ? filemtime( $css ) : '1', 'all' );
		wp_enqueue_script( 'gmwqp-frontend', GMWQP_PLUGIN_URL . 'build/frontend/frontend.js', array(), file_exists( $js ) ? filemtime( $js ) : '1', true );

		// Only static values here; everything else is fetched at runtime (cache safe).
		wp_localize_script( 'gmwqp-frontend', 'gmwqp_frontend_data', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		) );
	}

	function gmwqp_enquiry_single_product_shortcode($atts){
		global $gmwqp_arr;
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
			if ($gmwqp_arr['gmwqp_trasnlation_button_label']!='') {
					$gmwqp_trasnlation_button_label = esc_html($gmwqp_arr['gmwqp_trasnlation_button_label']);
			}else{
				$gmwqp_trasnlation_button_label = __('Inquiry!', 'gm-woocommerce-quote-popup' );
			}
			

			?>
			<div class="gmwqp_inquirybtn_loop">
				<a href="#" class="button gmwqp_inq wp-block-button__link wp-element-button" title="<?php echo esc_attr( $product->get_name() ); ?>" attr_id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo $gmwqp_trasnlation_button_label;?></a>
			</div>
			
			<?php
			$output = ob_get_contents();
			ob_end_clean();
		}
		
		return $output;
	}
	public function gmwqp_new_tab( $tabs ) {
		global $gmwqp_arr;
		$gmwqp_trasnlation_button_label = esc_html($gmwqp_arr['gmwqp_trasnlation_button_label']);
	    $tabs['desc_tab'] = array(
	        'title'     => __( $gmwqp_trasnlation_button_label, 'woocommerce' ),
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
	    	<div
	    		class="gmwqp-frontend-inline"
	    		data-product-id="<?php echo esc_attr( $prod_id ); ?>"
	    		data-product-title="<?php echo esc_attr( $product_title ); ?>"
	    	></div>
	    </div>
	    <?php
	}
	
	
	public function gmwqp_after_addtocart() {
		global $product;
		global $gmwqp_arr;

		if($this->gmwqp_is_exclude($product->get_id())==true){
			return;
		}

		if ($gmwqp_arr['gmwqp_trasnlation_button_label']!='') {
			$gmwqp_trasnlation_button_label = esc_html($gmwqp_arr['gmwqp_trasnlation_button_label']);
		}else{
			$gmwqp_trasnlation_button_label = __('Inquiry!', 'gm-woocommerce-quote-popup' );
		}
		?>
		<div class="gmwqp_inquirybtn">
			<a href="#" class="button gmwqp_inq wp-block-button__link wp-element-button" title="<?php echo esc_attr( $product->get_name() ); ?>"  attr_id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo $gmwqp_trasnlation_button_label;?></a>
		</div>
		<?php
	}

	

	
	

	
	public function gmwqp_after_button(  ){
		global $gmwqp_arr;
		global $product;

		if($this->gmwqp_is_exclude($product->get_id())==true){
			return;
		}
		
		if ($gmwqp_arr['gmwqp_trasnlation_button_label']!='') {
				$gmwqp_trasnlation_button_label = esc_html($gmwqp_arr['gmwqp_trasnlation_button_label']);
		}else{
			$gmwqp_trasnlation_button_label = __('Inquiry!', 'gm-woocommerce-quote-popup' );
		}
		

		?>
		<div class="gmwqp_inquirybtn_loop">
			<a href="#" class="button gmwqp_inq wp-block-button__link wp-element-button" title="<?php echo esc_attr( $product->get_name() ); ?>" attr_id="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo $gmwqp_trasnlation_button_label;?></a>
		</div>
		
		<?php
		
	}
	public function gmwqp_is_exclude($product_id){
		global $gmwqp_arr;
		$gmwqp_include_exclude = $gmwqp_arr['gmwqp_include_exclude'];
		$gmwqp_show_product_outofstock = $gmwqp_arr['gmwqp_show_product_outofstock'];
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
			if(count($is_exclude)==0){
				$isretus = false;
			}else{
				$isretus = true;
			}
		}else{
			$isretus = false;
		}
		//print_r($product);
	//	echo $product->is_in_stock()."sssssssssssss" ;
		if($gmwqp_show_product_outofstock=='yes'){
			if (  ! $product->is_in_stock() ){
				//echo "ggg";
				$isretus = false;
			}else{
				//echo "bb";
				$isretus = true;
			}
		}
		
		
		return $isretus;
	}

	public function add_script_variation_name() {
	   global $product;
	   if ( $product instanceof WC_Product && $product->is_type( 'variable' ) ) {
	   		/*echo "<pre>";
	   		print_r($product->get_name());
	   		echo "</pre>";*/
	   		ob_start();
	   		$separator = ' - ';
			?>
			var name = <?php echo wp_json_encode( $product->get_name(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>;

	        jQuery('form.cart').on('show_variation', function(event, data) {
	            var text = '';

	            jQuery.each( data.attributes, function( key, value ) {
	                text += <?php echo wp_json_encode( $separator ); ?> + value;
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



 

