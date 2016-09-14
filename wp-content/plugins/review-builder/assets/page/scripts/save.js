function SGRB(){
	this.init();
};

function SGReview(){
};

SGRB.prototype.init = function(){
	var that = this;

	SGReview.prototype.isHidden();
	if (SGReview.prototype.getURLParameter('edit')) {
		jQuery('<div class="updated notice notice-success is-dismissible below-h2">' +
				'<p>Review updated.</p>' +
				'<button type="button" class="notice-dismiss" onclick="jQuery(\'.notice\').remove();"></button></div>').appendTo('.sgrb-top-bar h1');
	}
	jQuery(document).ajaxComplete(function(){
		jQuery('.sgrb-common-wrapper').find('.sgrb-each-comment-rate').remove();
		jQuery('.sgrb-widget-wrapper').find('.sgrb-loading-spinner').hide();
	});
	
	if (jQuery('.sgrb-show-tooltip').length) {
		var totalRateCout = jQuery('.sgrb-show-tooltip');
		var sgrbTooltip = jQuery('.sgrb-tooltip');

		totalRateCout.on('hover', function(){
			sgrbTooltip.show(100);
		});
		totalRateCout.on('mouseleave', function(){
			sgrbTooltip.hide(100);
		});
	}
	if (jQuery('.sgrb-widget-wrapper').find('.sgrb-show-tooltip-widget').length) {
		var totalRateCoutw = jQuery('.sgrb-show-tooltip-widget');
		var sgrbTooltipw = jQuery('.sgrb-tooltip-widget');

		totalRateCoutw.on('hover', function(){
			sgrbTooltipw.show();
		});
		totalRateCoutw.on('mouseleave', function(){
			sgrbTooltipw.hide();
		});
	}

	//border:none; if template image-div has background-image
	if (jQuery('.sgrb-image-review').length) {
		jQuery('.sgrb-image-review').each(function(){
			if (jQuery(this).attr('style') != 'background-image:url();') {
				jQuery(this).parent().attr('style', 'border:none;');
			}
		});
	}

	jQuery('.sgrb-custom-template-hilghlighting').next().click(function(){
		jQuery('.sgrb-custom-template-hilghlighting').attr('style','position:absolute;color:#3F3F3F;margin-left:10px;z-index:9');
		jQuery(this).prev().attr('style','position:absolute;color:#3F3F3F;margin:-4px;z-index:9');
		jQuery('.sgrb-template-label').find('.sgrb-highlighted').removeClass('sgrb-highlighted');
		jQuery(this).addClass('sgrb-highlighted');
	});

	if (jQuery('.sgrb-custom-template-hilghlighting').length) {
		jQuery('.sgrb-default-template-js').click(function(){
			jQuery('.sgrb-custom-template-hilghlighting').attr('style','position:absolute;color:#3F3F3F;margin-left:10px;z-index:9');
			jQuery('.sgrb-custom-template-hilghlighting').next().removeClass('sgrb-highlighted');
		});
	}

	if (jQuery('.sgrb-template-shadow-style').val()) {
		var shadowStyle = jQuery('.sgrb-template-shadow-style').val();
		jQuery('.sg-template-wrapper').find('.sg-tempo-title').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-image ').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-title-second ').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-title-info ').attr('style', shadowStyle);
		jQuery('.sg-template-wrapper').find('.sg-tempo-context').attr('style', shadowStyle);
	}
	SGRB.uploadImageButton();
	SGRB.removeImageButton();
	if (jQuery('.sgrb-approved-comments-to-show').length) {
		var commentsPerPage = parseInt(jQuery('.sgrb-page-count').val());
		if (jQuery('.sgrb-comments-count-load').val()) {
			commentsPerPage = jQuery('.sgrb-comments-count').val();
		}
		/*if (jQuery('.sgrb-widget-wrapper')) {
			SGRB.ajaxPagination(1,0,5);
		}
		else {*/
			SGRB.ajaxPagination(1,0,commentsPerPage);
		//}
	}

	jQuery('.sgrb-read-more').on('click', function(){
		showHideComment('sgrb-read-more');
	});

	jQuery('.sgrb-hide-read-more').on('click', function(){
		showHideComment('sgrb-hide-read-more');
	});

	//////////////
	var currentFont = jQuery('.sgrb-current-font').val();
	if(currentFont) {
		SGReview.prototype.changeFont(currentFont);
	}
	/////////////

	if (!jQuery('.sgrb-total-rate-title').length) {
		jQuery('.sgrb-total-rate-wrapper').remove();
	}
	jQuery('.sgrb-google-search-checkbox').on('change', function(){
		var googleSearchOn = jQuery(this).prop("checked");
		if (!googleSearchOn) {
			jQuery('.sgrb-hide-google-preview').hide('slow');
			jQuery('.sgrb-google-search-box').attr('style', 'min-height:100px !important;');
		}
		else {
			jQuery('.sgrb-hide-google-preview').show('slow');
			jQuery('.sgrb-google-search-box').removeAttr('style');
		}
	});
	/////////////
	jQuery('.sgrb-email-hide-show-js').on('change', function(){
		var emailNotification = jQuery('.sgrb-email-notification');
		if (jQuery(this).attr('checked')) {
			var adminEmail = jQuery('.sgrb-admin-email').val();
			emailNotification.val(adminEmail);
		}
		else {
			emailNotification.val('');
		}
	});

	jQuery('.sgrb-js-update').on('click', function(){
		that.save();
	});

	jQuery('.sgrb-add-field').on('click', function(){
		that.clone();
	});

	jQuery('.sgrb-reset-options').on('click', function(){ 
		if (confirm('Are you sure?')) {
			jQuery('input[name=skin-color]').val('');
			jQuery('input[name=rate-text-color]').val('');
			jQuery('input[name=total-rate-background-color]').val('');
			jQuery('.wp-color-result').css('background-color','');
		}
	});

	jQuery('.sgrb-rate-type').on('change', function(){
		var value = jQuery(this).val();
		SGReview.prototype.changeType(value);
		SGReview.prototype.preview(value);
	});

	jQuery(function(){
		if(jQuery(".color-picker").length) {
			jQuery(".color-picker").wpColorPicker();
		}
	});

	var currentPreviewType = jQuery('.sgrb-rate-type:checked').val();
	SGReview.prototype.preview(currentPreviewType);
	
	var totalColorOptions = jQuery('.sgrb-total-color-options');

	jQuery('.sgrb-template-selector').on('click', function(){
		var currentTemplateName = jQuery('input[name=sgrb-template]').val(),
			all = jQuery('#sgrb-template-name').text(),
			container = jQuery('#sgrb-template').dialog({
				width:875,
				height: 600,
				modal: true,
				resizable: false,
				buttons : {
					"Select template": function() {
						var tempName = jQuery('input[name=sgrb-template-radio]:checked').val();
						if (all != tempName) {
							if (confirm('When change the template, you\'ll lose your uploaded images and texts. Continue ?')) {
								if (tempName == 'post_review') {
									jQuery('.sgrb-main-template-wrapper').hide();
									jQuery('.sgrb-template-options-box').hide();
									jQuery('.sgrb-post-template-wrapper').show();
									jQuery('.sgrb-template-post-box').attr('style', 'min-height:150px;');
								}
								else {
									jQuery('.sgrb-main-template-wrapper').show();
									jQuery('.sgrb-template-options-box').show();
									jQuery('.sgrb-post-template-wrapper').hide();
									that.ajaxSelectTemplate(tempName);
								}
								jQuery('input[name=sgrb-template]').val(tempName);
								jQuery('#sgrb-template-name').html(tempName);
								jQuery(this).dialog('destroy');
							}
						}
						else {
							jQuery(this).dialog("close");
						}
					},
					Cancel: function() {
						jQuery(this).dialog("close");
					}
				}
			}),
			scrollTo = jQuery('input[name=sgrb-template-radio]:checked').parent();
			jQuery('input[name=sgrb-template-radio]').each(function(){
				if (jQuery(this).val() == all) {
					jQuery(this).parent().find('input').attr('checked','checked');
					scrollTo = jQuery(this).parent();
				}
			});
		if (scrollTo.length != 0) {
			if(typeof container.offset().top !== 'undefined') {
				container.animate({
					scrollTop: (scrollTo.offset().top - container.offset().top + container.scrollTop()) - 7
					//Lowered to 7,because label has border and is highlighted (wip)
				});
			}
			
		}
		else {
		// Select template for the first time
			var defaultTheme = jQuery('#TB_ajaxContent label:first-child'),
				res = jQuery(defaultTheme).find('input').attr('checked','checked');
		}


	});
	
};

