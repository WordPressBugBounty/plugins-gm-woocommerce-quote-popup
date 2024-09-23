<?php

$gmwqp_field_customizer_enble = get_option( 'gmwqp_field_customizer_enble' );
$gmwqp_field_customizer_required = get_option( 'gmwqp_field_customizer_required' );
$gmwqp_field_customizer_field = get_option( 'gmwqp_field_customizer_field' );
$gmwqp_field_customizer_type = get_option( 'gmwqp_field_customizer_type' );
$gmwqp_field_customizer_order = get_option( 'gmwqp_field_customizer_order' );
$gmwqp_field_customizer_option = get_option( 'gmwqp_field_customizer_option' );
$gmwqp_content_beforeform = get_option( 'gmwqp_content_beforeform' );
$gmwqp_content_afterform = get_option( 'gmwqp_content_afterform' );
/*echo "<pre>";
    print_r($gmwqp_field_customizer_enble);
    print_r($gmwqp_field_customizer_required);
    print_r($gmwqp_field_customizer_field);
    print_r($gmwqp_field_customizer_type);
    print_r($gmwqp_field_customizer_option);
echo "</pre>";*/
?>
<form method="post" action="options.php">
  
  
  <?php settings_fields( 'gmwqp_form_customizer_group' ); ?>


  <div class="fomradbuso">
    
    <a href="#" class="button button-primary addnew_customizer_form" style="margin-top:20px;margin-bottom: 20px;"><?php _e('Add New Field', 'gmwqp'); ?></a>
  </div>

  <table class="widefat">
    <tr valign="top">
      
      <th><?php _e('Enable / Disable', 'gmwqp'); ?></th>
      <th><?php _e('Required', 'gmwqp'); ?></th>
      <th><?php _e('Field Label', 'gmwqp'); ?></th>
      <th><?php _e('Field Order Number', 'gmwqp'); ?></th>
      <th><?php _e('Field Type', 'gmwqp'); ?></th>
      <th><?php _e('Field Options', 'gmwqp'); ?></th>
      <th><?php _e('Action', 'gmwqp'); ?></th>
    </tr>
   
    <?php
    $looparrm = $gmwqp_field_customizer_field;
    $x=1;
    if(!empty($looparrm)){
    foreach ($looparrm as $keylooparrm => $valuelooparrm) {
    ?>
    <tr valign="top">
      
      <td>
        <input class="regular-text" type="checkbox" <?php if($x==1 || $x==2){echo 'readonly';} ?>  <?php echo (isset($gmwqp_field_customizer_enble[$keylooparrm]) && $gmwqp_field_customizer_enble[$keylooparrm]=='yes')?'checked':'';?>  name="gmwqp_field_customizer_enble[<?php echo $keylooparrm;?>]" value="yes"   />
      </td>
      <td>
        <input class="regular-text" type="checkbox"  <?php if($x==1 || $x==2){echo 'readonly';} ?>   <?php echo (isset($gmwqp_field_customizer_required[$keylooparrm]) && $gmwqp_field_customizer_required[$keylooparrm]=='yes')?'checked':'';?> name="gmwqp_field_customizer_required[<?php echo $keylooparrm;?>]" value="yes"   />
      </td>
      <td>
        <input class="regular-text"  type="text" required name="gmwqp_field_customizer_field[<?php echo $keylooparrm;?>]" value="<?php echo esc_html($valuelooparrm);?>" />
        <input  type="hidden" name="gmwqp_field_customizer_type[<?php echo $keylooparrm;?>]" value="<?php echo $gmwqp_field_customizer_type[$keylooparrm];?>" />
      </td>
      <td>
        <input class="regular-text" style="width:60px;"  type="number" required name="gmwqp_field_customizer_order[<?php echo $keylooparrm;?>]" value="<?php echo $gmwqp_field_customizer_order[$keylooparrm];?>" />
      </td>
      <td>
        <?php echo $gmwqp_field_customizer_type[$keylooparrm];?>
      </td>
        <td>
          <?php
         
          $fromtype = array("select", "radio", "multiselect", "checkbox");
          if (in_array( $gmwqp_field_customizer_type[$keylooparrm], $fromtype)){
          ?>
          <textarea class="regular-text" style='max-width:150px;' placeholder="Option 1&#10;Option 2"   name="gmwqp_field_customizer_option[<?php echo $keylooparrm;?>]"><?php echo (isset($gmwqp_field_customizer_option[$keylooparrm]))?$gmwqp_field_customizer_option[$keylooparrm]:'';?></textarea>
          <?php
          }
          ?>
          
        </td>
        <td>
        <?php
        if($x>5){
        ?>
        <a href="<?php echo admin_url( 'admin.php?action=delete_keyif&key='.$keylooparrm);?>" class="button">Delete Field</a>
        <?php
        }
        ?>
      </td>
    </tr>
    <?php
    $x++;
    }
    }
    ?>
  </table>
  <table class="form-table">
    <tr valign="top">
        <th scope="row">
           <label><?php _e('Content Before Enquiry From', 'gmwqp'); ?></label>
        </th>
        <td>
           <?php
           wp_editor( $gmwqp_content_beforeform,'gmwqp_content_beforeform',array('textarea_rows' => 4));
           ?>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
           <label><?php _e('Content After Enquiry From', 'gmwqp'); ?></label>
        </th>
        <td>
           <?php
           wp_editor( $gmwqp_content_afterform,'gmwqp_content_afterform',array('textarea_rows' => 4));
           ?>
        </td>
    </tr>
  </table>
  <?php  submit_button(); ?>
