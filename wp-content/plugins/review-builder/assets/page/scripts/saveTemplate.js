function SGRB(){
	this.init();
};

function SGReview(){

};

SGRB.prototype.init = function() {
	var that = this;

	if (SGReview.prototype.getURLParameter('edit')) {
		jQuery('<div class="updated notice notice-success is-dismissible below-h2">' +
				'<p>Comment updated.</p>' +
				'<button type="button" class="notice-dismiss" onclick="jQuery(\'.notice\').remove();"></button></div>').appendTo('.sgrb-top-bar h1');
	}

	jQuery('.sgrb-template-js-update').on('click', function(){
		that.save();
	});
	SGRB.uploadTemplateImage();
	SGRB.removeImageButton();
	jQuery('.sgrb-template-image-name').hide();

	jQuery('.sgrb-preview-eye').each(function(){
		jQuery(this).hover(
			function(){
				jQuery(this).find('.sgrb-template-icon-preview').attr('id','sgrb-template-display-style');
			},
			function(){
				jQuery(this).find('.sgrb-template-icon-preview').removeAttr('id');
			}
		);
	});
}

SGRB.ajaxDeleteTemplate = function(id) {
	if (confirm('Are you sure?')) {
		var deleteAction = 'TemplateDesign_ajaxDeleteTemplate';
		var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
		ajaxHandler.dataType = 'html';
		ajaxHandler.callback = function(response){
			/* If success */
			if (response) {
				location.reload();
			}
			else {
				alert('Can not delete.This template attached to Your reviews.');
				return;
			}
		}
		ajaxHandler.run();
	}
}

SGRB.prototype.save = function() {
	var isEdit = false;
	var sgrbError = false;
	var form = jQuery('.sgrb-template-js-form');
	if(jQuery('.sgrb-title-input').val().replace(/\s/g, "").length <= 0){
		sgrbError = 'Title field is required';
	}
	if (sgrbError) {
		alert(sgrbError);
		return;
	}
	var saveAction = 'TemplateDesign_ajaxSave';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize());
	ajaxHandler.dataIsObject = false;
	ajaxHandler.dataType = 'html';
	jQuery('.sgrb-loading-spinner').show();
	var sgrbSaveUrl = jQuery('input[name=sgrbSaveUrl]').val();
	ajaxHandler.callback = function(response){
		/* If success*/
		if(response) {
			jQuery('.sgrb-loading-spinner').hide();
			isEdit = true;
			/* Response is template id */
			jQuery('input[name=sgrbTemplateId]').val(response);
			location.href=sgrbSaveUrl+"&id="+response+'&edit='+isEdit;
		}
		else {
			alert('Template with this title already exists');
			jQuery('.sgrb-loading-spinner').hide();
			return;
		}
	}
	ajaxHandler.run();
}

/**
 * getURLParameter() check if it is create
 * or edit
 * @param params is boolean
 */
SGReview.prototype.getURLParameter = function(params) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == params) {
			return sParameterName[1];
		}
	}
}

SGRB.uploadTemplateImage = function(){
	jQuery('.sgrb-tepmlate-img-upload').on('click', function(e) {
		var wrapperDiv = jQuery(this).parent().parent(),
			wrap = jQuery(this),
			imgNum = jQuery(this).next('.sgrb-img-num').attr('data-auto-id');
		e.preventDefault();
		var image = wp.media({
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e){
			jQuery('.sgrb-template-image-name').show();
			jQuery('.sgrb-tepmlate-img-upload').val('Remove image');
			var uploaded_image = image.state().get('selection').first();
			var image_url = uploaded_image.toJSON().url;
			jQuery('#sgrb-templateimg-url').val(image_url);
			jQuery('.sgrb-image-review').attr('style',"background-image:url("+image_url+");width: 200px;height:200px;background-color:#f7f7f7;margin: 0 auto;");
		});
	});
}

SGRB.removeImageButton = function(){
	jQuery('span.sgrb-remove-template-img-btn').on('click', function() {
		var uploaded_image = '';
		jQuery(this).parent().parent().parent().attr('style', "background-image:url();width: 200px;height:200px;background-color:#f7f7f7;margin: 0 auto;");
		jQuery(this).parent().parent().find('.sgrb-images').val('');
	});
}

SGReview.prototype.insertTag = function(tag) {
	tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
}