SGRB.prototype.ajaxSelectTemplate = function(tempName){
	var changeAction = 'Review_ajaxSelectTemplate';
	var ajaxHandler = new sgrbRequestHandler(changeAction, {template:tempName});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		//If success
		if(response) {
			jQuery('.sgrb-change-template').empty();
			jQuery('div.sgrb-main-template-wrapper').html(response);
			SGRB.uploadImageButton();
			SGRB.removeImageButton();
		}
	}
	ajaxHandler.run();

};

SGRB.uploadImageButton = function(){
	jQuery('span.sgrb-upload-btn').on('click', function(e) {
		var wrapperDiv = jQuery(this).parent().parent(),
			wrap = jQuery(this),
			imgNum = jQuery(this).next('.sgrb-img-num').attr('data-auto-id');
		e.preventDefault();
		var image = wp.media({
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e){
			var uploaded_image = image.state().get('selection').first();
			var image_url = uploaded_image.toJSON().url;
			jQuery('#sgrb_image_url_'+imgNum).val(image_url);
			jQuery(wrap).addClass('sgrb-image-review-plus');
			jQuery(wrapperDiv).addClass('sgrb-image-review');
			jQuery(wrapperDiv).parent().attr('style',"background-image:url("+image_url+")");
			jQuery(wrapperDiv).parent().parent().attr('style',"border:none;");
		});
	});
}

