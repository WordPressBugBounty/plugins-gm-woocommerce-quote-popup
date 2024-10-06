<?php

class GMWQP_Cron {
	
	public function __construct () {

		add_action( 'init', array( $this, 'GMWQP_default' ) );
		
	}

	public function gmwqp_default(){
		$defalarr = array(
			'gmwqp_button_label' => 'ENQUIRY!',
			'gmwqp_form_title' => 'Product Enquiry',
			'gmwqp_form_required' => 'Please Enter',
			'gmwqp_display' => 'all',
			'gmwqp_enable_setting' => 'yes',
			'gmwqp_sp_bl' => 'after_add_cart',
			'gmwqp_label_show' => 'show_label',
			'gmwqp_email_sub' => 'Get Quote',
			'gmwqp_customer_email_subject' => 'Get Quote Customer',
			'gmwqp_cart_button_label' => 'ADD TO ENQUIRY CART!',
			'gmwqp_cart_display' => 'all',

			'gmwqp_usershow' => 'all',
			'gmwqp_email_body' => '<table><tr><th>Name</th><td>[name]</td></tr>
<tr><th>Email</th><td>[email]</td></tr>
<tr><th>Subject</th><td>[subject]</td></tr>
<tr><th>Mobile</th><td>[mobile]</td></tr>
<tr><th>Enquiry</th><td>[enquiry]</td></tr>
<tr><th>Product</th><td>[product]</td></tr></table>',
			'gmwqp_email_sucesemsg' => 'Your Message Successfully Sent!',
			'gmwqp_reci_email'=>get_bloginfo('admin_email'),
			'gmwqp_include_exclude' => 'all',
			
		);
		
		foreach ($defalarr as $keya => $valuea) {
			if (get_option( $keya )=='') {
				update_option( $keya, $valuea );
			}
			
		}

		$arrin = array(
			'gmwqp_field_customizer_field' => array(
					'name' => 'Name',
					'email' => 'Email',
					'subject' => 'Subject',
					'mobile' => 'Mobile Number',
					'enquiry' => 'Enquiry',
					/*'captcha' => 'Captcha',*/
				),
			'gmwqp_field_customizer_enble' => array(
					'name' => 'yes',
					'email' => 'yes',
					'subject' => 'yes',
					'mobile' => 'yes',
					'enquiry' => 'yes',
					/*'captcha' => 'yes',*/
				),
			'gmwqp_field_customizer_required' => array(
					'name' => 'yes',
					'email' => 'yes',
					'subject' => 'yes',
					'mobile' => 'yes',
					'enquiry' => 'yes',
					/*'captcha' => 'yes',*/
				),
			'gmwqp_field_customizer_type' => array(
					'name' => 'text',
					'email' => 'email',
					'subject' => 'text',
					'mobile' => 'text',
					'enquiry' => 'textarea',
					/*'captcha' => 'captcha',*/
				),
			'gmwqp_field_customizer_order' => array(
					'name' => '1',
					'email' => '2',
					'subject' => '3',
					'mobile' => '4',
					'enquiry' => '5',
					/*'captcha' => '6',*/
				),
			
		);
		foreach ($arrin as $keya => $valuea) {
			if (get_option( $keya )=='') {
				update_option( $keya, $valuea );
			}
			
		}
		
	}
}

?>