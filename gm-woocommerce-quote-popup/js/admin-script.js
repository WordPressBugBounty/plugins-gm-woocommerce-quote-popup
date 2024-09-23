(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    jQuery(function() {
        jQuery('.gmwqp-color-field').wpColorPicker();
        jQuery('.gmwqp-select').select2();
    });
     
    

})( jQuery );


 jQuery( document ).ready(function() {

 		jQuery(".sourfocheck").change(function(){
			if(jQuery(this).is(":checked")){
				jQuery("."+jQuery(this).attr("target")).show();
			}else{
				jQuery("."+jQuery(this).attr("target")).hide();
			}
			
		  return false;
		});	
		jQuery(".includexdfocheck").change(function(){
			if(jQuery(this).val()=="include"){
				jQuery(".gmwqp_include_div").show();
				jQuery(".gmwqp_exclude_div").hide();
			}else if(jQuery(this).val()=="exclude"){
				jQuery(".gmwqp_include_div").hide();
				jQuery(".gmwqp_exclude_div").show();
			}else{
				jQuery(".gmwqp_include_div").hide();
				jQuery(".gmwqp_exclude_div").hide();
			}
			
		//  return false;
		});

		
		jQuery(".addnew_customizer_form").click(function(){
			
			jQuery(".showpopmain").show();
		  return false;
		});	
		jQuery(".editfield_pop").click(function(){
			jQuery(".showpopmain").show();
		  return false;
		});	
		jQuery(".closeicond").click(function(){
			jQuery(".showpopmain").hide();
		  return false;
		});	
		jQuery(".field_type_gmqqp").change(function(){
			
			var field_type_cfwjm = jQuery(this).val();
			if (field_type_cfwjm=='select' || field_type_cfwjm=='radio' || field_type_cfwjm=='multiselect' || field_type_cfwjm=='checkbox') {
				jQuery(".gmqqp_option").show();
			}else{
				jQuery(".gmqqp_option").hide();
			}
		  return false;
		});	

});