SGRB.removeImageButton = function(){
	jQuery('span.sgrb-remove-img-btn').on('click', function() {
		var uploaded_image = '';
		jQuery(this).parent().parent().parent().attr('style', "background-image:url()");
		jQuery(this).parent().parent().find('.sgrb-images').val('');
		jQuery(this).parent().parent().parent().parent().attr('style',"border: 2px dashed #ccc;border-radius: 10px;");
	});
}

SGRB.prototype.save = function(){
	var isEdit = true;
	var sgrbError = false;
	jQuery('.sgrb-updated').remove();
	var form = jQuery('.sgrb-js-form');
	var font = jQuery('.bfh-selectbox-option').text();
	if(jQuery('.sgrb-title-input').val().replace(/\s/g, "").length <= 0){
		sgrbError = 'Title field is required';
	}
	if (jQuery('.sgrb-one-field').length > 1) {
		jQuery('.sgrb-field-name').each(function() {
			if (jQuery(this).val() == '') {
				sgrbError = 'Empty category fields';
			}
		});
	}
	else if (jQuery('.sgrb-one-field').length == 1 || (jQuery('.sgrb-field-name').first().val() == '')) {
		if (jQuery('.sgrb-field-name').val() == '') {
			sgrbError = 'At least one category is required';
		}
	}
	if (sgrbError) {
		alert(sgrbError);
		return;
	}
	jQuery('.fontSelectbox').val(font);
	var saveAction = 'Review_ajaxSave';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize());
	ajaxHandler.dataIsObject = false;
	ajaxHandler.dataType = 'html';
	var sgrbSaveUrl = jQuery('.sgrbSaveUrl').val();
	jQuery('.sgrb-common-wrapper').find('.sgrb-loading-spinner').show();
	ajaxHandler.callback = function(response){
		//If success
		if(response) {
			jQuery('input[name=sgrb-id]').val(response);
			location.href=sgrbSaveUrl+"&id="+response+'&edit='+isEdit;
		}
		jQuery('.sgrb-common-wrapper').find('.sgrb-loading-spinner').hide();

	}
	ajaxHandler.run();
}

