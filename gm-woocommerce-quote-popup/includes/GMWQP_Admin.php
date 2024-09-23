<?php

/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class GMWQP_Admin {
	public $fieldset_arr_gm = array();
	public function __construct () {

		$this->fieldset_arr_gm = array(
			'text' => 'Text',
			'select' => 'Select',
			'radio' => 'Radio',
			'checkbox' => 'Checkbox',
			'textarea' => 'Textarea',
		);


		add_action( 'admin_init', array( $this, 'GMWQP_register_settings' ) );
		add_action( 'admin_menu', array( $this, 'GMWQP_admin_menu' ) );
		add_action('admin_enqueue_scripts', array( $this, 'GMWQP_admin_script' ));
		add_action( 'init', array( $this, 'GMWQP_init' ) );
		if ( is_admin() ) {
			return;
		}
		
	}

	public function GMWQP_admin_script ($hook) {
		if($hook=='toplevel_page_GMWQP'){
		wp_enqueue_style('gmwqp_admin_css', GMWQP_PLUGIN_URL.'assents/css/admin-style.css');
	    wp_enqueue_style( 'gmwqp_select2_css' , GMWQP_PLUGIN_URL.'js/select2/select2.css');
	    wp_enqueue_script('gmwqp_select2_js', GMWQP_PLUGIN_URL.'js/select2/select2.js');
		wp_enqueue_script( 'wp-color-picker' ); 
		wp_enqueue_script('gmwqp_admin_js', GMWQP_PLUGIN_URL.'js/admin-script.js');
		}
	}

	public function GMWQP_init () {
		$args = array(
				'label'               => __( 'gmwqp_enquiry', 'gmwqp' ),
				'show_ui'             => false,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'menu_position'       => 5,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				);
	
		// Registering your Custom Post Type
		register_post_type( 'gmwqp_enquiry', $args );
	}

	public function GMWQP_admin_menu () {

		add_menu_page('Woo Quote Popup', 'Woo Quote Popup', 'manage_options', 'GMWQP', array( $this, 'GMWQP_page' ));
	}

	public function GMWQP_page() {

		
	?>
	<div>
	  
	   <h2><?php _e('Woo Quote Popup', 'gmwqp'); ?></h2>
	    <div class="about-text">
	        <p>
				Thank you for using our plugin! If you are satisfied, please reward it a full five-star <span style="color:#ffb900">★★★★★</span> rating.                        <br>
	            <a href="https://wordpress.org/support/plugin/gm-woocommerce-quote-popup/reviews/?filter=5" target="_blank">Reviews</a>
	            | <a href="https://www.codesmade.com/contact-us/" target="_blank">Support</a>
	            | <a href="https://www.codesmade.com/product-enquiry-for-woocommerce-documentation/" target="_blank">Documentation</a>
	        </p>
	    </div>
	   <?php
		$navarr = array(
			'page=GMWQP'=>'Enquiry Button Settings',
			'page=GMWQP&view=list'=>'Enquiry List',
			'page=GMWQP&view=general'=>'General Settings',
			'page=GMWQP&view=exclude'=>'Include/Exclude',
			'page=GMWQP&view=form_customizer'=>'Form Customizer',
			'page=GMWQP&view=email_customizer'=>'Email Customizer',
			'page=GMWQP&view=translate'=>'Translate',
			
		);
		?>
		<h2 class="nav-tab-wrapper">
			<?php
			foreach ($navarr as $keya => $valuea) {
				$pagexl = explode("=",$keya);
				if(!isset($pagexl[2])){
					$pagexl[2] = '';
				}
				if(!isset($_REQUEST['view'])){
					$_REQUEST['view'] = '';
				}
				?>
				<a href="<?php echo admin_url( 'admin.php?'.$keya);?>" class="nav-tab <?php if($pagexl[2]==$_REQUEST['view']){echo 'nav-tab-active';} ?>"><?php echo $valuea;?></a>
				<?php
			}
			?>
		</h2>
	   <?php
		
			if($_REQUEST['view']==''){
				include(GMWQP_PLUGIN_DIR.'includes/GMWQP_Enquiry_Button.php');
			}
			if($_REQUEST['view']=='cart'){
				//include(GMWQP_PLUGIN_DIR.'includes/GMWQP_Enquiry_Button_Cart.php');
			}
			if($_REQUEST['view']=='list'){
				include(GMWQP_PLUGIN_DIR.'includes/GMWQP_list.php');
			}
			if($_REQUEST['view']=='general'){
				include(GMWQP_PLUGIN_DIR.'includes/GMWQP_General.php');
			}
			if($_REQUEST['view']=='exclude'){
				include(GMWQP_PLUGIN_DIR.'includes/GMWQP_Exclude.php');
			}
			if($_REQUEST['view']=='form_customizer'){
				include(GMWQP_PLUGIN_DIR.'includes/GMWQP_Form_customizer.php');
			}
			if($_REQUEST['view']=='email_customizer'){
				include(GMWQP_PLUGIN_DIR.'includes/GMWQP_Email_customizer.php');
			}
			if($_REQUEST['view']=='translate'){
				include(GMWQP_PLUGIN_DIR.'includes/GMWQP_Translate.php');
			}
		
		
		?>
	</div>
	<?php
	}

	public function GMWQP_register_settings() {
		if(isset($_REQUEST['action'])){
			if($_REQUEST['action']=='add_new_field_gmwqp'){
				$gmwqp_field_customizer_enble = get_option( 'gmwqp_field_customizer_enble' );
				$gmwqp_field_customizer_required = get_option( 'gmwqp_field_customizer_required' );
				$gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
				$gmwqp_field_customizer_type = get_option( 'gmwqp_field_customizer_type' );
				$gmwqp_field_customizer_order = get_option( 'gmwqp_field_customizer_order' );
				$gmwqp_field_customizer_option = get_option( 'gmwqp_field_customizer_option' );

				$unid = 'field_'.uniqid();
				$gmwqp_field_customizer_required[$unid]=$_REQUEST['field_required_gmwqp'];
				$gmwqp_field_customizer_field[$unid]=sanitize_text_field($_REQUEST['gmwqp_field_customizer_field']);
				$gmwqp_field_customizer_type[$unid]=$_REQUEST['gmwqp_field_customizer_type'];
				$gmwqp_field_customizer_order[$unid]=$_REQUEST['gmwqp_field_customizer_order'];
				$gmwqp_field_customizer_option[$unid] = $_REQUEST['gmwqp_field_customizer_option'];
				$gmwqp_field_customizer_enble[$unid]='yes';

				update_option( 'gmwqp_field_customizer_enble', $gmwqp_field_customizer_enble );
				update_option( 'gmwqp_field_customizer_required', $gmwqp_field_customizer_required );
				update_option( 'gmwqp_field_customizer_field', $gmwqp_field_customizer_field );
				update_option( 'gmwqp_field_customizer_type', $gmwqp_field_customizer_type );
				update_option( 'gmwqp_field_customizer_order', $gmwqp_field_customizer_order );
				update_option( 'gmwqp_field_customizer_option', $gmwqp_field_customizer_option );
				
				

				wp_redirect( admin_url( 'admin.php?page=GMWQP&view=form_customizer&msg=success') );
				exit;
			}
			if($_REQUEST['action']=='delete_keyif'){
				$gmwqp_field_customizer_required = get_option( 'gmwqp_field_customizer_required' );
				$gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
				$gmwqp_field_customizer_type = get_option( 'gmwqp_field_customizer_type' );
				$gmwqp_field_customizer_order = get_option( 'gmwqp_field_customizer_order' );
				$gmwqp_field_customizer_option = get_option( 'gmwqp_field_customizer_option' );

				if(!is_array($gmwqp_field_customizer_option) && trim($gmwqp_field_customizer_option)==''){
					$gmwqp_field_customizer_option = array();
				}
				if(array_key_exists($_REQUEST['key'],$gmwqp_field_customizer_required)){
					unset($gmwqp_field_customizer_required[$_REQUEST['key']]);
				}
				if(array_key_exists($_REQUEST['key'],$gmwqp_field_customizer_field)){
					unset($gmwqp_field_customizer_field[$_REQUEST['key']]);
				}
				if(array_key_exists($_REQUEST['key'],$gmwqp_field_customizer_type)){
					unset($gmwqp_field_customizer_type[$_REQUEST['key']]);
				}
				if(array_key_exists($_REQUEST['key'],$gmwqp_field_customizer_order)){
					unset($gmwqp_field_customizer_order[$_REQUEST['key']]);
				}
				if(array_key_exists($_REQUEST['key'],$gmwqp_field_customizer_option)){
					unset($gmwqp_field_customizer_option[$_REQUEST['key']]);
				}
				

				update_option( 'gmwqp_field_customizer_required', $gmwqp_field_customizer_required );
				update_option( 'gmwqp_field_customizer_field', $gmwqp_field_customizer_field );
				update_option( 'gmwqp_field_customizer_type', $gmwqp_field_customizer_type );
				update_option( 'gmwqp_field_customizer_order', $gmwqp_field_customizer_order );
				update_option( 'gmwqp_field_customizer_option', $gmwqp_field_customizer_option );
				wp_redirect( admin_url( 'admin.php?page=GMWQP&view=form_customizer&msg=success') );
				exit;
			}
		}
		
		
		
		
		register_setting( 'gmwqp_options_group', 'gmwqp_display', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_options_group', 'gmwqp_sp_bl', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_options_group', 'gmwqp_enable_setting', array( $this, 'gmwqp_callback' ) );
		

		

		register_setting( 'gmwqp_translate_options_group', 'gmwqp_button_label', array( $this, 'gmwqp_text_callback' ) );
		register_setting( 'gmwqp_translate_options_group', 'gmwqp_form_title', array( $this, 'gmwqp_text_callback' ) );
		register_setting( 'gmwqp_translate_options_group', 'gmwqp_form_required', array( $this, 'gmwqp_text_callback' ) );
		register_setting( 'gmwqp_translate_options_group', 'gmwqp_email_sucesemsg', array( $this, 'gmwqp_text_callback' ) );

	

		register_setting( 'gmwqp_general_options_group', 'gmwqp_usershow', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_hide_add_to_cart', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_label_show', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_show_product_outofstock', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_remove_price', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_enquiry_btn_bg_color', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_enquiry_btn_text_color', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_enquiry_btn_bg_hover_color', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_enquiry_btn_text_hover_color', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_redirect_form_sub', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_redirect_form_sub_page', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_disable_cart_checkout_page', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_general_options_group', 'gmwqp_redirect_disable_cart_checkout_page', array( $this, 'gmwqp_callback' ) );

		
		register_setting( 'gmwqp_exclude_options_group', 'gmwqp_include_exclude', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_exclude_options_group', 'gmwqp_include_category', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_exclude_options_group', 'gmwqp_exclude_category', array( $this, 'gmwqp_callback' ) );



		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_field_customizer_enble', array( $this, 'gmwqp_enalyes' ) );
		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_field_customizer_required', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_field_customizer_field', array( $this, 'gmwqp_multiple_callback' ) );
		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_field_customizer_type', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_field_customizer_order', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_field_customizer_option', array( $this, 'gmwqp_accesstoken_callback' ) );
		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_content_beforeform', array( $this, 'gmwqp_textarea_callback' ) );
		register_setting( 'gmwqp_form_customizer_group', 'gmwqp_content_afterform', array( $this, 'gmwqp_textarea_callback' ) );

		register_setting( 'gmwqp_email_customizer_group', 'gmwqp_reci_email', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_email_customizer_group', 'gmwqp_email_sub', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_email_customizer_group', 'gmwqp_email_body', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_email_customizer_group', 'gmwqp_send_enquiry_email_cutomer', array( $this, 'gmwqp_callback' ) );
		register_setting( 'gmwqp_email_customizer_group', 'gmwqp_customer_email_subject', array( $this, 'gmwqp_callback' ) );

	}
	
	public function gmwqp_enalyes($option) {
		/*if($option!='yes'){

		}*/
		return $option;
	} 
	public function gmwqp_text_callback( $input ) {
        // Sanitize or validate the input before saving it
        $sanitized_input = sanitize_text_field( $input );

        return $sanitized_input;
    }
    public function gmwqp_textarea_callback( $input ) {
        // Sanitize or validate the input before saving it
         $allowed_html = array(
	        'a' => array(
	            'href' => true,
	            'title' => true,
	        ),
	        'br' => array(),
	        'em' => array(),
	        'strong' => array(),
	        'p' => array(),
	        'table' => array(
	            'border' => true,
	            'cellpadding' => true,
	            'cellspacing' => true,
	            'summary' => true,
	            'width' => true,
	            'class' => true,
	            'id' => true,
	            'style' => true,
	        ),
	        'thead' => array(),
	        'tbody' => array(),
	        'tr' => array(),
	        'th' => array(
	            'colspan' => true,
	            'rowspan' => true,
	            'scope' => true,
	        ),
	        'td' => array(
	            'colspan' => true,
	            'rowspan' => true,
	            'headers' => true,
	        ),
	        // Add more tags and attributes as needed
	    );
        $sanitized_input = wp_kses($input,$allowed_html);

        return $sanitized_input;
    }
    public function gmwqp_multiple_callback( $input ) {
        $sanitized_data = array();

        // Iterate through each field in the form
        foreach ($input as $key => $value) {
            $sanitized_data[$key] = sanitize_text_field($value);
        }

        return $sanitized_data;
    }
	public function gmwqp_accesstoken_callback($option) {
		/*print_r($option);
		exit;
		$textToStore = htmlentities($option, ENT_QUOTES, 'UTF-8');*/
		return $option;
	}
}

?>