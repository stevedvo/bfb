<?php
	global $sgrb;
	$sgrb->includeStyle('page/styles/jquery-ui-dialog');
	$sgrb->includeScript('core/scripts/jquery-ui-dialog');
	if (get_option(SG_REVIEW_BANNER)) {
		require_once($sgrb->app_path.'/com/layouts/Review/banner.php');
	}
?>

<div class="wrap">
	<form class="sgrb-js-form">
		<div class="sgrb-top-bar">
			<h1 class="sgrb-add-edit-title">
				<?php echo (@$sgrbRev->getId() != 0) ? _e('Edit Review', 'sgrb') : _e('Add New Review', 'sgrb');?>
				<span class="sgrb-spinner-save-button-wrapper">
					<i class="sgrb-loading-spinner"><img src='<?php echo $sgrb->app_url.'/assets/page/img/spinner-2x.gif';?>'></i>
					<a href="javascript:void(0)"
						class="sgrb-js-update button-primary sgrb-pull-right"> <?php _e('Save changes', 'sgrb'); ?></a>
				</span>
			</h1>
			<input class="sgrb-text-input sgrb-title-input" value="<?php echo esc_attr(@$sgrbDataArray['title']); ?>"
					type="text" autofocus name="sgrb-title" placeholder="<?php _e('Enter title here', 'sgrb'); ?>">
			<div class="sgrb-template-box">
				<strong><?php _e('Template: ', 'sgrb'); ?></strong><span id="sgrb-template-name"><?php echo isset($sgrbDataArray['template']) ? esc_attr($sgrbDataArray['template']) : 'full_width'; ?></span>
				<input  class="sgrb-template-selector button-small button" type="button" value="<?php _e('Select template', 'sgrb')?>"/>
			</div>
		</div>
		<input type="hidden" name="sgrb-id" value="<?php echo esc_attr(@$_GET['id']); ?>">
		<input type="hidden" name="sgrb-template" value="<?php echo isset($sgrbDataArray['template']) ? esc_attr($sgrbDataArray['template']) : 'full_width'; ?>">
		<input class="sgrb-link" type="hidden" data-href="<?php echo esc_attr($sgrb->app_url);?>">
		<div class="sgrb-main-container">
			<div class="sg-row">

				<div class="sg-col-8">
					<div class="sg-box sgrb-template-post-box"<?php echo (@$sgrbDataArray['template'] == 'post_review') ? ' style="min-height:150px;"' : '';?>>
						<div class="sg-box-title"><?php echo _e('General', 'sgrb');?></div>
						<div class="sg-box-content">
							<div class="sgrb-main-template-wrapper"><?php echo (@$sgrbDataArray['template'] != 'post_review') ? @$res : '';?></div>
							<div class="sgrb-post-template-wrapper"<?php echo (@$sgrbDataArray['template'] == 'post_review') ? '' : ' style="display:none;"';?>>
								<div class="sg-row">
									<div class="sg-col-4">
										<span><?php echo _e('Select post category to show current review:', 'sgrb');?></span>
									</div>
									<div class="sg-col-8">
										<select class="sgrb-post-category" name="post-category">
											<?php foreach (get_terms(array('get'=> 'all')) as $postCategory) :?>
												<option value="<?php echo $postCategory->term_id;?>"<?php echo (@$sgrbDataArray['post-category'] == $postCategory->term_id) ? ' selected': '';?>><?php echo $postCategory->name?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-6">
										<label for="sgrb-disable-wp-comments">
											<input id="sgrb-disable-wp-comments" value="true" type="checkbox" name="disableWPcomments"<?php echo (@$sgrbDataArray['disableWPcomments']) ? ' checked' : '';?>> <?php echo _e('Disable Wordpress default comments', 'sgrb');?>
										</label>
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-6">
										<p class="sgrb-type-warning">This review will only be shown in posts with selected category</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="sg-box">
						<div class="sg-box-title"><?php echo _e('Rate options ', 'sgrb');?> <a href="javascript:void(0)" class="sgrb-reset-options button-small button"> <?php echo _e('Reset to default','sgrb');?></a></div>
						<div class="sg-box-content">
							<div class="sgrb-preview-container">
							<div class="sg-row">
								<div class="sg-col-6">
									<div class="sgrb-rate-options-rows-rate-type-preview">
										<p><b><?php echo _e('Rate skin style', 'sgrb');?>: </b></p>
										<?php if (SGRB_PRO_VERSION == 1) :?>
											<p class="sgrb-type-warning"><?php echo (@$sgrbRev->getId() != 0) ? _e('If you change the type, you\'ll lose all comments for this review', 'sgrb') : '';?></p>
										<?php endif; ?>
										<input name="rate-type-notice" class="sgrb-rate-type-notice" type="hidden" value="<?php echo esc_attr(@$sgrbDataArray['rate-type']) ;?>">
										<label class="sgrb-preview-1" for="sgrb-rate-type-1">
											<?php if (SGRB_PRO_VERSION == 0) :?>
												<input id="sgrb-rate-type-1" class="sgrb-preview-1 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_STAR;?>" onclick="SGReview.prototype.changeType(<?php echo SGRB_RATE_TYPE_STAR;?>)" checked>
											<?php else :?>
												<input id="sgrb-rate-type-1" class="sgrb-preview-1 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_STAR;?>" onclick="SGReview.prototype.changeType(<?php echo SGRB_RATE_TYPE_STAR;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_STAR) ? ' checked' : '' ;?> <?php echo (@$sgrbRevId == 0) ? ' checked' : '';?>>
											<?php endif; ?>
											<?php echo _e('Star', 'sgrb');?>
										</label>
										<?php if (SGRB_PRO_VERSION == 0) :?>
											<div style="position:relative;float:right;width:200px;">
											<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button-second" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
												<label class="" for="">
													<input id="" class="sgrb-preview-2 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_PERCENT;?>" onclick="SGReview.prototype.changeType(<?php echo SGRB_RATE_TYPE_PERCENT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_PERCENT) ? ' checked' : '' ;?>>
													<?php echo _e('Percent', 'sgrb');?>
												</label>
												<label class="" for="">
													<input id="" class="" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_POINT;?>" onclick="SGReview.prototype.changeType(<?php echo SGRB_RATE_TYPE_POINT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_POINT) ? ' checked' : '' ;?>>
													<?php echo _e('Point', 'sgrb');?>
												</label>
											</div>
										<?php elseif (SGRB_PRO_VERSION == 1) :?>
											<label class="sgrb-preview-2" for="sgrb-rate-type-2">
												<input id="sgrb-rate-type-2" class="sgrb-preview-2 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_PERCENT;?>" onclick="SGReview.prototype.changeType(<?php echo SGRB_RATE_TYPE_PERCENT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_PERCENT) ? ' checked' : '' ;?>>
												<?php echo _e('Percent', 'sgrb');?>
											</label>
											<label class="sgrb-preview-3" for="sgrb-rate-type-3">
												<input id="sgrb-rate-type-3" class="sgrb-preview-3 sgrb-rate-type" type="radio" name="rate-type" value="<?php echo SGRB_RATE_TYPE_POINT;?>" onclick="SGReview.prototype.changeType(<?php echo SGRB_RATE_TYPE_POINT;?>)"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_POINT) ? ' checked' : '' ;?>>
												<?php echo _e('Point', 'sgrb');?>
											</label>
										<?php endif ; ?>
									</div>
								</div>
								<div class="sg-col-6">
									<div class="sgrb-skin-style-preview">
										<div class=""></div>
									</div>
								</div>
							</div>
							</div>
							<div class="sgrb-skin-color"<?php echo (@$sgrbDataArray['rate-type'] == SGRB_RATE_TYPE_POINT) ? ' style="display:none;"' : '' ;?>>
								<p><b><?php echo _e('Rate Skin color', 'sgrb');?>: </b></p>
								<span><input name="skin-color" type="text" value="<?php echo esc_attr(@$sgrbDataArray['skin-color']);?>" class="color-picker" /></span>
							</div>

							<div class="sgrb-total-show">
								<p><b><?php echo _e('Show total rate', 'sgrb');?>: </b></p>
								<select name="totalRate" class="sgrb-total-rate">
									<option value="1"<?php echo (@$sgrbDataArray['total-rate'] == 1 || (!@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('Yes', 'sgrb');?></option>
									<option value="0"<?php echo (@$sgrbDataArray['total-rate'] == 0 && (@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('No', 'sgrb');?></option>
								</select>
							</div>
							<div class="sgrb-comment-show">
								<p><b><?php echo _e('Show comments', 'sgrb');?>: </b></p>
								<select name="showComments" class="sgrb-total-rate">
									<option value="1"<?php echo (@$sgrbDataArray['show-comments'] == 1 || (!@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('Yes', 'sgrb');?></option>
									<option value="0"<?php echo (@$sgrbDataArray['show-comments'] == 0 && (@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('No', 'sgrb');?></option>
								</select>
							</div>

						<?php if (SGRB_PRO_VERSION == 0) :?>
							<div style='position: relative;overflow: hidden;width: 20%;'>
							<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
						<?php endif; ?>
							<div class="sgrb-comment-show" style="min-width:150px;">
								<p><b><?php echo _e('Include captcha', 'sgrb');?>: </b></p>
								<select name="captchaOn" class="sgrb-total-rate">
									<option value="1"<?php echo (@$sgrbDataArray['captcha-on'] == 1 || (!@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('Yes', 'sgrb');?></option>
									<option value="0"<?php echo (@$sgrbDataArray['captcha-on'] == 0 && (@$sgrbRev->getId())) ? ' selected' : '';?>><?php echo _e('No', 'sgrb');?></option>
								</select>
							</div>
						<?php if (SGRB_PRO_VERSION == 0) :?>
							</div>
						<?php endif;?>

							<div class="sgrb-total-color-options">
								<input type="hidden" class="sgrb-show-total" value="<?php echo (@$sgrbDataArray['total-rate']) ? 1 : 0;?>">
								<div class="sgrb-total-options-rows-rate-type">
									<p><b><?php echo _e('Form & content text color', 'sgrb');?>: </b></p>
									<span><input name="rate-text-color" type="text" value="<?php echo esc_attr(@$sgrbDataArray['rate-text-color']);?>" class="color-picker" /></span>
								</div>
								<div class="sgrb-total-options-rows-rate-type">
									<p><b><?php echo _e('Form & content background color', 'sgrb');?>: </b></p>
									<span><input name="total-rate-background-color" type="text" value="<?php echo esc_attr(@$sgrbDataArray['total-rate-background-color']);?>" class="color-picker" /></span>
								</div>
							</div>

						</div>
					</div>

					<div class="sg-box sgrb-template-options-box" style="min-height:100px;<?php echo (@$sgrbDataArray['template'] == 'post_review') ? 'display:none;' : '';?>">
						<div class="sg-box-title"><?php echo _e('Template customize options', 'sgrb');?></div>
						<div class="sg-box-content">
							<?php if (SGRB_PRO_VERSION == 1) :?>
								<?php require_once('templatesOptionsPro.php');?>
							<?php else :?>
								<div style='position: relative;'>
								<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="Upgrade to PRO version" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
									<div class="sgrb-template-options">
										<div class="sg-row">
											<div class="sg-col-4">
												<div class="sgrb-total-options-rows-rate-type">
													<p style="margin: 4px 0"><?php echo _e('Font', 'sgrb');?>: </p>
													<select>
														<option>Your custom font</option>
													</select>
												</div>
												<div class="sgrb-total-options-rows-rate-type">
													<p style="margin: 3px 0"><?php echo _e('Background color', 'sgrb');?>: </p>
													<span><input type="text" class="color-picker" /></span>
												</div>
												<div class="sgrb-total-options-rows-rate-type">
													<p style="margin: 3px 0"><?php echo _e('Text color', 'sgrb');?>: </p>
													<span><input type="text" class="color-picker" /></span>
												</div>
											</div>
											<div class="sg-col-4">
												<div class="sgrb-total-options-rows-rate-type">
													<p><label for="sgrb-template-shadow-on"><input id="sgrb-template-shadow-on" type="checkbox"> <?php echo _e('Text boxes shadow effect', 'sgrb');?> </label></p>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><?php echo _e('Color', 'sgrb');?>: </div><div style="float:right;"><input type="text" class="color-picker" /></div>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><i class="sgrb-required-asterisk"> * </i><?php echo _e('To Left / Right (- / +)', 'sgrb');?>: </div><div class="sgrb-option-input-side"><input name="shadow-left-right" class="sgrb-template-shadow-directions" type="text" value="<?=@$sgrbDataArray['shadow-left-right']?>"/> - px</div>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><i class="sgrb-required-asterisk"> * </i><?php echo _e('To Top / Bottom (- / +)', 'sgrb');?>: </div><div class="sgrb-option-input-side"><input name="shadow-top-bottom" class="sgrb-template-shadow-directions" type="text" value="<?=@$sgrbDataArray['shadow-top-bottom']?>"/> - px</div>
												</div>
												<div class="sgrb-single-option">
													<div class="sgrb-option-title-side"><?php echo _e('Blur effect', 'sgrb');?>:</div><div class="sgrb-option-input-side"><input class="sgrb-template-shadow-directions" type="text"/> - px</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endif ;?>
						</div>
					</div>

					<div class="sg-box sgrb-google-search-box">
						<div class="sg-box-title"><?php echo _e('Search options', 'sgrb');?></div>
						<div class="sg-box-content">
							<?php if (SGRB_PRO_VERSION == 1) :?>
								<?php require_once('googleSearchPreviewOptions.php');?>
							<?php else :?>
								<div style='position: relative;'>
									<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="Upgrade to PRO version" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
									<div class="sg-row">
										<div class="sg-col-12">
											<label>
												<input type="checkbox"><?php echo _e('Show Your review in Google search', 'sgrb');?>
											</label>
										</div>
									</div>
									<div class="sg-row">
									<div class="sg-col-12">
										<div class="sgrb-google-search-preview">
											<div class="sgrb-google-box-wrapper">
												<div class="sgrb-google-box-title">Your review title</div>
												<div class="sgrb-google-box-url">www.your-web-page.com/your-review-site/...</div>
												<div class="sgrb-google-box-image-votes"><img width="70px" height="20px" src="<?php echo $sgrb->app_url.'/assets/page/img/google_search_preview.png';?>"><span>Rating - 5 - 305 votes</span></div>
												<div class="sgrb-google-box-description"><span>Your description text, if description field in Your selected template not exist, then there will be another field's text, e.g. title,subtitle ...</span></div>
											</div>
										</div>
									</div>
								</div>
								</div>

							<?php endif;?>
						</div>
					</div>

				</div>

				<div class="sg-col-4">
					<div class="sg-box">
						<div class="sg-box-title"><?php echo _e('Options', 'sgrb');?></div>
						<div class="sg-box-content">
						<?php if (SGRB_PRO_VERSION == 0) :?>
							<div style="position:relative;">
								<div class="sgrb-version"><input type="button" class="sgrb-upgrade-button" value="PRO" onclick="window.open('<?php echo SGRB_PRO_URL;?>')"></div>
								<div class="sgrb-require-options-fields">
									<div class="sg-row">
										<div class="sg-col-4">
											<span class="sgrb-comments-count-options"><?php echo _e('Comments to show:', 'sgrb');?></span>
										</div>
										<div class="sg-col-2">
											<input class="sgrb-comments-count-to-show" value="10" type="text">
										</div>
									</div>
									<div class="sg-row">
										<div class="sg-col-4">
											<span class="sgrb-comments-count-options"><?php echo _e('Comments to load:', 'sgrb');?></span>
										</div>
										<div class="sg-col-2">
											<input class="sgrb-comments-count-to-load" value="3" type="text">
										</div>
									</div>
								</div>
								<div class="sgrb-require-options-fields">
									<p><label for=""><input id="" class="sgrb-email-notification-checkbox" value="true" type="checkbox"><?php echo _e('Notify for new comments to this email:', 'sgrb');?></label>
									<input class="sgrb-email-notification"></p>
								</div>
							</div>
						<?php elseif (SGRB_PRO_VERSION == 1) :?>
							<div class="sgrb-require-options-fields">
								<div class="sg-row">
									<div class="sg-col-4">
										<span class="sgrb-comments-count-options"><?php echo _e('Comments to show:', 'sgrb');?></span>
									</div>
									<div class="sg-col-2">
										<input class="sgrb-comments-count-to-show" name="comments-count-to-show" value="<?php echo (@$sgrbDataArray['comments-count-to-show']) ? @$sgrbDataArray['comments-count-to-show'] : 10;?>" type="text">
									</div>
								</div>
								<div class="sg-row">
									<div class="sg-col-4">
										<span class="sgrb-comments-count-options"><?php echo _e('Comments to load:', 'sgrb');?></span>
									</div>
									<div class="sg-col-2">
										<input class="sgrb-comments-count-to-load" name="comments-count-to-load" value="<?php echo (@$sgrbDataArray['comments-count-to-load']) ? @$sgrbDataArray['comments-count-to-load'] : 3;?>" type="text">
									</div>
								</div>
							</div>
							<div class="sgrb-require-options-fields">
								<p><label for="sgrb-email-checkbox"><input id="sgrb-email-checkbox" class="sgrb-email-notification-checkbox sgrb-email-hide-show-js" value="true" type="checkbox" name="email-notification-checkbox"<?php echo (@$sgrbDataArray['notify']) ? ' checked' : '';?><?php echo (@$sgrbRevId != 0) ? '' : ' checked';?>><?php echo _e('Notify for new comments to this email:', 'sgrb');?></label>
								<input class="sgrb-email-notification" type="email" value="<?php echo (@$sgrbRevId != 0) ? @$sgrbDataArray['notify'] : get_option('admin_email');?>" name="email-notification"></p>
								<input class="sgrb-admin-email" type="hidden" value="<?php echo get_option('admin_email') ;?>">
							</div>
						<?php endif ; ?>
							<div class="sgrb-require-options-fields">
								<p><label for="sgrb-required-title-checkbox"><input id="sgrb-required-title-checkbox" class="sgrb-email-notification-checkbox" value="true" type="checkbox" name="required-title-checkbox"<?php echo (@$sgrbDataArray['required-title-checkbox']) ? ' checked' : '';?><?php echo (@$sgrbRevId != 0) ? '' : ' checked';?>><?php echo _e('Title required', 'sgrb');?></label>
								<p><label for="sgrb-required-email-checkbox"><input id="sgrb-required-email-checkbox" class="sgrb-email-notification-checkbox" value="true" type="checkbox" name="required-email-checkbox"<?php echo (@$sgrbDataArray['required-email-checkbox']) ? ' checked' : '';?><?php echo (@$sgrbRevId != 0) ? '' : ' checked';?>><?php echo _e('Email required', 'sgrb');?></label>
							</div>
							<div class="sgrb-require-options-fields">
								<p><label for="sgrb-auto-approve-checkbox"><input id="sgrb-auto-approve-checkbox" class="sgrb-auto-approve-checkbox" value="true" type="checkbox" name="auto-approve-checkbox"<?php echo (@$sgrbDataArray['auto-approve-checkbox']) ? ' checked' : '';?>><?php echo _e('Auto approve comments', 'sgrb');?></label>
							</div>
							<div class="sgrb-require-options-fields sgrb-categories-title"<?php echo (@$sgrbRevId != 0) ? ' style="margin-bottom:30px;"' : '';?>>
								<p><b><?php echo _e('Categories to rate', 'sgrb');?>: </b><a class="button-primary sgrb-add-field"<?php echo (@$sgrbRevId != 0) ? ' style="pointer-events:none;" disabled' : '';?>><span class="dashicons dashicons-plus-alt button-icon"></span> Add new</a></p>
								<i class="sgrb-category-empty-warning"> <?php echo (@$sgrbRevId != 0) ? '' : _e('At least one category is required', 'sgrb');?></i>
							</div>
							<div class="sgrb-field-container">
							<input type="hidden" class="sgrbSaveUrl" value="<?php echo esc_attr($sgrbSaveUrl);?>">
								<?php if (empty($fields)) :?>
									<div class="sgrb-one-field" id="clone_1">
									<div class="sgrb-require-options-fields">
										<input class="sgrb-fieldId" name="fieldId[]" type="hidden" value="0">
										<p>
											<input name="field-name[]" type="text" value="" placeholder="<?php echo _e('Category name', 'sgrb');?>" class="sgrb-field-name">
											<a class="button-secondary sgrb-remove-button" onclick="SGRB.remove('clone_1')"><span class="sgrb-dashicon dashicons dashicons-trash button-icon"></span> Remove</a>
										</p>
										<input type="hidden" class="fake-sgrb-id" name="fake-id[]" value="66">
									</div>
								</div>
								<?php else :?>
								<?php $i = 1;?>
								<?php foreach (@$fields as $field) : ?>
								<div class="sgrb-one-field" id="sgrb_<?php echo @$field->getId();?>">
									<div>
										<input class="sgrb-fieldId" name="fieldId[]" type="hidden" value="<?php echo esc_attr(@$field->getId());?>">
									<p>
										<input<?php echo (@$sgrbRevId != 0) ? ' readonly' : '';?> name="field-name[]" type="text" value="<?php echo esc_attr(@$field->getName());?>"placeholder="<?php echo _e('Category name', 'sgrb');?>" class="sgrb-border sgrb-field-name">
										<a class="button-secondary sgrb-remove-button" onclick="SGRB.remove(<?php echo esc_attr(@$field->getId());?>)"><span class="sgrb-dashicon dashicons dashicons-trash button-icon"></span> Remove</a>
									</p>
										<input type="hidden" class="fake-sgrb-id" name="fake-id[]" value="<?php echo $i++;?>">
									</div>
								</div>
								<?php endforeach;?>
								<?php endif;?>
							</div>

						</div>
					</div>
				</div>

			</div>

		</div>
	</form>
</div>
<input type="hidden" class="sgrb-is-pro" value="<?php echo SGRB_PRO_VERSION;?>">
<div id="sgrb-template" title="Select template">
	<?php
	foreach($allTemplates as $template):
		$isChecked = ($template->getName() == @$sgrbDataArray['template']) ? ' checked' : '';
		$proHtml = '<div class="ribbon-wrapper" style="position:relative;display:block;"><div class="sgrb-ribbon"><div><a target="_blank" href="'.SGRB_PRO_URL.'">PRO</a></div></div></div>';
		if($template->getSgrb_pro_version()==1) $proHtml='';
	?>
	<label class="sgrb-template-label">
		<?php if($template):?>
		<input type="radio" class="sgrb-radio" name="sgrb-template-radio" value="<?php echo $template->getName()?>"<?php echo esc_attr($isChecked);?>>
		<?php endif?>
		<?php echo $proHtml; ?>
		<?php if (!$template->getImg_url()):?>
			<div class="sgrb-custom-template-hilghlighting" style="position:absolute;color:#3F3F3F;margin-left:10px;z-index:9"><b><?=$template->getName()?></b></div>
			<img width="200px" src="<?php echo $sgrb->app_url.'assets/page/img/custom_template.jpeg'; ?>">
		<?php else:?>
			<img class="sgrb-default-template-js" width="200px" src="<?php echo $template->getImg_url(); ?>">
		<?php endif;?>
	</label>
	<?php endforeach; ?>
</div>
