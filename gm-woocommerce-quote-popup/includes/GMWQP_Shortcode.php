<?php

class GMWQP_Shortcode {
	
	public function __construct () {

		add_shortcode( 'gm_woo_enquiry_cart', array( $this, 'gm_woo_enquiry_cart' ) );
		add_action( 'wp_footer', array( $this, 'gmwqp_wp_footer' ) );

		// Cache-safe config: the popup fetches fresh settings + nonce at runtime,
		// so full-page caches (WP Rocket, LiteSpeed, W3TC, Cloudflare...) never serve a stale nonce.
		add_action( 'wp_ajax_gmwqp_config', array( $this, 'gmwqp_config_ajax' ) );
		add_action( 'wp_ajax_nopriv_gmwqp_config', array( $this, 'gmwqp_config_ajax' ) );
	}

	private function gmwqp_color( $key ) {
		global $gmwqp_arr;
		$v = isset( $gmwqp_arr[ $key ] ) ? trim( $gmwqp_arr[ $key ] ) : '';
		return ( 'theme' === $v || ! preg_match( '/^#[0-9a-fA-F]{3,8}$/', $v ) ) ? '' : $v;
	}

	public function gmwqp_config_ajax() {
		nocache_headers();
		wp_send_json( $this->gmwqp_get_frontend_config() );
	}


	public function gmwqp_wp_footer() {
		global $gmwqp_arr;
		?>
		<div id="gmwqp-frontend-root"></div>
		<?php
		if($gmwqp_arr['gmwqp_captcha']=='yes' && $gmwqp_arr['gmwqp_captcha_site_key']!=''  && $gmwqp_arr['gmwqp_captcha_secrete_key']!=''){
		?>
		<?php wp_enqueue_script( 'gmwqp-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true ); ?>
		<?php
		}
	}

	/**
	 * Builds the config object consumed by the React frontend app
	 * (src-frontend/frontend.js) — field definitions, translations,
	 * button colors, captcha, and redirect settings.
	 */
	public function gmwqp_get_frontend_config(){
		global $gmwqp_arr;

		$gmwqp_field_customizer_enble = $gmwqp_arr['gmwqp_field_customizer_enble'];
		$gmwqp_field_customizer_required = $gmwqp_arr['gmwqp_field_customizer_required'];
		$gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];
		$gmwqp_field_customizer_type = $gmwqp_arr['gmwqp_field_customizer_type'];
		$gmwqp_field_customizer_order = $gmwqp_arr['gmwqp_field_customizer_order'];
		$gmwqp_field_customizer_option = $gmwqp_arr['gmwqp_field_customizer_option'];

		asort($gmwqp_field_customizer_order);

		$fields = array();
		foreach ($gmwqp_field_customizer_order as $keylooparrm => $valuelooparrm) {
			if(isset($gmwqp_field_customizer_enble[$keylooparrm]) && $gmwqp_field_customizer_enble[$keylooparrm]=="yes"){
				$field = array(
					'name'     => $keylooparrm,
					'type'     => $gmwqp_field_customizer_type[$keylooparrm],
					'label'    => esc_html($gmwqp_field_customizer_field[$keylooparrm]),
					'required' => (isset($gmwqp_field_customizer_required[$keylooparrm]) && $gmwqp_field_customizer_required[$keylooparrm]=='yes'),
				);
				$fromtype = array("select", "radio", "multiselect", "checkbox");
				if (in_array($gmwqp_field_customizer_type[$keylooparrm], $fromtype)){
					$field['options'] = array_values(array_filter(array_map('trim', explode("\n", $gmwqp_field_customizer_option[$keylooparrm]))));
				}
				$fields[] = $field;
			}
		}

		// Old default hover colour (#eeeeee) with white text was unreadable; upgrade it on the fly.
		$hover_bg = strtolower( $gmwqp_arr['gmwqp_enquiry_btn_bg_hover_color'] );
		if ( '#eeeeee' === $hover_bg || '#eee' === $hover_bg ) {
			$gmwqp_arr['gmwqp_enquiry_btn_bg_hover_color'] = '#333333';
		}

		return array(
			'ajax_url'        => admin_url('admin-ajax.php'),
			'ajax_nonce'      => wp_create_nonce('gmwqp_ajax_action'),
			'form_title'      => esc_html($gmwqp_arr['gmwqp_trasnlation_form_title']),
			'submit_label'    => esc_html($gmwqp_arr['gmwqp_trasnlation_button_submit']),
			'required_label'  => esc_html($gmwqp_arr['gmwqp_trasnlation_form_required']),
			'success_message' => esc_html($gmwqp_arr['gmwqp_trasnlation_email_sucesemsg']),
			'label_show'      => $gmwqp_arr['gmwqp_label_show'],
			'fields'          => $fields,
			'content_before'  => wp_kses_post($gmwqp_arr['gmwqp_content_beforeform']),
			'content_after'   => wp_kses_post($gmwqp_arr['gmwqp_content_afterform']),
			'captcha'         => array(
				'enabled'  => ($gmwqp_arr['gmwqp_captcha']=='yes' && $gmwqp_arr['gmwqp_captcha_site_key']!='' && $gmwqp_arr['gmwqp_captcha_secrete_key']!=''),
				'site_key' => esc_html($gmwqp_arr['gmwqp_captcha_site_key']),
			),
			// 'theme' (set by the Reset button) means: no custom colour, keep the theme / built-in default.
			'colors'          => array(
				'bg'         => $this->gmwqp_color( 'gmwqp_enquiry_btn_bg_color' ),
				'bg_hover'   => $this->gmwqp_color( 'gmwqp_enquiry_btn_bg_hover_color' ),
				'text'       => $this->gmwqp_color( 'gmwqp_enquiry_btn_text_color' ),
				'text_hover' => $this->gmwqp_color( 'gmwqp_enquiry_btn_text_hover_color' ),
			),
		);
	}

