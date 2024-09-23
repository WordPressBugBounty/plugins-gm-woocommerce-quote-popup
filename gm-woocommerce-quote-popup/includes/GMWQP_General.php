<?php
$gmwqp_usershow = get_option( 'gmwqp_usershow' );
$gmwqp_hide_add_to_cart = get_option( 'gmwqp_hide_add_to_cart' );
$gmwqp_label_show = get_option( 'gmwqp_label_show' );
$gmwqp_remove_price = get_option( 'gmwqp_remove_price' );
$gmwqp_show_product_outofstock = get_option( 'gmwqp_show_product_outofstock' );
$gmwqp_enquiry_btn_bg_color = esc_html(get_option( 'gmwqp_enquiry_btn_bg_color' ));
$gmwqp_enquiry_btn_text_color = esc_html(get_option( 'gmwqp_enquiry_btn_text_color' ));
$gmwqp_enquiry_btn_bg_hover_color = esc_html(get_option( 'gmwqp_enquiry_btn_bg_hover_color' ));
$gmwqp_enquiry_btn_text_hover_color = esc_html(get_option( 'gmwqp_enquiry_btn_text_hover_color' ));
$gmwqp_redirect_form_sub = get_option( 'gmwqp_redirect_form_sub' );
$gmwqp_redirect_form_sub_page = get_option( 'gmwqp_redirect_form_sub_page' );
$gmwqp_disable_cart_checkout_page = get_option( 'gmwqp_disable_cart_checkout_page' );
$gmwqp_redirect_disable_cart_checkout_page = get_option( 'gmwqp_redirect_disable_cart_checkout_page' );

