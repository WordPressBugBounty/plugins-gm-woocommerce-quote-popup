<?php
$gmwqp_email_body = get_option( 'gmwqp_email_body' );
$gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
$gmwqp_send_enquiry_email_cutomer = get_option( 'gmwqp_send_enquiry_email_cutomer' );
?>
<form method="post" action="options.php">
  
  
  <?php settings_fields( 'gmwqp_email_customizer_group' ); ?>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">
        <label><?php _e("Recipient's Email", 'gmwqp'); ?></label>
      </th>
      <td>
        <input class="regular-text" type="text" name="gmwqp_reci_email" value="<?php echo esc_html(get_option('gmwqp_reci_email')); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label><?php _e("Email subject", 'gmwqp'); ?></label>
      </th>
      <td>
        <input class="regular-text" type="text" name="gmwqp_email_sub" value="<?php echo esc_html(get_option('gmwqp_email_sub')); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label for="gmwqp_send_enquiry_email_cutomer"><?php _e('Send Enquiry Email to Customer As Well', 'gmwqp'); ?></label>
      </th>
      <td>
        <input class="regular-text" type="checkbox" id="gmwqp_send_enquiry_email_cutomer" <?php echo (($gmwqp_send_enquiry_email_cutomer=='yes')?'checked':'') ; ?> name="gmwqp_send_enquiry_email_cutomer" value="yes" disabled/>
        <a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label><?php _e("Customer Email Subject", 'gmwqp'); ?></label>
      </th>
      <td>
        <input class="regular-text" type="text" name="gmwqp_customer_email_subject" value="<?php echo esc_html(get_option('gmwqp_customer_email_subject')); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label><?php _e('Email Body', 'gmwqp'); ?></label>
      </th>
      <td>
        
        <p style="word-break: break-word;">
          <?php
          foreach ($gmwqp_field_customizer_field as $keygmwqp_field_customizer_field => $valuegmwqp_field_customizer_field) {
          //if($keygmwqp_field_customizer_field!='captcha'){
          echo '<code>['.$keygmwqp_field_customizer_field.']</code>&nbsp;&nbsp;';
          //}
          
          }
          ?>
          <code>[product]</code>
          <code>[product_id]</code>
          <code>[product_name_link]</code>
          <code>[site_url]</code>
          <code>[site_title]</code>
        </p>
        <?php
        $settings  = array(
        'media_buttons' => false ,
        'textarea_rows' => 15,
        'quicktags'     => true
        );
        wp_editor( $gmwqp_email_body,'gmwqp_email_body',$settings);?>
        
      </td>
    </tr>
  </table>
  <?php  submit_button(); ?>
</form>