	public function gmwqp_form_footer($product_title='',$is_tab=false,$prod_id=''){
		global $gmwqp_arr;
		$gmwqp_label_show = $gmwqp_arr['gmwqp_label_show'];
		$gmwqp_field_customizer_enble = $gmwqp_arr['gmwqp_field_customizer_enble'];
		$gmwqp_field_customizer_required = $gmwqp_arr['gmwqp_field_customizer_required'];
		$gmwqp_field_customizer_field = $gmwqp_arr['gmwqp_field_customizer_field'];
		$gmwqp_field_customizer_type = $gmwqp_arr['gmwqp_field_customizer_type'];
		$gmwqp_field_customizer_order = $gmwqp_arr['gmwqp_field_customizer_order'];
		$gmwqp_field_customizer_option = $gmwqp_arr['gmwqp_field_customizer_option'];
		$gmwqp_content_beforeform = $gmwqp_arr['gmwqp_content_beforeform'];
		$gmwqp_content_afterform = $gmwqp_arr['gmwqp_content_afterform'];
		//echo "<pre>";
		//print_r($gmwqp_field_customizer_order);
		//$fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");
		asort($gmwqp_field_customizer_order);
		//print_r($gmwqp_field_customizer_order);
		//echo "</pre>";
		$gmwqp_fields = array();
		foreach ($gmwqp_field_customizer_order as $keylooparrm => $valuelooparrm) {
			if($gmwqp_field_customizer_enble[$keylooparrm]=="yes"){
				$fieldssetup =array();
				$fieldssetup['position']="full";
				$fieldssetup['name']=$keylooparrm;
				$fieldssetup['type']=$gmwqp_field_customizer_type[$keylooparrm];
				$fieldssetup['label']=esc_html($gmwqp_field_customizer_field[$keylooparrm]);
				$fieldssetup['required']=(isset($gmwqp_field_customizer_required[$keylooparrm]))?$gmwqp_field_customizer_required[$keylooparrm]:'';
				$fromtype = array("select", "radio", "multiselect", "checkbox");
         		if (in_array( $gmwqp_field_customizer_type[$keylooparrm], $fromtype)){
         			$fieldssetup['options']=explode("\n",$gmwqp_field_customizer_option[$keylooparrm]);
         		}
				$gmwqp_fields[]=$fieldssetup;
			}
			
		}
		/*echo "<pre>";
		print_r($gmwqp_fields);
		echo "</pre>";*/
		?>
		<div class="gmwqp_toplevel">
		<?php
		if($gmwqp_content_beforeform!=''){
			?>
			<div class="gmwqp_beforeformcontent">
				<?php echo wp_kses_post($gmwqp_content_beforeform);?>
			</div>
			<?php
		}
		?>
			<form action="#" id="gmwqp_popup_op_form" class="gmwqp_popup_op_form" method="post" accept-charset="utf-8">
				<div class="gmwqp_popupcontant" id="gmwqp_popupcontant">
						<div class="gmwqp_inner_popupcontant">
							<?php
							foreach ($gmwqp_fields as $key_gmwqp_fields => $value_gmwqp_fields) {
								echo '<div class="gmwqp_loop gmwqp_'.$value_gmwqp_fields['position'].'">';
								
								$isplace ='';
								$isreq = '';
								if($gmwqp_label_show=='show_label'){
									
									echo '<label class="gmqqp_label">'.$value_gmwqp_fields['label'];
									if($value_gmwqp_fields['required']=="yes"){
										echo '<span>*</span>';
									}
									echo '</label>';
								}else{
									if($value_gmwqp_fields['required']=="yes"){
										$isreq = '*';
									}
									$isplace = $value_gmwqp_fields['label'];
								}
								echo '<div class="gmwqp_inner_field">';
								if (in_array($value_gmwqp_fields['type'], array("text","email"))){
									
									echo '<input class="gmqqp_input" placeholder="'.$isplace.' '.$isreq.'" type="'.$value_gmwqp_fields['type'].'" name="'.$value_gmwqp_fields['name'].'" value="">';
									
								}
								
								if (in_array($value_gmwqp_fields['type'], array("textarea"))){
									echo '<textarea class="gmqqp_input" placeholder="'.$isplace.' '.$isreq.'" name="'.$value_gmwqp_fields['name'].'"></textarea>';
								}
								if($value_gmwqp_fields['type']=='select'){
									echo '<select class="gmqqp_input" name="'.$value_gmwqp_fields['name'].'">';
									foreach ($value_gmwqp_fields['options'] as $keyoptions => $valueoptions) {
										echo '<option>'.$valueoptions.'</option>';
									}
									echo '</select>';
								}
								if($value_gmwqp_fields['type']=='radio'){
									foreach ($value_gmwqp_fields['options'] as $keyoptions => $valueoptions) {
										echo '<input type="radio" name="'.$value_gmwqp_fields['name'].'" value="'.$valueoptions.'"/>';
										echo '<label>'.$valueoptions.'</label>';
									}
								}
								if($value_gmwqp_fields['type']=='checkbox'){
									foreach ($value_gmwqp_fields['options'] as $keyoptions => $valueoptions) {
										echo '<input type="checkbox" name="'.$value_gmwqp_fields['name'].'[]" value="'.$valueoptions.'"/>';
										echo '<label>'.$valueoptions.'</label>';
									}
								}
								echo '</div>';
								echo '</div>';
							}
							?>
							
							<input type="hidden" name="action" class="gmqqp_enquiry" value="gmqqp_enquiry" />
							<input type="hidden" name="gmqqp_product" class="gmqqp_product_vl" value="<?php echo esc_attr( $product_title ); ?>" />
							<input type="hidden" name="gmqqp_product_id" class="gmqqp_product_id" value="<?php echo esc_attr( $prod_id ); ?>" />
						</div>
						<?php 
						if($gmwqp_arr['gmwqp_captcha']=='yes' && $gmwqp_arr['gmwqp_captcha_site_key']!=''  && $gmwqp_arr['gmwqp_captcha_secrete_key']!='' ){
							?>
							<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $gmwqp_arr['gmwqp_captcha_site_key'] ); ?>"></div>
							<?php
						}
						?>
					</div>
					
					
					<div class="gmqqp_submit">
						<button type="submit" class="gmqqp_submit_btn wp-block-button__link wp-element-button"><?php echo esc_html($gmwqp_arr['gmwqp_trasnlation_button_submit']); ?></button>
					</div>
			</form>
			<?php
			if($gmwqp_content_afterform!=''){
				?>
				<div class="gmwqp_afterformcontent">
					<?php echo wp_kses_post($gmwqp_content_afterform);?>
				</div>
				<?php
			}
			?>
		</div>
		<?php 
		if($gmwqp_arr['gmwqp_captcha']=='yes' && $gmwqp_arr['gmwqp_captcha_site_key']!=''  && $gmwqp_arr['gmwqp_captcha_secrete_key']!=''){
		?>
		<?php wp_enqueue_script( 'gmwqp-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true ); ?>
		<?php
		}
		?>
		<?php
	}

	
}

?>