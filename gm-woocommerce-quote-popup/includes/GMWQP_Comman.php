<?php

class GMWQP_Comman {
	
	public function __construct () {

				add_action( 'init', array( $this, 'gmwqp_default' ) );
                add_action('woocommerce_single_product_summary', array($this, 'gmwqp_single'), 5);

                add_action( 'wp_ajax_gmqqp_enquiry', array( $this, 'gmqqp_enquiry' ));
				add_action( 'wp_ajax_nopriv_gmqqp_enquiry', array( $this, 'gmqqp_enquiry' ));

				add_action( 'woocommerce_init',  array($this, 'gmwqp_startSession') );
    }

    public function gmwqp_startSession(){
        if(isset(WC()->session)){
            if ( !is_admin() && !WC()->session->has_session() ) {
                WC()->session->set_customer_session_cookie( true );
            }
        }
    } 


	public function gmwqp_default(){
		global $gmwqp_arr;
		
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='download_enquiery_data') {
			if(in_array('administrator',  wp_get_current_user()->roles)){
				global $wpdb;
				$table_name = $wpdb->prefix . 'posts';
				$items = $wpdb->get_results("SELECT ID FROM $table_name where post_type='gmwqp_enquiry' ", ARRAY_A);
				$arraml = array();
				$arramllablel=array();
				$arramllablel['id']="ID";
				$gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];
				foreach ($gmwqp_field_customizer_field as $keymk => $valuemk) {
		             $arramllablel[$keymk]  = $valuemk;
				}
				$arramllablel['product_gmwqp']="Products";
				$arramllablel['date_insert']="Date";
				$arraml[]=$arramllablel;
				foreach ($items as $keya => $valuea) {
					$custom_arraml= array();
					$custom_arraml['id'] =  $valuea['ID'];
					
            		foreach ($gmwqp_field_customizer_field as $keymk => $valuemk) {
		                $valuekey = get_post_meta(  $valuea['ID'], $keymk,true );
		                $custom_arraml[$keymk]  = (is_array($valuekey))?implode(",",$valuekey):$valuekey;
		            }
		            $custom_arraml['product_gmwqp'] = get_post_meta(  $valuea['ID'], 'product_gmwqp',true );
            		$custom_arraml['date_insert'] = get_the_date( 'd-m-Y', $valuea['ID'] );
            		$arraml[]=$custom_arraml;
				}
				/*echo "<pre>";
				print_r($arraml);
				exit;*/
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="dataall.csv"');

				$fp = fopen('php://output', 'wb');
				foreach ( $arraml as $line ) {
				    //$val = explode(",", $line);
				    fputcsv($fp, $line);
				}
				fclose($fp);
				exit;
			}
			
		}
		
		if ($gmwqp_arr['gmwqp_remove_price'] == "yes") {
			 remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
		}
		if ($gmwqp_arr['gmwqp_hide_add_to_cart'] == "yes") {
			/*remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart',30);   */
   			
		}
             
		
	}

	public function gmwqp_single(){
		global $gmwqp_arr;
		if ($gmwqp_arr['gmwqp_remove_price'] == "yes") {
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		}
		
	}

	public function gmqqp_enquiry() {
		if (!isset($_POST['_ajax_nonce']) || !wp_verify_nonce($_POST['_ajax_nonce'], 'gmwqp_ajax_action')) {
	        wp_send_json_error(array("msg" => "error","returnhtml" => "nounce not verify"));
	        wp_die();
	    }
	    global $gmwqp_arr;
		$gmwqp_field_customizer_enble = $gmwqp_arr['gmwqp_field_customizer_enble'];
		$gmwqp_field_customizer_required = $gmwqp_arr['gmwqp_field_customizer_required'];
		$gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];
		$gmwqp_field_customizer_type = $gmwqp_arr['gmwqp_field_customizer_type'];
		$gmwqp_field_customizer_option = $gmwqp_arr['gmwqp_field_customizer_option'];
		$gmwqp_redirect_form_sub = $gmwqp_arr['gmwqp_redirect_form_sub'];
		$gmwqp_redirect_form_sub_page = $gmwqp_arr['gmwqp_redirect_form_sub_page'];
		$gmwqp_email_body = $gmwqp_arr['gmwqp_email_body'];
		$gmwqp_trasnlation_email_sucesemsg = $gmwqp_arr['gmwqp_trasnlation_email_sucesemsg'];
		$gmwqp_send_enquiry_email_cutomer = $gmwqp_arr['gmwqp_send_enquiry_email_cutomer'];
		$gmwqp_send_enquiry_replyto_customer_email = $gmwqp_arr['gmwqp_send_enquiry_replyto_customer_email'];
		$gmwqp_customer_email_subject = $gmwqp_arr['gmwqp_customer_email_subject'];
		$gmwqp_email_sub = $gmwqp_arr['gmwqp_email_sub'];
		$gmwqp_customer_email_subject = $gmwqp_arr['gmwqp_customer_email_subject'];
		$msg = '';
		foreach ($gmwqp_field_customizer_field as $keylooparrm => $valuelooparrm) {
			if($gmwqp_field_customizer_enble[$keylooparrm]=="yes"){
				if(empty($_REQUEST[$keylooparrm]) && $gmwqp_field_customizer_required[$keylooparrm]=="yes"){
					$msg .= '<div>'.__( esc_html($gmwqp_arr['gmwqp_trasnlation_form_required']).' '.esc_html($valuelooparrm).'!', 'gmwqp' ).'</div>';
				}
				/*if($gmwqp_field_customizer_type[$keylooparrm]=='captcha'){
					$session_val = WC()->session->get( 'gmqqp_answer');
					if ($session_val != $_REQUEST[$keylooparrm] ){
						$msg .= '<li>'.__( 'Please Enter Correct Captcha!', 'gmwqp' ).'</li>';
					}
				}*/
			}
		}
		if($gmwqp_arr['gmwqp_captcha']=='yes' && $gmwqp_arr['gmwqp_captcha_site_key']!=''  && $gmwqp_arr['gmwqp_captcha_secrete_key']!=''){
			$recaptcha_secret = $gmwqp_arr['gmwqp_captcha_secrete_key'];
		    $recaptcha_response = $_POST['g-recaptcha-response'];

		    $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");

		    $response_body = wp_remote_retrieve_body($response);
		    $result = json_decode($response_body, true);

		    if ($result['success']) {
		    }else {
		        $msg .= '<div>'.__( 'Captcha verification failed. Please try again.!', 'gmwqp' ).'</div>';
		    }
		}

		if($msg!=''){
			$returnarr = array(
				"msg" => "error",
				"returnhtml" => "<div class='gmwqpmsgc gmwerr'>".$msg."</div>"
			);
			echo json_encode($returnarr);
		}else{
			if($gmwqp_arr['gmwqp_reci_email']==''){
				$to = esc_html(get_option('admin_email'));
			}else{
				$to = esc_html($gmwqp_arr['gmwqp_reci_email']);
			}
			
			
			

			$post_id = wp_insert_post(array (
										   'post_type' => 'gmwqp_enquiry',
										   'post_title' => $_REQUEST['name'],
										   'post_status' => 'publish',
										));
			$body = $gmwqp_email_body;
			
			foreach ($gmwqp_field_customizer_field as $keylooparrm => $valuelooparrm) {
				if($gmwqp_field_customizer_enble[$keylooparrm]=="yes"){
					if($gmwqp_field_customizer_type[$keylooparrm]=='checkbox'){
						$body = str_ireplace("[".$keylooparrm."]",implode(",",$_REQUEST[$keylooparrm]),$body);
					}
					elseif($gmwqp_field_customizer_type[$keylooparrm]!='captcha'){
						$body = str_ireplace("[".$keylooparrm."]",$_REQUEST[$keylooparrm],$body);
					}
					update_post_meta( $post_id, $keylooparrm,$_REQUEST[$keylooparrm]);
				}
			}
			
			
			

			$gmwqp_email = sanitize_text_field($_REQUEST['email']);
			

			
			$prodnameformail= sanitize_text_field($_REQUEST['gmqqp_product']);
			$gmqqp_product_id= sanitize_text_field($_REQUEST['gmqqp_product_id']);
			update_post_meta( $post_id, 'product_gmwqp', sanitize_text_field($_REQUEST['gmqqp_product']) );	
			
			$body = str_ireplace("[product]",$prodnameformail,$body);
			$product_name_link = "<a href='".get_permalink($gmqqp_product_id)."'>".$prodnameformail."</a>";
			$body = str_ireplace("[product_name_link]",$product_name_link,$body);
			update_post_meta( $post_id, 'productid_gmwqp', sanitize_text_field($_REQUEST['gmqqp_product_id']) );
			$body = str_ireplace("[product_id]",$gmqqp_product_id,$body);
			$body = str_ireplace("[site_title]", get_bloginfo( 'name' ),$body);
			$body = str_ireplace("[site_url]",get_site_url(),$body);
			
			$headers = array(
			    'Content-Type: text/html; charset=UTF-8'
			);

			if ( $gmwqp_send_enquiry_replyto_customer_email=='yes'  ) { // your flag
			    $headers[] = 'Reply-To: '.$gmwqp_email.' <'.$gmwqp_email.'>';
			}

	        $gmwqp_email_sub = str_ireplace("[product]",$prodnameformail,$gmwqp_email_sub);
			wp_mail( $to, $gmwqp_email_sub, $body ,$headers);
			if($gmwqp_send_enquiry_email_cutomer=='yes' && $gmwqp_email!=''){
				wp_mail( $gmwqp_email, $gmwqp_customer_email_subject, $body ,$headers);
			}

			// Webhook Implementation
			if(isset($gmwqp_arr['gmwqp_webhook_enable']) && $gmwqp_arr['gmwqp_webhook_enable'] == 'yes' && isset($gmwqp_arr['gmwqp_webhook_url']) && !empty($gmwqp_arr['gmwqp_webhook_url'])){
				$webhook_url = esc_url($gmwqp_arr['gmwqp_webhook_url']);
				$webhook_data = $_REQUEST;
				// Add extra data if needed
				$webhook_data['product_name'] = $prodnameformail;
				$webhook_data['product_id'] = $gmqqp_product_id;
				$webhook_data['enquiry_date'] = current_time('mysql');

				wp_remote_post($webhook_url, array(
					'body' => $webhook_data,
					'blocking' => false, // Non-blocking to avoid delaying the response
				));
			}

			$returnarr = array(
				"msg" => "success",
				"returnhtml" => "<div class='gmwqpmsgc gmwsuc'><div>".esc_html( $gmwqp_trasnlation_email_sucesemsg)."</div></div>"
			);
			$returnarr['requested_data']=$_REQUEST;

			
			if($gmwqp_redirect_form_sub=='yes' && $gmwqp_redirect_form_sub_page!=''){
				$returnarr['redirect']="yes";
				$returnarr['redirect_to'] = $gmwqp_redirect_form_sub_page;
			}else{
				$returnarr['redirect']="no";
			}
			echo json_encode($returnarr);
		}
		exit;
	}

	

}
