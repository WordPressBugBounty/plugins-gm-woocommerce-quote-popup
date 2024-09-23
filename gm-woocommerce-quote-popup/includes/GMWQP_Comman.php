<?php

class GMWQP_Comman {
	
	public function __construct () {

				

				add_action( 'init', array( $this, 'gmwqp_default' ) );
                add_action('woocommerce_single_product_summary', array($this, 'gmwqp_single'), 5);

                add_action( 'wp_ajax_gmqqp_enquiry', array( $this, 'gmqqp_enquiry' ));
				add_action( 'wp_ajax_nopriv_gmqqp_enquiry', array( $this, 'gmqqp_enquiry' ));

				add_action( 'wp_ajax_gmqqp_add_tocart_enquiry', array( $this, 'gmqqp_add_tocart_enquiry' ));
				add_action( 'wp_ajax_nopriv_gmqqp_add_tocart_enquiry', array( $this, 'gmqqp_add_tocart_enquiry' ));

				add_action( 'wp_ajax_gmqqp_remove_cart', array( $this, 'gmqqp_remove_cart' ));
				add_action( 'wp_ajax_nopriv_gmqqp_remove_cart', array( $this, 'gmqqp_remove_cart' ));

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

		
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='download_enquiery_data') {
			if(in_array('administrator',  wp_get_current_user()->roles)){
				global $wpdb;
				$table_name = $wpdb->prefix . 'posts';
				$items = $wpdb->get_results("SELECT ID FROM $table_name where post_type='gmwqp_enquiry' ", ARRAY_A);
				$arraml = array();
				$arramllablel=array();
				$arramllablel['id']="ID";
				$gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
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
		
		if (get_option( 'gmwqp_remove_price' ) == "yes") {
			 remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
		}
		if (get_option( 'gmwqp_hide_add_to_cart' ) == "yes") {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart',30);   
   			
		}
             
		
	}

	public function gmwqp_single(){
		if (get_option( 'gmwqp_remove_price' ) == "yes") {
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
		}
		
	}

	public function gmqqp_enquiry() {

		$gmwqp_field_customizer_enble = get_option( 'gmwqp_field_customizer_enble' );
		$gmwqp_field_customizer_required = get_option( 'gmwqp_field_customizer_required' );
		$gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
		$gmwqp_field_customizer_type = get_option( 'gmwqp_field_customizer_type' );
		$gmwqp_field_customizer_option = get_option( 'gmwqp_field_customizer_option' );
		$gmwqp_redirect_form_sub = get_option( 'gmwqp_redirect_form_sub' );
		$gmwqp_redirect_form_sub_page = get_option( 'gmwqp_redirect_form_sub_page' );
		$gmwqp_email_body = get_option( 'gmwqp_email_body' );
		$gmwqp_email_sucesemsg = get_option( 'gmwqp_email_sucesemsg' );
		$gmwqp_send_enquiry_email_cutomer = get_option( 'gmwqp_send_enquiry_email_cutomer' );
		$gmwqp_customer_email_subject = get_option( 'gmwqp_customer_email_subject' );
		$gmwqp_email_sub = get_option('gmwqp_email_sub');
		$gmwqp_customer_email_subject = get_option('gmwqp_customer_email_subject');
		$msg = '';
		foreach ($gmwqp_field_customizer_field as $keylooparrm => $valuelooparrm) {
			if($gmwqp_field_customizer_enble[$keylooparrm]=="yes"){
				if(empty($_REQUEST[$keylooparrm]) && $gmwqp_field_customizer_required[$keylooparrm]=="yes"){
					$msg .= '<div>'.__( esc_html(get_option('gmwqp_form_required')).' '.esc_html($valuelooparrm).'!', 'gmwqp' ).'</div>';
				}
				/*if($gmwqp_field_customizer_type[$keylooparrm]=='captcha'){
					$session_val = WC()->session->get( 'gmqqp_answer');
					if ($session_val != $_REQUEST[$keylooparrm] ){
						$msg .= '<li>'.__( 'Please Enter Correct Captcha!', 'gmwqp' ).'</li>';
					}
				}*/
			}
		}
		

		if($msg!=''){
			$returnarr = array(
				"msg" => "error",
				"returnhtml" => "<div class='gmwqpmsgc gmwerr'>".$msg."</div>"
			);
			echo json_encode($returnarr);
		}else{
			if(get_option('gmwqp_reci_email')==''){
				$to = esc_html(get_option( 'admin_email' ));
			}else{
				$to = esc_html(get_option('gmwqp_reci_email'));
			}
			
			$gmwqp_added_cart = WC()->session->get( 'gmwqp_added_cart' );
			$namearr = array();
			foreach ($gmwqp_added_cart as $gmwqpkey => $gmwqpvalue) {
				$product = wc_get_product( $gmwqpvalue);
				
				$namearr[]=$product->get_name();
			}

			$post_id = wp_insert_post(array (
										   'post_type' => 'gmwqp_enquiry',
										   'post_title' => sanitize_text_field($_REQUEST['name']),
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
					update_post_meta( $post_id, $keylooparrm,sanitize_text_field($_REQUEST[$keylooparrm]));
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
			//$headers = "Reply-To: ".$gmwqp_name." <".$gmwqp_email.">";
	        $headers = "Content-Type: text/html; charset=UTF-8"; 
	        $gmwqp_email_sub = str_ireplace("[product]",$prodnameformail,$gmwqp_email_sub);
			wp_mail( $to, $gmwqp_email_sub, $body ,$headers);
			if($gmwqp_send_enquiry_email_cutomer=='yes' && $gmwqp_email!=''){
				wp_mail( $gmwqp_email, $gmwqp_customer_email_subject, $body ,$headers);
			}
			$returnarr = array(
				"msg" => "success",
				"returnhtml" => "<div class='gmwqpmsgc gmwsuc'><div>".esc_html( $gmwqp_email_sucesemsg)."</div></div>"
			);
			WC()->session->set( 'gmwqp_added_cart', array() );
			$returnarr['requested_data']=$_REQUEST;
			if($gmwqp_redirect_form_sub=='yes'){
				$returnarr['redirect']="yes";
				$returnarr['redirect_to'] = get_permalink($gmwqp_redirect_form_sub_page);
			}else{
				$returnarr['redirect']="no";
			}
			echo json_encode($returnarr);
		}
		exit;
	}

	public function gmqqp_remove_cart() {
		$array = WC()->session->get( 'gmwqp_added_cart' );
		$products = array_diff($array, array($_REQUEST['product_id']));
		WC()->session->set( 'gmwqp_added_cart', $products );
		exit;
	}

	public function gmqqp_add_tocart_enquiry() {
		$gmwqp_cart_page = get_option( 'gmwqp_cart_page' );
		$add_id = $_REQUEST['add_id'];
		$gmwqp_added_cart = WC()->session->get( 'gmwqp_added_cart' );
		$gmwqp_added_cart[]=$add_id; 
		$gmwqp_added_cart=array_unique($gmwqp_added_cart);
		WC()->session->set( 'gmwqp_added_cart', $gmwqp_added_cart );

		$returnarr = array(
				"msg" => "success",
				"returnhtml" => "<a href='".get_permalink($gmwqp_cart_page)."' class='viewcaren button'>View Cart Enquiry</a>"
			);
			echo json_encode($returnarr);
		exit;
	}

}

?>