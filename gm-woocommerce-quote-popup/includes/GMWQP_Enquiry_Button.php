<?php
$gmwqp_display = get_option( 'gmwqp_display' );
$gmwqp_sp_bl = get_option( 'gmwqp_sp_bl' );
$gmwqp_enable_setting = get_option( 'gmwqp_enable_setting' );



?>
<form method="post" action="options.php">
	<?php settings_fields( 'gmwqp_options_group' ); ?>
	<table class="form-table">
		<tr valign="top">
            <th scope="row">
               <label for="gmwqp_enable_setting"><?php _e('Enable', 'gmwqp'); ?></label>
            </th>
            <td>
               <input class="regular-text" type="checkbox" id="gmwqp_enable_setting" <?php echo (($gmwqp_enable_setting=='yes')?'checked':'') ; ?> name="gmwqp_enable_setting" value="yes" />
            </td>
         </tr>
		
		
		
		<tr>
			<th scope="row"><label><?php _e('Display Page', 'gmwqp'); ?></label></th>
			<td>
				<input type="radio" name="gmwqp_display" <?php echo ($gmwqp_display=='all')?'checked':''; ?> value="all"><?php _e('Shop and Single Product Page', 'gmwqp'); ?>
				<input type="radio" name="gmwqp_display" <?php echo ($gmwqp_display=='single')?'checked':''; ?> value="single"><?php _e('Single Product Page', 'gmwqp'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label><?php _e('Single Product Button Location', 'gmwqp'); ?></label></th>
			<td>
				<input type="radio" name="gmwqp_sp_bl" <?php echo ($gmwqp_sp_bl=='after_add_cart')?'checked':''; ?> value="after_add_cart"><?php _e('After Add to Cart Button', 'gmwqp'); ?><br/>
				<input type="radio" name="gmwqp_sp_bl" <?php echo ($gmwqp_sp_bl=='tabshow')?'checked':''; ?> value="tabshow"><?php _e('Enquiry in Tab', 'gmwqp'); ?><br/>
				<input type="radio" name="gmwqp_sp_bl" <?php echo ($gmwqp_sp_bl=='custom')?'checked':''; ?> value="custom"><?php _e('Custom Location', 'gmwqp'); ?><br>
				<strong><em>Note : Custom Location for you need to use shortcode</em></strong>
			</td>
		</tr>
		<tr>
			<th scope="row"><label>ShortCode</label></th>
			<td>
				<code>[gmwqp_enquiry_single_product]</code>  or <code>[gmwqp_enquiry_single_product id='{product_id}']</code>
			</td>
		</tr>
		
		
		
	</table>
	<?php  submit_button(); ?>
</form>