?>
<form method="post" action="options.php">
	<?php settings_fields( 'gmwqp_general_options_group' ); ?>
  <div class="metabox-holder">
    <div class="postbox">
      <div class="postbox-header">
        <h3 class="hndle">Setting</h3>
      </div>
      <div class="inside">
        <table class="form-table">
          <tr>
            <th scope="row"><label><?php _e('Users Show', 'gmwqp'); ?></label></th>
            <td>
              <input type="radio" name="gmwqp_usershow" <?php echo ($gmwqp_usershow=='all')?'checked':''; ?> value="all"><?php _e('All Users', 'gmwqp'); ?>
              <input type="radio" name="gmwqp_usershow" <?php echo ($gmwqp_usershow=='logged_user')?'checked':''; ?> value="logged_user"><?php _e('Only Logged in Users', 'gmwqp'); ?>
              <input type="radio" name="gmwqp_usershow" <?php echo ($gmwqp_usershow=='logged_out')?'checked':''; ?> value="logged_out"><?php _e('Only Logged out Users', 'gmwqp'); ?>
            </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                 <label for="gmwqp_show_product_outofstock"><?php _e('Show Enquiry Button When Product is out of stock', 'gmwqp'); ?></label>
              </th>
              <td> 
                 <input class="regular-text" type="checkbox" id="gmwqp_show_product_outofstock" <?php echo (($gmwqp_show_product_outofstock=='yes')?'checked':'') ; ?> name="gmwqp_show_product_outofstock" value="yes" disabled/>
                 <a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                 <label for="gmwqp_remove_price"><?php _e('Remove Price From Product', 'gmwqp'); ?></label>
              </th>
              <td> 
                 <input class="regular-text" type="checkbox" id="gmwqp_remove_price" <?php echo (($gmwqp_remove_price=='yes')?'checked':'') ; ?> name="gmwqp_remove_price" value="yes" disabled/>
                 <a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
              </td>
          </tr>
          <tr valign="top">
              <th scope="row">
                 <label for="gmwqp_hide_add_to_cart"><?php _e('Hide Add to Cart Button', 'gmwqp'); ?></label>
              </th>
              <td> 
                 <input class="regular-text" type="checkbox" id="gmwqp_hide_add_to_cart" <?php echo (($gmwqp_hide_add_to_cart=='yes')?'checked':'') ; ?> name="gmwqp_hide_add_to_cart" value="yes" disabled/>
                 <a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
              </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="metabox-holder">
    <div class="postbox">
      <div class="postbox-header">
        <h3 class="hndle">Form Setting</h3>
      </div>
      <div class="inside">
        <table class="form-table">
          <tr>
          <th scope="row"><label><?php _e('Label / Placeholder Display', 'gmwqp'); ?></label></th>
          <td>
            <input type="radio" name="gmwqp_label_show" <?php echo ($gmwqp_label_show=='show_label')?'checked':''; ?> value="show_label"><?php _e('Show Label', 'gmwqp'); ?>
            <input type="radio" name="gmwqp_label_show" <?php echo ($gmwqp_label_show=='show_placeholder')?'checked':''; ?> value="show_placeholder"><?php _e('Show Placeholder', 'gmwqp'); ?><br>
            <strong>In popup show label or placeholder that define</strong>
          </td>
        </tr>
        <tr>
              <th scope="row"><label><?php _e('Button Background Color', 'gmwqp'); ?></label></th>
              <td>
                 <input type="text" class="gmwqp-color-field" name="gmwqp_enquiry_btn_bg_color" value="<?php echo $gmwqp_enquiry_btn_bg_color; ?>">
              </td>
          </tr>
          <tr>
              <th scope="row"><label><?php _e('Button Text Color', 'gmwqp'); ?></label></th>
              <td>
                 <input type="text" class="gmwqp-color-field" name="gmwqp_enquiry_btn_text_color" value="<?php echo $gmwqp_enquiry_btn_text_color; ?>">
              </td>
          </tr>
          <tr>
              <th scope="row"><label><?php _e('Button Background Hover Color', 'gmwqp'); ?></label></th>
              <td>
                 <input type="text" class="gmwqp-color-field" name="gmwqp_enquiry_btn_bg_hover_color" value="<?php echo $gmwqp_enquiry_btn_bg_hover_color; ?>">
              </td>
          </tr>
          <tr>
              <th scope="row"><label><?php _e('Button Text Hover Color', 'gmwqp'); ?></label></th>
              <td>
                 <input type="text" class="gmwqp-color-field" name="gmwqp_enquiry_btn_text_hover_color" value="<?php echo $gmwqp_enquiry_btn_text_hover_color; ?>">
              </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="metabox-holder">
    <div class="postbox">
      <div class="postbox-header">
        <h3 class="hndle">Redirection Setting</h3>
      </div>
      <div class="inside">
        <table class="form-table">
          <tr>
            <th scope="row"><label><?php _e('Redirect after Enquiry form Submission', 'gmwqp'); ?></label></th>
            <td>
              <input class="regular-text sourfocheck" target="gmwqp_redirect_form_sub_page" type="checkbox" id="gmwqp_redirect_form_sub" <?php echo (($gmwqp_redirect_form_sub=='yes')?'checked':'') ; ?> name="gmwqp_redirect_form_sub" value="yes" />
             
            </td>
          </tr>


    <tr style='<?php echo (($gmwqp_redirect_form_sub!='yes')?'display:none;':'') ; ?>' class="gmwqp_redirect_form_sub_page">
          <th scope="row"><label><?php _e('Redirect Page', 'gmwqp'); ?></label></th>
          <td>
            <?php
      $list_cart_page = get_posts( array(
                          'posts_per_page' => -1,
                          'post_type'  => 'page',
                      ) );
            ?>
            <select name="gmwqp_redirect_form_sub_page">
             <?php
              foreach ($list_cart_page as $keylist_cart_page => $valuelist_cart_page) {
                echo '<option  '.(($gmwqp_redirect_form_sub_page==$valuelist_cart_page->ID)?'selected':'').' value="'.$valuelist_cart_page->ID.'">'.$valuelist_cart_page->post_title.'</option>';
              }
              ?>
            </select>
            <p class="description">
              <?php _e('Select page where user will be redirected for form submission.', 'gmwqp'); ?>
            </p>
          </td>
        </tr>



          <tr>
            <th scope="row"><label><?php _e('Disable Woocommerce Cart and Checkout Page?', 'gmwqp'); ?></label></th>
            <td>
              <input class="regular-text sourfocheck"  target="gmwqp_redirect_disable_cart_checkout_page"  type="checkbox" id="gmwqp_disable_cart_checkout_page" <?php echo (($gmwqp_disable_cart_checkout_page=='yes')?'checked':'') ; ?> name="gmwqp_disable_cart_checkout_page" value="yes" />
             
            </td>
          </tr>


          <tr style='<?php echo (($gmwqp_disable_cart_checkout_page!='yes')?'display:none;':'') ; ?>'  class="gmwqp_redirect_disable_cart_checkout_page">
              <th scope="row"><label><?php _e('Redirect Page', 'gmwqp'); ?></label></th>
              <td>
                <?php
                $list_cart_page = get_posts( array(
                              'posts_per_page' => -1,
                              'post_type'  => 'page',
                          ) );
                ?>
                <select name="gmwqp_redirect_disable_cart_checkout_page">
                 <?php
                  foreach ($list_cart_page as $keylist_cart_page => $valuelist_cart_page) {
                    echo '<option  '.(($gmwqp_redirect_disable_cart_checkout_page==$valuelist_cart_page->ID)?'selected':'').' value="'.$valuelist_cart_page->ID.'">'.$valuelist_cart_page->post_title.'</option>';
                  }
                  ?>
                </select>
                <p class="description">
                  <?php _e('Select page where user will be redirected for disable cart page.', 'gmwqp'); ?>
                </p>
              </td>
            </tr>
            
          </tr>
        </table>
      </div>
    </div>
  </div>
	
	<?php  submit_button(); ?>
</form>