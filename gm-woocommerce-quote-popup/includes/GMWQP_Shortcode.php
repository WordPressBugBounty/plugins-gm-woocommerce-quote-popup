<?php

class GMWQP_Shortcode {
	
	public function __construct () {

		add_shortcode( 'gm_woo_enquiry_cart', array( $this, 'gm_woo_enquiry_cart' ) );
		add_action( 'wp_footer', array( $this, 'gmwqp_wp_footer' ) );
	}


	public function gmwqp_wp_footer() {
		
		?>
		<div  class="gmwqp_popup_op">
			<div class="gmwqp_inner_popup_op">
				<div class="gmwqp_inner_popup_op_mores">
					<a href="#" class="gmwqp_close b-close"><img src="<?php echo esc_url( GMWQP_PLUGIN_URL.'assents/img/close_btn.png' );?>" /></a>
					
					
					<h3 class="gmwqp_popup_title"><?php echo esc_html(get_option('gmwqp_form_title')); ?></h3>
					<?php
					$this->gmwqp_form_footer();
					?>
				</div>
			</div>
		</div>
		<style type="text/css">
			body .gmwqp_inq_addtocart:hover, body .gmwqp_inq:hover, body .viewcaren:hover, body .gmqqp_submit_btn:hover{
				text-decoration: none !important;
			}
			<?php
			if(get_option('gmwqp_enquiry_btn_bg_color')!=''){
				?>
				.gmwqp_inq_addtocart, .gmwqp_inq , .viewcaren, .gmqqp_submit_btn{
					background-color:<?php echo esc_html(get_option('gmwqp_enquiry_btn_bg_color'));?> !important;
				}
				<?php
			}
			if(get_option('gmwqp_enquiry_btn_bg_hover_color')!=''){
				?>
				.gmwqp_inq_addtocart:hover, .gmwqp_inq:hover , .viewcaren:hover, .gmqqp_submit_btn:hover{
					background-color:<?php echo esc_html(get_option('gmwqp_enquiry_btn_bg_hover_color'));?> !important;
				}
				<?php
			}
			if(get_option('gmwqp_enquiry_btn_text_color')!=''){
				?>
				.gmwqp_inq_addtocart, .gmwqp_inq, .viewcaren, .gmqqp_submit_btn{
					color:<?php echo esc_html(get_option('gmwqp_enquiry_btn_text_color'));?> !important;
				}
				<?php
			}
			if(get_option('gmwqp_enquiry_btn_text_hover_color')!=''){
				?>
				.gmwqp_inq_addtocart:hover, .gmwqp_inq:hover, .viewcaren:hover, .gmqqp_submit_btn:hover{
					color:<?php echo esc_html(get_option('gmwqp_enquiry_btn_text_hover_color'));?> !important;
				}
				<?php
			}
			
			?>
		</style>
		<?php
	}

	public function gmwqp_form_footer($product_title='',$is_tab=false,$prod_id=''){
		$gmwqp_label_show = get_option('gmwqp_label_show');
		$gmwqp_field_customizer_enble = get_option( 'gmwqp_field_customizer_enble' );
		$gmwqp_field_customizer_required = get_option( 'gmwqp_field_customizer_required' );
		$gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
		$gmwqp_field_customizer_type = get_option( 'gmwqp_field_customizer_type' );
		$gmwqp_field_customizer_order = get_option( 'gmwqp_field_customizer_order' );
		$gmwqp_field_customizer_option = get_option( 'gmwqp_field_customizer_option' );
		$gmwqp_content_beforeform = get_option( 'gmwqp_content_beforeform' );
		$gmwqp_content_afterform = get_option( 'gmwqp_content_afterform' );
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
								if (in_array($value_gmwqp_fields['type'], array("captcha"))){
									/*$digit1 = mt_rand(1,20);
								    $digit2 = mt_rand(1,20);
						            $math = "$digit1 + $digit2";
						            $gmqqp_answer = $digit1 + $digit2;
						            if(isset(WC()->session)){
						            	WC()->session->set( 'gmqqp_answer', $gmqqp_answer );
						            }
								   

								    echo '<div class="gmqqp_captchadiv">';
								    echo "<label>What's <strong>".$math."</strong> = </label>";
									echo '<input class="gmqqp_input" autocomplete="off" placeholder="'.$isplace.' '.$isreq.'" type="text" name="'.$value_gmwqp_fields['name'].'" value="">';
									echo '</div>';*/
									
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
							<input type="hidden" name="gmqqp_product" class="gmqqp_product_vl" value="<?php echo $product_title; ?>" />
							<input type="hidden" name="gmqqp_product_id" class="gmqqp_product_id" value="<?php echo $prod_id; ?>" />
						</div>
					</div>
					<div class="gmqqp_submit">
						<button type="submit" class="gmqqp_submit_btn button wp-block-button__link wp-element-button"><?php _e('Send!', 'gmwqp'); ?></button>
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
	}

	
}

?>