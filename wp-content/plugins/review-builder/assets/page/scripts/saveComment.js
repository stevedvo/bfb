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

	jQuery('.sgrb-displayedComment').each(function(){
		if (jQuery(this).text().length > 40) {
			var string = jQuery(this).text().substring(0, 40);
			string = string +' ...';
			jQuery(this).text(string);
		}
	});

	jQuery('.sgrb-select-review').change(function(){
		var reviewId = jQuery(this).val();
		if (reviewId) {
			SGRB.ajaxSelectReview(reviewId);
		}
		else if (!reviewId) {
			jQuery('input[name=review]').val('');
			jQuery('.sgrb-ajax-load-categories').empty();
		}
	});
	
	jQuery('.sgrb-js-update').click(function(){
		that.save();
	});

	jQuery(function(){
		jQuery(".color-picker").wpColorPicker();
	});
};

SGRB.prototype.save = function() {
	var isEdit = false;
	var form = jQuery('.sgrb-js-form');
	var review = jQuery('.sgrb-review').val();
	if (!review) {
		alert('Review not selected');
		return;
	}
	if (jQuery('.sgrb-select-post-category').val() && !jQuery('.sgrb-select-post').val()) {
		alert('There are no posts with selected category');
		return;
	}
	var saveAction = 'Comment_ajaxSave';
	var ajaxHandler = new sgrbRequestHandler(saveAction, form.serialize());
	ajaxHandler.dataIsObject = false;
	ajaxHandler.dataType = 'html';
	jQuery('.sgrb-loading-spinner').show();
	var sgrbSaveUrl = jQuery('input[name=sgrbSaveUrl]').val();
	ajaxHandler.callback = function(response){
		//If success
		if(response) {
			isEdit = true;
			//Response is comment id
			jQuery('input[name=sgrb-id]').val(response);
			location.href=sgrbSaveUrl+"&id="+response+'&edit='+isEdit;
		jQuery('.sgrb-loading-spinner').hide();
		}
	}
	ajaxHandler.run();
}

SGRB.ajaxDelete = function(id) {
	if (confirm('Are you sure?')) {
		var deleteAction = 'Comment_ajaxDelete';
		var ajaxHandler = new sgrbRequestHandler(deleteAction, {id: id});
		ajaxHandler.dataType = 'html';
		ajaxHandler.callback = function(response){
			//If success
			location.reload();
		}
		ajaxHandler.run();
	}
};

SGRB.ajaxApproveComment = function(id) {
	var approveAction = 'Comment_ajaxApproveComment';
	var ajaxHandler = new sgrbRequestHandler(approveAction, {id: id});
		ajaxHandler.dataType = 'html';
		ajaxHandler.callback = function(response){
			location.reload();
		}
	ajaxHandler.run();
}

SGRB.ajaxSelectReview = function(id) {
	var commentLoader = jQuery('.sgrb-comment-loader');
	var selectAction = 'Comment_ajaxSelectReview';
	var ajaxHandler = new sgrbRequestHandler(selectAction, {id: id});
	commentLoader
		.removeAttr('style')
		.show();
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		var html = '';
		var count = 0;
		if (response) {
			commentLoader.hide();
			jQuery('input[name=sgrb-id]').val(id);
			jQuery('input[name=review]').val(id);
			jQuery('.sgrb-ajax-load-categories').empty();

			var obj = jQuery.parseJSON(response);
			for (var i in obj) {
				if (obj[i].categoryId) {
					html += '<div class="sgrb-category-row-wrapper"><span>Category : </span><select name="categories[]" class="sgrb-category"><option value="'+obj[i].categoryId+'">'+obj[i].name+'</option></select>';

					html += '<span>Rate (1-'+parseInt(obj[i].count)+') : </span><select class="sgrb-each-rate-skin" name="rates[]">';

					for (var s = 1;s <= obj[i].count;s++) {
							html += '<option value="'+s+'">'+s+'</option>';
						}
					html += '</select><code class="sgrb-rate-type-code">'+obj[i].ratingType+'</code></div>';
				}
			}
			if (obj.postCategoies) {
				jQuery('.sgrb-select-post-category').removeAttr('disabled').empty();
				for (var i in obj.postCategoies) {
					jQuery('<option value="'+obj.postCategoies[i].postCategoryId+'">'+obj.postCategoies[i].postCategoryTitle+'</option>').appendTo('.sgrb-select-post-category');
				}
				
				/*jQuery('.sgrb-select-post').removeAttr('disabled').empty();
				for (var i in obj.posts) {
					jQuery('<option value="'+obj.posts[i].postId+'">'+obj.posts[i].postTitle+'</option>').appendTo('.sgrb-select-post');
				}*/
				jQuery('.sgrb-select-post-category').on('change', function(){
					var selectedCategory = jQuery(this).val();
					SGReview.prototype.ajaxSelectPosts(selectedCategory);
				});
				
			}
			else {
				jQuery('.sgrb-select-post-category').empty();
				jQuery('.sgrb-select-post').empty();
				jQuery('<option>Select post category</option>').appendTo('.sgrb-select-post-category');
				jQuery('<option>Select post</option>').appendTo('.sgrb-select-post');
			}
			jQuery(html).appendTo('.sgrb-ajax-load-categories');
		}
	}
	ajaxHandler.run();
};

/**
 * getURLParameter() checked if it is create
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
/**
 * ajaxSelectPosts() select posts to show
 * @param categoryId is category id (integer)
 */
SGReview.prototype.ajaxSelectPosts = function(categoryId) {
	var selectAction = 'Comment_ajaxSelectPosts';
	var ajaxHandler = new sgrbRequestHandler(selectAction, {categoryId: categoryId});
	ajaxHandler.dataType = 'html';
	ajaxHandler.callback = function(response){
		if (response) {
			var obj = jQuery.parseJSON(response);
			if (!jQuery.isEmptyObject(obj)) {
				jQuery('.sgrb-select-post').removeAttr('disabled').empty();
				for (var i in obj) {
					jQuery('<option value="'+obj[i].postId+'">'+obj[i].postTitle+'</option>').appendTo('.sgrb-select-post');
				}
			}
			else {
				jQuery('.sgrb-select-post').empty();
				jQuery('<option value="">No post with this category</option>').appendTo('.sgrb-select-post');
			}
		}
	}
	ajaxHandler.run();
}