SGRB.prototype.clone = function(){
	var oneField = jQuery('.sgrb-one-field');
	var elementsCount = oneField.length,
		elementToClone = oneField.first(),
		elementToAppend = jQuery('.sgrb-field-container'),
		clonedElementId = elementsCount+1,
		clonedElement = elementToClone
							.clone()
							.find("input:text")
							.val("")
							.end()
							.appendTo(elementToAppend)
							.attr('id', 'clone_' + clonedElementId);
		clonedElement
			.find('.sgrb-remove-button')
			.removeAttr('onclick')
			.attr('onclick', "SGRB.remove('clone_"+clonedElementId+"')");
		clonedElement
			.find('.sgrb-fieldId')
			.val('');
	if (jQuery('.sgrb-field-name').length > 1) {
		jQuery('.sgrb-category-empty-warning').hide();
		jQuery('.sgrb-categories-title').attr('style', 'margin-bottom:32px;');
	}
};
SGRB.remove = function(elementId){
	var oneField = jQuery('.sgrb-one-field');
	var elementsCount = oneField.length;
	if (elementsCount <= 1) {
		alert('At least 1 field is needed');
		return;
	}
	if (confirm('Are you sure?')) {
		if (elementId.length > 5) {
			var clone = elementId.slice(0,6);
			if (clone) {
				jQuery('#' + elementId).remove();
				if (jQuery('.sgrb-field-name').length <= 1) {
					jQuery('.sgrb-category-empty-warning').show();
					jQuery('.sgrb-categories-title').removeAttr('style');
				}
				return;
			}
		}
		
		jQuery('#sgrb_' + elementId).remove();
		SGRB.ajaxDeleteField(elementId);
	}
};


SGRB.ajaxDelete = function(id){
	if (confirm('Are you sure?')) {
		var deleteAction = 'Review_ajaxDelete';
		var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
		ajaxHandler.dataType = 'html';
		ajaxHandler.callback = function(response){
			//If success
			location.reload();
		}
		ajaxHandler.run();
	}
};

SGRB.ajaxDeleteField = function(id){
	var deleteAction = 'Review_ajaxDeleteField';
	var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		//If success
	}
	ajaxHandler.run();
};

