<?php

class GMWQP_Cron {
	
	public function __construct () {

		add_action( 'init', array( $this, 'GMWQP_default' ) );
		
	}

	public function GMWQP_default(){
		global $gmwqp_arr,$gmwqp_translation;
		$defalarr = array(
			'gmwqp_trasnlation_button_label' => 'ENQUIRY!',
			'gmwqp_trasnlation_form_title' => 'Product Enquiry',
			'gmwqp_trasnlation_form_required' => 'Please Enter',
			'gmwqp_trasnlation_email_sucesemsg' => 'Your Message Successfully Sent!',
			'gmwqp_trasnlation_button_submit' => 'Send!',
			'gmwqp_display' => 'all',
			'gmwqp_sp_bl' => 'after_add_cart',
			'gmwqp_label_show' => 'show_label',
			'gmwqp_email_sub' => 'Get Quote',
			'gmwqp_customer_email_subject' => 'Get Quote Customer',
			'gmwqp_cart_display' => 'all',
			'gmwqp_enable_setting' => 'yes',
			'gmwqp_usershow' => 'all',
			'gmwqp_show_product_outofstock' => 'no',
			'gmwqp_remove_price' => 'no',
			'gmwqp_hide_add_to_cart' => 'no',
			'gmwqp_enquiry_btn_bg_color' =>'#000000',
			'gmwqp_enquiry_btn_text_color' =>'#ffffff',
			'gmwqp_enquiry_btn_bg_hover_color' =>'#eeeeee',
			'gmwqp_enquiry_btn_text_hover_color' =>'#ffffff',
			'gmwqp_content_beforeform' =>'',
			'gmwqp_content_afterform' =>'',
			'gmwqp_redirect_form_sub' =>'no',
			'gmwqp_disable_cart_checkout_page' =>'no',
			'gmwqp_send_enquiry_email_cutomer' =>'no',
			'gmwqp_send_enquiry_replyto_customer_email' =>'no',
			'gmwqp_captcha' =>'no',
			'gmwqp_captcha_version' =>'2',
			'gmwqp_captcha_site_key' =>'',
			'gmwqp_captcha_secrete_key' =>'',
			'gmwqp_redirect_form_sub_page' =>'',
			'gmwqp_redirect_disable_cart_checkout_page' =>'',
			'gmwqp_include_category' =>array(),
			'gmwqp_exclude_category' =>array(),
			'gmwqp_email_body' => '<table><tr><th>Name</th><td>[name]</td></tr>
<tr><th>Email</th><td>[email]</td></tr>
<tr><th>Subject</th><td>[subject]</td></tr>
<tr><th>Mobile</th><td>[mobile]</td></tr>
<tr><th>Enquiry</th><td>[enquiry]</td></tr>
<tr><th>Product</th><td>[product]</td></tr></table>',
			
			
			'gmwqp_reci_email'=>get_bloginfo('admin_email'),
			'gmwqp_include_exclude' => 'all',
			'gmwqp_webhook_url' => '',
			'gmwqp_webhook_enable' => 'no',
			
		);

		foreach ($defalarr as $keya => $valuea) {
			if (get_option( $keya )=='') {
				$gmwqp_arr[$keya]=$defalarr[$keya];
			}else{
				$gmwqp_arr[$keya]=get_option($keya);
			}
		}
		$arrin = array(
			'gmwqp_field_customizer_field' => array(
					'name' => 'Name',
					'email' => 'Email',
					'subject' => 'Subject',
					'mobile' => 'Mobile Number',
					'enquiry' => 'Enquiry',
				),
			'gmwqp_field_customizer_enble' => array(
					'name' => 'yes',
					'email' => 'yes',
					'subject' => 'yes',
					'mobile' => 'yes',
					'enquiry' => 'yes',
				),
			'gmwqp_field_customizer_required' => array(
					'name' => 'yes',
					'email' => 'yes',
					'subject' => 'yes',
					'mobile' => 'yes',
					'enquiry' => 'yes',
				),
			'gmwqp_field_customizer_type' => array(
					'name' => 'text',
					'email' => 'email',
					'subject' => 'text',
					'mobile' => 'text',
					'enquiry' => 'textarea',
				),
			'gmwqp_field_customizer_order' => array(
					'name' => '1',
					'email' => '2',
					'subject' => '3',
					'mobile' => '4',
					'enquiry' => '5',
				),
			'gmwqp_field_customizer_option' => array(
					'name' => '',
					'email' => '',
					'subject' => '',
					'mobile' => '',
					'enquiry' => '',
				),
			
			
		);
		foreach ($arrin as $keya => $valuea) {
			if (get_option( $keya )=='') {
				$gmwqp_arr[$keya]=$arrin[$keya];
			}else{
				$gmwqp_arr[$keya]=get_option($keya);
			}
			
		}
		$gmwqp_translation_arr = array(
			'gmwqp_trasnlation_button_label',
			'gmwqp_trasnlation_form_title',
			'gmwqp_trasnlation_form_required',
			'gmwqp_trasnlation_email_sucesemsg',
			'gmwqp_trasnlation_button_submit',
		);
		foreach ($gmwqp_translation_arr as $keya => $valuea) {
			$gmwqp_translation[$valuea]['label'] = $defalarr[$valuea];
			$gmwqp_translation[$valuea]['val']=$gmwqp_arr[$valuea];
			
		}
		
	}
}

?>