<?php

?>
<form method="post" action="options.php">
	<?php settings_fields( 'gmwqp_translate_options_group' ); ?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">
				<label>ENQUIRY!</label>
			</th>
			<td>
				<input class="regular-text" type="text" name="gmwqp_button_label" value="<?php echo esc_html(get_option('gmwqp_button_label')); ?>" disabled />
				<a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label>Product Enquiry</label>
			</th>
			<td>
				<input class="regular-text" type="text" name="gmwqp_form_title" value="<?php echo esc_html(get_option('gmwqp_form_title')); ?>" disabled/>
				<a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label>Please Enter</label>
			</th>
			<td>
				<input class="regular-text" type="text" name="gmwqp_form_required" value="<?php echo esc_html(get_option('gmwqp_form_required')); ?>" disabled/>
				<a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label>Your Message Successfully Sent!</label>
			</th>
			<td>
				<input class="regular-text" type="text" name="gmwqp_email_sucesemsg" value="<?php echo esc_html(get_option('gmwqp_email_sucesemsg')); ?>" disabled/>
				<a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
			</td>
		</tr>
	</table>
	<?php  submit_button(); ?>
</form>