SGRB.ajaxPagination = function(page,itemsRangeStart,perPage){
	if (jQuery('.sgrb-load-it').length) {
		perPage = parseInt(jQuery('.sgrb-comments-count-load').val());
		if (!jQuery('.sgrb-comments-count-load').val()) {
			perPage = 3;
		}
	}
	
	var postId = '';
	var commentsPerPage = perPage,
		pageCount = jQuery('.sgrb-page-count').val(),
		postId = jQuery('.sgrb-post-id').val(),
		loadMore = jQuery('.sgrb-comment-load'),
		arr = parseInt(jQuery('.sgrb-current-page').text());
	
	var review = jQuery('.sgrb-reviewId').val();
	var jPageAction = 'Review_ajaxPagination';
	var ajaxHandler = new sgrbRequestHandler(jPageAction, {review:review,page:page,itemsRangeStart:itemsRangeStart,perPage:perPage,postId:postId});
	ajaxHandler.dataType = 'html';
	jQuery('.sgrb-common-wrapper').find('.sgrb-loading-spinner').show();
	jQuery('.sgrb-comment-load').hide();
	ajaxHandler.callback = function(response){
		var obj = jQuery.parseJSON(response);

		var commentHtml = '';
		var prev = parseInt(itemsRangeStart-commentsPerPage);
		var next = parseInt(itemsRangeStart+commentsPerPage);
		var formTextColor = jQuery('.sgrb-rate-text-color').val();
		var formBackgroundColor = jQuery('.sgrb-rate-background-color').val();
		if (!formTextColor) {
			formTextColor = '#4c4c4c';
		}
		if (!formBackgroundColor) {
			formBackgroundColor = '#f7f7f7';
		}
		if (jQuery.isEmptyObject(obj)) {
			loadMore.attr({
				'disabled':'disabled',
				'style' : 'cursor:default;color:#c1c1c1;vertical-align: text-top;pointer-events: none;'
			}).text('no more comments');
		}
		else {
			loadMore.removeAttr('disabled style');
			loadMore.attr('onclick','SGRB.ajaxPagination(1,'+next+','+commentsPerPage+')');
		}
		if (!jQuery('.sgrb-row-category').is(':visible') || jQuery('.sgrb-widget-wrapper')) {
			jQuery('.sgrb-widget-wrapper .sgrb-comment-load').remove();
		}
		for(var i in obj) {
			commentHtml += '<div class="sgrb-approved-comments-wrapper" style="background-color:'+formBackgroundColor+';color:'+formTextColor+'">';
			if (!jQuery('.sgrb-load-it').length) {
				if (!jQuery('.sgrb-row-category').is(':visible') || jQuery('.sgrb-widget-wrapper')) {
					commentHtml += '<input type="hidden" class="sgrb-each-comment-avg-widget" value="'+obj[i].rates+'">';
					commentHtml += '<div class="sg-row"><div class="sg-col-12"><div class="sgrb-comment-wrapper sgrb-each-comment-rate"></div></div></div>';
				}
			}
			commentHtml += '<div class="sg-row"><div class="sg-col-12"><div class="sgrb-comment-wrapper"><span style="width:100%;"><i><b>'+obj[i].title+' </i></b></span></div></div></div>';
			commentHtml += '<div class="sg-row"><div class="sg-col-12"><div class="sgrb-comment-wrapper">';
			if (obj[i].comment.length >= 200) {
				commentHtml += '<input class="sgrb-full-comment" type="hidden" value="'+obj[i].comment+'">';
				commentHtml += '<span class="sgrb-comment-text sgrb-comment-max-height">" '+obj[i].comment.substring(0,200)+' ... <a onclick="SGRB.prototype.showHideComment(\'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a></span>';
			}
			else {
				commentHtml += '<span class="sgrb-comment-text">" '+obj[i].comment+' "</span>';
			}

			if (!obj[i].name) {
				var name = 'Guest';
			}
			else {
				var name = obj[i].name;
			}
			commentHtml += '</div></div></div><div class="sg-row"><div class="sg-col-12"><span class="sgrb-name-title-text"><b>'+obj[i].date+'</b><i> , comment by </i><b>'+name+'</b></span></div></div>';
			commentHtml += '</div>';
		}
			if (jQuery('.sgrb-load-it').length) {
				jQuery('.sgrb-common-wrapper').find('.sgrb-approved-comments-to-show').append(commentHtml);
			}
			else {
				jQuery('.sgrb-approved-comments-to-show').append(commentHtml);
			}
			jQuery('.sgrb-approved-comments-to-show').addClass('sgrb-load-it');
			jQuery('.sgrb-common-wrapper').find('.sgrb-loading-spinner').hide();
			jQuery('.sgrb-common-wrapper').find('.sgrb-comment-load').show();
			//jQuery('.sgrb-widget-wrapper').find('.sgrb-approved-comments-to-show').removeClass('sgrb-approved-comments-to-show').addClass('sgrb-approved-comments-to-show-widget');
	}
	ajaxHandler.run();
};