</form>
<div class="showpopmain" style="display: none;">
  <div class="popupinner">
    <div class="postbox">
      <a class="closeicond" href="#"><span class="dashicons dashicons-no"></span></a>
      <div class="inside">
        <form action="#" method="post" id="wp_job_custom_form">
          
          <h3><?php _e('Custom Field Add', 'gmwqp'); ?></h3>
          <a href="https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/" target="_blank">Get Pro Version for this feature</a>
          <table class="form-table">
            
            <tr>
              <th scope="row"><label>Field Type</label></th>
              <td>
                <select name="gmwqp_field_customizer_type" class="field_type_gmqqp" >
                  <?php
                  foreach ($this->fieldset_arr_gm as $fieldset_arrk => $fieldset_arrv) {
                  echo '<option value="'.$fieldset_arrk.'" >'.$fieldset_arrv.'</option>';
                  }
                  ?>
                  
                </select>
              </td>
            </tr>
            <tr>
              <th scope="row"><label>Field Name</label></th>
              <td>
                <input type="text" required class="regular-text" name="gmwqp_field_customizer_field" disabled>
              </td>
            </tr>

            <tr class="gmqqp_option" style="display: none;">
              <th scope="row"><label>Field Option</label></th>
              <td>
                <textarea  class="regular-text textheighs" name="gmwqp_field_customizer_option" placeholder="Option 1&#10;Option 2" disabled></textarea>
                <p class="description">Per Line add one Option</p>
              </td>
            </tr>
            <tr>
              <th scope="row"><label>Field Required</label></th>
              <td>
                <input type="checkbox"  class="regular-text" name="field_required_gmwqp" value="yes" disabled>
              </td>
            </tr>
            <tr>
              <th scope="row"><label>Field Order Number</label></th>
              <td>
                <input type="number" required class="regular-text" name="gmwqp_field_customizer_order" disabled>
              </td>
            </tr>
          </table>
          
          <p class="submit">
            <input type="hidden" name="action" value="add_new_field_gmwqp">
            <input type="submit" name="submit"  class="button button-primary" value="Save" disabled>
          </p>
        </form>
      </div>
    </div>
    
  </div>
  <style type="text/css">
input[type="checkbox"][readonly] {
  pointer-events: none;
    opacity: 0.5;
}
  </style>