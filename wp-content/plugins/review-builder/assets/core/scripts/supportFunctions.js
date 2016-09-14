jQuery(document).ready(function(){
	jQuery('#wpcontent').find('.subsubsub').attr('style','margin-top:66px;position:absolute;');
	var type = jQuery('.sgrb-rating-type').val(),
		isRated = false,
		value = 1,
		skinHtml = '',
		mainFinalRate = jQuery('.sgrb-final-rate');
	if (!jQuery('.sgrb-hide-show-wrapper').length) {
		isRated = true;
	}
	if (type == 1) {
		skinHtml = '<div class="sgrb-each-rateYo"></div>';
		if (!jQuery('.sgrb-row-category').is(':visible') || jQuery('.sgrb-widget-wrapper')) {
			jQuery(document).ajaxComplete(function(){
				jQuery('.sgrb-each-comment-rate').append(skinHtml);
				jQuery('.sgrb-approved-comments-wrapper').each(function(){
					value = jQuery(this).find('.sgrb-each-comment-avg-widget').val();
					avgVal = 0;
					value = value.split(',');
					for (var i=0;i<value.length;i++) {
						avgVal = parseInt(avgVal+parseInt(value[i]));
					}
					avgVal = Math.round(avgVal/value.length);
					jQuery(this).find('.sgrb-each-rateYo').rateYo({
						rating: avgVal,
						readOnly: true
					});
				});
			});
		}
		if (jQuery('.sgrb-common-wrapper')) {
			if (jQuery('.sgrb-is-empty-field').val() == 1) {
				jQuery('.rateYoAll').rateYo({
					starWidth: "60px",
					fullStar: true,
					onChange: function (rating, rateYoInstance) {
						var res = jQuery(this).next().text(rating).text();
					}
				});
			}

			var rateTextColor = jQuery('.sgrb-rate-text-color').val();
			if (rateTextColor) {
				mainFinalRate.css('color',rateTextColor);
			}
			var skinColor = jQuery('.sgrb-skin-color').val();
			if (skinColor) {
				jQuery(".rateYo").rateYo({
					ratedFill: skinColor,
					starWidth: "30px",
					fullStar: true,
					maxValue: 5,
					onChange: function (rating, rateYoInstance) {
						jQuery(this).next().text(rating);
						jQuery('.sgrb-each-rate-skin').each(function(){
							var res = jQuery(this).parent().find(".sgrb-counter").text();
							jQuery(this).parent().find('.sgrb-each-rate-skin').val(res);
						});
					}
				});
			}
			var eachCategoryTotal = '';
			if (isRated) {
				jQuery('.sgrb-common-wrapper').find('.sgrb-rate-each-skin-wrapper').each(function(){
					eachCategoryTotal = jQuery(this).find('.sgrb-each-category-total').val();
					jQuery(this).find('.rateYo').rateYo({
						rating: eachCategoryTotal,
						readOnly: true
					})
				});
			}
			else {
				jQuery(".rateYo").rateYo({
					starWidth: "30px",
					fullStar: true,
					maxValue: 5,
					onChange: function (rating, rateYoInstance) {
						jQuery(this).next().text(rating);
						jQuery('.sgrb-each-rate-skin').each(function(){
							var res = jQuery(this).parent().find(".sgrb-counter").text();
							jQuery(this).parent().find('.sgrb-each-rate-skin').val(res);
						});
					}
				});
				jQuery('.rateYoAll').attr('style', 'margin-top: 110px; margin-left:30px;position:absolute');
				jQuery('.sgrb-counter').attr('style', 'display:none');
				jQuery('.sgrb-allCount').attr('style', 'display:none');

				jQuery('.sgrb-user-comment-submit').on('click', function(){
					jQuery(".rateYo").rateYo({readOnly:true});
				});
			}
		}
	}
	else if (type == 2) {
		skinHtml = '<div class="circles-slider"></div>';
		if (!jQuery('.sgrb-row-category').is(':visible') || jQuery('.sgrb-widget-wrapper')) {
			jQuery(document).ajaxComplete(function(){
				var slider = jQuery('.sgrb-widget-wrapper').find('.circles-slider');
				jQuery('.sgrb-each-comment-rate').append(skinHtml).attr('style','padding:0 !important;min-height:30px;');
				jQuery('.sgrb-approved-comments-wrapper').each(function(){
					value = jQuery(this).find('.sgrb-each-comment-avg-widget').val();
					avgVal = 0;
					value = value.split(',');
					for (var i=0;i<value.length;i++) {
						avgVal = parseInt(avgVal+parseInt(value[i]));
					}
					avgVal = Math.round(avgVal/value.length);
					jQuery(this).find('.circles-slider').slider({
						max:100,
						value: avgVal
					}).slider("pips", {
						rest: false,
						labels:100
					}).slider("float", {
					});
				});
				jQuery('.sgrb-widget-wrapper').find('.circles-slider').attr('style','pointer-events:none;margin: 40px 30px 0 27px !important;width: 78% !important;clear: right !important;');
				jQuery('.sgrb-widget-wrapper').find('.circles-slider .ui-slider-handle').addClass('ui-state-hover ui-state-focus');
			});
		}
		if (jQuery('.sgrb-common-wrapper')) {
			if (isRated) {
				jQuery('.sgrb-each-percent-skin-wrapper').each(function(){
					value = jQuery(this).find('.sgrb-each-category-total').val();
					jQuery(this).find('.circles-slider').slider({
						max:100,
						value: value
					}).slider("pips", {
						rest: false,
						labels:100
					}).slider("float", {
					});
				});
				jQuery('.sgrb-common-wrapper').find('.circles-slider').attr('style','pointer-events:none;float:right !important;');
				jQuery('.circles-slider .ui-slider-handle').addClass('ui-state-hover ui-state-focus');
			}
			else {
				jQuery(".circles-slider").slider({
					max:100,
					value: value,
					change: function(event, ui) {
						jQuery(this).parent().parent().find('.sgrb-each-rate-skin').val(ui.value);
					}
				}).slider("pips", {
					rest: false,
					labels:100
				}).slider("float", {
				});
			}
		}
	}
	else if (type == 3) {
		var point = jQuery('.sgrb-point');
		mainFinalRate.parent().css('margin','30px 15px 30px 0');
		skinHtml = '<select class="sgrb-point">'+
								  '<option value="1">1</option>'+
								  '<option value="2">2</option>'+
								  '<option value="3">3</option>'+
								  '<option value="4">4</option>'+
								  '<option value="5">5</option>'+
								  '<option value="6">6</option>'+
								  '<option value="7">7</option>'+
								  '<option value="8">8</option>'+
								  '<option value="9">9</option>'+
								  '<option value="10">10</option>'+
							'</select>';
		if (!jQuery('.sgrb-row-category').is(':visible') || jQuery('.sgrb-widget-wrapper')) {
			jQuery(document).ajaxComplete(function(){
				jQuery('.sgrb-each-comment-rate').append(skinHtml).attr('style','padding:0 !important;min-height:30px;');
				jQuery('.sgrb-approved-comments-wrapper').each(function(){
					value = jQuery(this).find('.sgrb-each-comment-avg-widget').val();
					avgVal = 0;
					value = value.split(',');
					for (var i=0;i<value.length;i++) {
						avgVal = parseInt(avgVal+parseInt(value[i]));
					}
					avgVal = Math.round(avgVal/value.length);
					jQuery(this).find('.sgrb-point').barrating({
						theme : 'bars-1to10',
						readonly: true
					});
					jQuery(this).find('.sgrb-point').barrating('set',avgVal);
					jQuery('.sgrb-widget-wrapper').find(".br-wrapper").attr('style','margin-top: 2px !important;');
					jQuery('.sgrb-widget-wrapper').find('.sgrb-point').parent().find('a').attr("style", 'width:8%;box-shadow:none;border:1px solid #dbe867;');
					jQuery('.sgrb-widget-wrapper').find('.br-current-rating').attr('style','height:27px !important;line-height:1.5 !important;');
				});
			});
		}
		if (isRated) {
			jQuery('.sgrb-common-wrapper').find('.sgrb-rate-each-skin-wrapper').each(function(){
				pointValue = jQuery(this).find('.sgrb-each-category-total').val();
				pointValue = Math.round(pointValue);
				jQuery(this).find('.sgrb-point').barrating({
					theme : 'bars-1to10',
					readonly: true
				});
				jQuery(this).find('.sgrb-point').barrating('set',pointValue);
			});
			point.barrating('show');
		}
		else {
			point.barrating({
				theme : 'bars-1to10',
				onSelect: function (value, text, event) {
					this.$widget.parent().parent().parent().find('.sgrb-each-rate-skin').val(value);
					mainFinalRate.text(value);
					mainFinalRate.attr('style','margin:8px 0 0 30px;color: rgb(237, 184, 103); display: inline-block;width:70px;height:70px;position:relative;font-size:4em;text-align:center');
				}
			});
		}
			point.barrating('show');
			jQuery('.br-current-rating').attr('style','display:none');
			jQuery(".br-wrapper").attr("style", 'display:inline-block;float:right;height:28px;');
			jQuery('.sgrb-each-rate-skin').each(function(){
				var skinColor = jQuery('.sgrb-skin-color').val(),
					colorOptions = '';

				if (skinColor) {
					colorOptions += 'background-color:'+skinColor;
				}
				point.parent().find('a').attr("style", 'width:9px;box-shadow:none;border:1px solid #dbe867;');
			});
			jQuery('.sgrb-user-comment-submit').on('click', function(){
				point.barrating('readonly',true);
			});
	}

});