SGRB.prototype.ajaxUserRate = function(){
	var error = false,
		captchaError = false,
		requiredEmail = jQuery('.sgrb-requiredEmail').val(),
		requiredTitle = jQuery('.sgrb-requiredTitle').val(),
		name = jQuery('input[name=addName]').val(),
		email = jQuery('input[name=addEmail]').val(),
		title = jQuery('input[name=addTitle]').val(),
		comment = jQuery('textarea[name=addComment]').val();
	var post = jQuery('input[name=addPostId]').val();
	var captcha = jQuery('input[name=addCaptcha]').val();
	var captchaCode = jQuery('input[name=captchaCode]').val();
	jQuery('.sgrb-notice').hide();
	jQuery('.sgrb-notice span').empty();
	if (requiredEmail && !email) {
		error = 'Email is required';
	}
	if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) && requiredEmail) {
		error = 'Uncorrect email address';
	}
	if (requiredTitle && !title) {
		error = 'Title is required.';
	}
	if (!name || !comment) {
		error = 'Name and comment are required';
	}
	if (captchaCode) {
		if (captchaCode.toUpperCase() !== captcha.toUpperCase() || !captcha) {
			captchaError = 'Invalid captcha text';
		}
	}
	if (error) {
		jQuery('.sgrb-notice span').show().text(error);
		jQuery('.sgrb-notice').show();
		return;
	}
	if (captchaError) {
		jQuery('.sgrb-captcha-notice span').show().text(captchaError);
		jQuery('.sgrb-captcha-notice').show();
		return;
	}

	jQuery('.sgrb-user-comment-submit').attr('disabled','disabled');
	var form = jQuery('.sgrb-user-rate-js-form'),
		cookie = jQuery('.sgrb-cookie').val(),
		saveAction = 'Review_ajaxUserRate';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize());
	ajaxHandler.dataType = 'html';
	ajaxHandler.dataIsObject = false;
	ajaxHandler.callback = function(response){
		if (response != 0 && response != NaN) {
			if (jQuery('.sgrb-total-rate-title').length == 0) {
				jQuery('.sgrb-total-rate-wrapper').removeAttr('style');
			}
			jQuery('.sgrb-notice').hide(500);
			jQuery('.sgrb-hide-show-wrapper').hide(1000);
			//jQuery('.sgrb-row-category').hide();
			jQuery('.sgrb-user-comment-wrapper').append('<span>Thank You for Your Comment</span>');
			jQuery.cookie('rater', cookie);
		}
		else if (response == 0) {
			jQuery('.sgrb-user-comment-submit').removeAttr('disabled');
			jQuery('.sgrb-notice span').show().text('Please, rate all suggested categories');
			jQuery('.sgrb-notice').show();
		}
	}
	ajaxHandler.run();
};

// show more or less comment
SGRB.prototype.showHideComment = function (className) {
	if (className == 'sgrb-read-more') {
		var fullText = jQuery('.sgrb-read-more')
							.parent()
							.parent()
							.find('.sgrb-full-comment')
							.val();
		jQuery('.sgrb-read-more')
			.parent()
			.parent()
			.find('.sgrb-comment-text')
			.empty()
			.removeClass('sgrb-comment-max-height')
			.html('" '+fullText+' " <a onclick="SGRB.prototype.showHideComment(\'sgrb-hide-read-more\')" href="javascript:void(0)" class="sgrb-hide-read-more">hide&#9650</a>');
	}

	if (className == 'sgrb-hide-read-more') {
		var fullText = jQuery('.sgrb-hide-read-more').parent().parent().find('.sgrb-full-comment').val();
		var cuttedText = fullText.substr(0, 200);
		jQuery('.sgrb-hide-read-more').parent().parent().find('.sgrb-comment-text').empty().addClass('sgrb-comment-max-height').html('" '+cuttedText+' ... <a onclick="SGRB.prototype.showHideComment(\'sgrb-read-more\')" href="javascript:void(0)" class="sgrb-read-more">show all&#9660</a>');
	}
}

/**
 * changeType() get skin style and set it as default.
 * @param type is integer
 */
SGReview.prototype.changeType = function (type) {
	/*var currentType = jQuery('.rate-type-notice').val();
	if (currentType != type) {
		jQuery('.type-warning').show();
	}
	else {
		jQuery('.type-warning').hide();
	}*/
	if (type == 1) {
		span = ' Rate (1-5) : ';
		type = 'star';
		count = 5;
	}
	else if (type == 2) {
		span = ' Rate (1-100) : ';
		type = 'percent';
		count = 100;
	}
	else if (type == 3) {
		span = ' Rate (1-10) : ';
		type = 'point';
		count = 10;
	}
	SGReview.prototype.rateSelectboxHtmlBuilder(type,span,count);
}

/**
 * preview() get skin style for show preview.
 * @param type is integer
 */
