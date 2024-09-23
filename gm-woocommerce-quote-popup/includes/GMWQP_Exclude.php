<?php
$gmwqp_include_exclude = get_option( 'gmwqp_include_exclude' );
$gmwqp_include_category = get_option( 'gmwqp_include_category',array());
if(empty($gmwqp_include_category)){
  $gmwqp_include_category = array();
}
$gmwqp_exclude_category = get_option( 'gmwqp_exclude_category',array());
if(empty($gmwqp_exclude_category)){
  $gmwqp_exclude_category = array();
}

?>
<form method="post" action="options.php">
	<?php settings_fields( 'gmwqp_exclude_options_group' ); ?>
	<table class="form-table">
    <tr>
        <th scope="row"><label><?php _e('Include Exclude Base On Category', 'gmwqp'); ?></label></th>
        <td>
          <input type="radio" name="gmwqp_include_exclude" <?php echo ($gmwqp_include_exclude=='all')?'checked':''; ?> value="all" target="" class="includexdfocheck">All
          <input type="radio" name="gmwqp_include_exclude" <?php echo ($gmwqp_include_exclude=='include')?'checked':''; ?> value="include" target="gmwqp_include_div" class="includexdfocheck">Include
          <input type="radio" name="gmwqp_include_exclude" <?php echo ($gmwqp_include_exclude=='exclude')?'checked':''; ?> value="exclude" target="gmwqp_exclude_div" class="includexdfocheck">Exclude
        </td>
      </tr>
       <tr valign="top" class="gmwqp_include_div"  style='<?php echo (($gmwqp_include_exclude=='include')?'':'display:none;') ; ?>' >
            <th scope="row">
               <label for="gmwqp_include_category"><?php _e('Include From Category', 'gmwqp'); ?></label>
            </th>
            <td> 
              <?php
              $terms_cat = get_terms( 'product_cat', array(
                        'hide_empty' => false,
                    ) );

             
              ?>
               <select name="gmwqp_include_category[]" multiple  class="gmwqp-select" style="min-width: 200px;">
                 <?php
                 foreach ($terms_cat as $key_terms_cat => $value_terms_cat) {
                   echo '<option value="'.$value_terms_cat->term_id.'" '.((in_array($value_terms_cat->term_id, $gmwqp_include_category))?'selected':'').'>'.$value_terms_cat->name.'</option>';
                 }
                 ?>
                </select>
            </td>
        </tr>
        <tr valign="top"class="gmwqp_exclude_div"  style='<?php echo (($gmwqp_include_exclude=='exclude')?'':'display:none;') ; ?>' >
            <th scope="row">
               <label for="gmwqp_exclude_category"><?php _e('Exclude From Category', 'gmwqp'); ?></label>
            </th>
            <td> 
              <?php
              $terms_cat = get_terms( 'product_cat', array(
                        'hide_empty' => false,
                    ) );

             
              ?>
               <select name="gmwqp_exclude_category[]" multiple  class="gmwqp-select" style="min-width: 200px;">
                 <?php
                 foreach ($terms_cat as $key_terms_cat => $value_terms_cat) {
                   echo '<option value="'.$value_terms_cat->term_id.'" '.((in_array($value_terms_cat->term_id, $gmwqp_exclude_category))?'selected':'').'>'.$value_terms_cat->name.'</option>';
                 }
                 ?>
                </select>
            </td>
        </tr>
	</table>
	<?php  submit_button(); ?>
</form>