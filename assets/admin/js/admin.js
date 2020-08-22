jQuery(document).ready(function($) {
	$('#woo-userroles-list').select2();
    $('#woo-user-list').select2();
    $('#woo-product-list').select2();
	$('#woo-category-list').select2();


  if($('.is_other_admin_mail').is(':checked')) {
		var parrent_ele = $('#other_admin_mail').parent().parent();
		parrent_ele.show();
  }
  else {
  	var parrent_ele = $('#other_admin_mail').parent().parent();
		parrent_ele.hide();
  }
  if($('.is_override_form_heading').is(':checked')) {
		var parrent_ele = $('#custom_static_heading').parent().parent();
		parrent_ele.show();
  }
  else {
  	var parrent_ele = $('#custom_static_heading').parent().parent();
		parrent_ele.hide();
  }
  if($('.is_page_redirect').is(':checked')) {
		var parrent_ele = $('#redirect_page_id').parent().parent();
		parrent_ele.show();
  }
  else {
  	var parrent_ele = $('#redirect_page_id').parent().parent();
		parrent_ele.hide();
  }
  if($('.is-fileupload').is(':checked')) {
		var parrent_ele = $('#filesize-limit').parent().parent();
		parrent_ele.show();
  }
  else {
  	var parrent_ele = $('#filesize-limit').parent().parent();
		parrent_ele.hide();
  }
  
	$('.is_other_admin_mail').change(function() {
			if($(this).is(":checked")) {
				var parrent_ele = $('#other_admin_mail').parent().parent();
				parrent_ele.show('slow');
			}
			else {
				var parrent_ele = $('#other_admin_mail').parent().parent();
				parrent_ele.hide('slow');
			}
	});
	$('.is_override_form_heading').change(function() {
			if($(this).is(":checked")) {
				var parrent_ele = $('#custom_static_heading').parent().parent();
				parrent_ele.show('slow');
			}
			else {
				var parrent_ele = $('#custom_static_heading').parent().parent();
				parrent_ele.hide('slow');
			}
	});
	$('.is_page_redirect').change(function() {
			if($(this).is(":checked")) {
				var parrent_ele = $('#redirect_page_id').parent().parent();
				parrent_ele.show('slow');
			}
			else {
				var parrent_ele = $('#redirect_page_id').parent().parent();
				parrent_ele.hide('slow');
			}
	});
	$('.is-fileupload').change(function() {
			if($(this).is(":checked")) {
				var parrent_ele = $('#filesize-limit').parent().parent();
				parrent_ele.show('slow');
			}
			else {
				var parrent_ele = $('#filesize-limit').parent().parent();
				parrent_ele.hide('slow');
			}
	});
	// Hide choose your button type select option
	var parrent_ele = $('#button-link-catalog').parent().parent();
	parrent_ele.hide();
	$('#button-type').change(function() {
		if( $(this).val() == 2 ){
			var parrent_ele = $('#button-link-catalog').parent().parent();
				parrent_ele.show();
		}else{
			var parrent_ele = $('#button-link-catalog').parent().parent();
				parrent_ele.hide();
		}
	});

	if ($('#button-type').val() == 2) {
        var parrent_ele = $('#button-link-catalog').parent().parent();
		parrent_ele.show();
    } else {
        var parrent_ele = $('#button-link-catalog').parent().parent();
		parrent_ele.hide();
    }

});