SGReview.prototype.preview = function (type) {
	var selectedType = '.sgrb-preview-'+type,
		skinColor = jQuery('.sgrb-skin-color'),
		skinStylePreview = jQuery('.sgrb-skin-style-preview');

	if (selectedType == '.sgrb-preview-1') {
		skinColor.show(200);
		skinStylePreview
			.empty()
			.html('<div></div>')
			.find('div')
			.attr('class','')
			.addClass('rateYoPreview');
		//jQuery('.rateYoPreview').attr('style','border-left:1px dotted #efefef;padding: 30px 100px;width:200px;background-color:#fcfcfc;');
		skinStylePreview.find('.sgrb-point').hide();
		skinStylePreview.removeClass('sgrb-skin-style-preview-percent sgrb-skin-style-preview-point');
		jQuery('.rateYoPreview').rateYo({
			rating: "3",
			fullStar: true,
			starWidth: "40px"
		});
	}
	else if (selectedType == '.sgrb-preview-2') {
		skinColor.show(200);
		skinStylePreview.empty().html('<div class="sgrb-percent-preview"><div></div></div>');
		skinStylePreview.removeClass('sgrb-skin-style-preview-point').addClass('sgrb-skin-style-preview-percent');
		jQuery('.sgrb-percent-preview').find('div').attr('class','').addClass('circles-slider');
		skinStylePreview.find('.sgrb-point').hide();
		jQuery(".circles-slider").slider({
			max:100,
			value: 40,
		}).slider("pips", {
			rest: false,
			labels:100
		}).slider("float", {
		});
	}
	else if (selectedType == '.sgrb-preview-3') {
		skinColor.hide(200);
		skinStylePreview.empty();
		skinStylePreview.removeClass('sgrb-skin-style-preview-percent').addClass('sgrb-skin-style-preview-point');
		skinStylePreview.html('<select class="sgrb-point"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select>');
		jQuery('.sgrb-point').barrating({
			theme : 'bars-1to10'
		});
		jQuery('.sgrb-point').barrating('set',5);
		jQuery('.br-theme-bars-1to10 .br-widget a').attr("style", "height:23px !important;width:18px !important;");
		//jQuery('.br-wrapper').attr('style','border-left:1px dotted #efefef !important;padding: 40px 100px !important;width:200px !important;background-color:#fcfcfc;');
		jQuery(".br-current-rating").attr("style", 'display:none;');
	}
}

/**
 * rateSelectboxHtmlBuilder() get skin style for show preview.
 * @param type is integer
 */
SGReview.prototype.rateSelectboxHtmlBuilder = function (type,span,count) {
	var selectBox = jQuery('.sgrb-select-box-count');
	jQuery('.sgrb-rate-count-span').text(span);
	jQuery('code').text(type);
	selectBox.val(count);
	var selectBoxCount = selectBox.val();
	var htmlRateSelectBox = '';
	for (var i=1;i<=selectBoxCount;i++) {
			htmlRateSelectBox += '<option value="'+i+'">'+i+'</option>';
	}
	jQuery('.sgrb-rate').empty();
	jQuery(htmlRateSelectBox).appendTo('.sgrb-rate');
}

/**
 * isHidden() checked if review rated by current user
 * and hide the comment form
 */
SGReview.prototype.isHidden = function () {
	if (!jQuery('.sgrb-hide-show-wrapper').is(":visible")) {
		//jQuery('.sgrb-row-category').hide();
	}
}

/**
 * getURLParameter() checked if it is create
 * or edit
 * @param params is boolean
 */
SGReview.prototype.getURLParameter = function (params) {
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) {
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == params) {
			return sParameterName[1];
		}
	}
}

SGReview.prototype.changeFont = function (fontName) {
	var font = fontName.replace(new RegExp(" ",'g'),"");
	var res = font.match(/[A-Z][a-z]+/g);
	var result = '';

	for (var i=0;i<res.length;i++) {
		result += res[i]+' ';
	}
	WebFontConfig = {
	google: { families: [ result.substr(0, result.length-1) ] }
  };
  (function() {
	var wf = document.createElement('script');
	wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
	  '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
	wf.type = 'text/javascript';
	wf.async = 'true';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(wf, s);

  })();
}
