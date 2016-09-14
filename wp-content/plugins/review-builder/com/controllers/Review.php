<?php
global $sgrb;
$sgrb->includeController('Controller');
$sgrb->includeCore('Template');
$sgrb->includeView('Admin');
$sgrb->includeView('Review');
$sgrb->includeView('TemplateDesign');
$sgrb->includeModel('TemplateDesign');
$sgrb->includeModel('Review');
$sgrb->includeModel('Comment');
$sgrb->includeModel('Template');
$sgrb->includeModel('Category');
$sgrb->includeModel('Comment_Rating');
$sgrb->includeModel('Rate_Log');

class SGRB_ReviewController extends SGRB_Controller
{
	public function index()
	{
		global $sgrb;
		$sgrb->includeScript('page/scripts/save');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$review = new SGRB_ReviewReviewView();
		$createNewUrl = $sgrb->adminUrl('Review/save');

		SGRB_AdminView::render('Review/index', array(
			'createNewUrl' => $createNewUrl,
			'review' => $review
		));
	}

	public function sgrbShortcode($atts, $content)
	{
		global $sgrb;

		$attributes = shortcode_atts(array(
			'id' => '1',
		), $atts);
		$sgrbId = (int)$attributes['id'];
		$sgrbRev = SGRB_ReviewModel::finder()->findByPk($sgrbId);
		if(!$sgrbRev){
			return;
		}
		$arr = array();
		$title = $sgrbRev->getTitle();
		$templateId = $sgrbRev->getTemplate_id();
		$options = $sgrbRev->getOptions();
		$template = SGRB_TemplateModel::finder()->findByPk($templateId);

		$arr['title'] = $title;
		$arr['id'] = $sgrbId;
		$arr['template-id'] = $templateId;
		$arr['options'] = json_decode($options,true);
		$arr['template'] = $template;
		$sgrbDataArray[] = $arr;

		$html = $this->createReviewHtml($sgrbDataArray);
		return $html;
	}


	public function ajaxSave()
	{
		global $wpdb;
		global $sgrb;
		$sgrb->includeCore('Template');

		$tempOptions = array();
		$options = array();
		$reviewId = 0;
		$isUpdate = false;
		$rateTypeNotice = @$_POST['rate-type-notice'];
		$templateImgArr = @$_POST['image_url'];
		$templateTextArr = @$_POST['input_html'];
		$templateUrlArr = @$_POST['input_url'];
		$tempName = @$_POST['sgrb-template'];
		$title = @$_POST['sgrb-title'];
		$tempOptions['images'] = $templateImgArr;
		$tempOptions['html'] = $templateTextArr;
		$tempOptions['url'] = $templateUrlArr;
		$tempOptions['name'] = $tempName;

		if (count($_POST)) {
			$reviewId = (int)$_POST['sgrb-id'];

			$review = new SGRB_ReviewModel();
			$isUpdate = false;

			if ($reviewId) {
				$isUpdate = true;
				$reviewId = (int)$_POST['sgrb-id'];
				$review = SGRB_ReviewModel::finder()->findByPk($reviewId);
				if (!$review) {
					return;
				}
			}

			////////////////////////////

			$tempId = $review->getTemplate_id();

			if ($tempId) {
				$template = new Template($tempName,$tempId);
			}
			else {
				$template = new Template($tempName);
			}

			$result = $template->save($tempOptions);

			/////////////////////////////

			$tempateShadowColor = '';
			$shadowLeftRight = '';
			$shadowTopBottom = '';
			$shadowBlur = '';
			$fields = @$_POST['field-name'];
			$fieldId = @$_POST['fieldId'];
			$title = @$_POST['sgrb-title'];
			$ratingType = @$_POST['rate-type'];
			$totalRateBackgroundColor = @$_POST['total-rate-background-color'];
			$skinColor = @$_POST['skin-color'];
			$totalRate = @$_POST['totalRate'];
			$showComments = @$_POST['showComments'];
			$captchaOn = @$_POST['captchaOn'];
			$rateTextColor = @$_POST['rate-text-color'];
			$emailNotificationCheckbox = isset($_POST['email-notification-checkbox']);
			$requiredEmailCheckbox = isset($_POST['required-email-checkbox']);
			$requiredTitleCheckbox = isset($_POST['required-title-checkbox']);
			$autoApprove = isset($_POST['auto-approve-checkbox']);
			$shadowOn = isset($_POST['template-field-shadow-on']);
			$googleSearch = isset($_POST['sgrb-google-search-on']);
			$emailNotification = @$_POST['email-notification'];
			$font = @$_POST['fontSelectbox'];
			$tempateBackgroundColor = @$_POST['template-background-color'];
			$tempateTextColor = @$_POST['template-text-color'];
			$tempateShadowColor = @$_POST['template-shadow-color'];
			$shadowLeftRight = @$_POST['shadow-left-right'];
			$shadowTopBottom = @$_POST['shadow-top-bottom'];
			$shadowBlur = @$_POST['shadow-blur'];
			$postCategory = @$_POST['post-category'];
			$disableWPcomments = @$_POST['disableWPcomments'];
			$commentsCount = (int)@$_POST['comments-count-to-show'];
			$commentsCountLoad = (int)@$_POST['comments-count-to-load'];

			$options['notify'] = '';
			$options['required-title-checkbox'] = '';
			$options['required-email-checkbox'] = '';
			$options['auto-approve-checkbox'] = '';
			$options['template-field-shadow-on'] = '';
			$options['sgrb-google-search-on'] = '';
			$options['disableWPcomments'] = '';
			$options['comments-count-to-show'] = '';
			$options['comments-count-to-load'] = '';
			$options['captcha-on'] = '';
			if (SGRB_PRO_VERSION) {
				if ($commentsCount) {
					$options['comments-count-to-show'] = $commentsCount;
				}
				if ($commentsCountLoad) {
					$options['comments-count-to-load'] = $commentsCountLoad;
				}
				if ($emailNotificationCheckbox) {
					$options['notify'] = sanitize_text_field($emailNotification);
				}
				if ($shadowOn) {
					if ($shadowLeftRight && $shadowTopBottom) {
						$options['template-field-shadow-on'] = 1;
						$options['shadow-left-right'] = $shadowLeftRight;
						$options['shadow-top-bottom'] = $shadowTopBottom;
						$options['template-shadow-color'] = $tempateShadowColor;
						$options['shadow-blur'] = $shadowBlur;
					}
				}
				if ($googleSearch) {
					$options['sgrb-google-search-on'] = 1;
				}
				if ($captchaOn) {
					$options['captcha-on'] = $captchaOn;
				}
			}
			if ($disableWPcomments) {
				$options['disableWPcomments'] = 1;
			}
			if ($postCategory && $tempOptions['name'] == 'post_review') {
				$options['post-category'] = $postCategory;
			}
			if ($requiredTitleCheckbox) {
				$options['required-title-checkbox'] = 1;
			}
			if ($requiredEmailCheckbox) {
				$options['required-email-checkbox'] = 1;
			}
			if ($autoApprove) {
				$options['auto-approve-checkbox'] = 1;
			}

			$options['total-rate'] = $totalRate;
			$options['show-comments'] = $showComments;
			$options['total-rate-background-color'] = $totalRateBackgroundColor;
			$options['rate-type'] = $ratingType;
			$options['template-font'] = $font;
			$options['template-background-color'] = $tempateBackgroundColor;
			$options['template-text-color'] = $tempateTextColor;
			if ($rateTypeNotice != $ratingType) {
				SGRB_CommentModel::finder()->deleteAll('review_id = %d', $reviewId);
				SGRB_Rate_LogModel::finder()->deleteAll('review_id = %d', $reviewId);
			}
			$options['skin-color'] = $skinColor;
			$options['rate-text-color'] = $rateTextColor;

			$options = json_encode($options);

			$review->setTitle(sanitize_text_field($title));
			$review->setTemplate_id(sanitize_text_field($result));
			$review->setOptions(sanitize_text_field($options));

			if (!$fields[0]) {
				exit();
			}

			$review->save();

			$lastRevId = $wpdb->insert_id;

			// if is db update, not insert

			if (!$lastRevId && $reviewId) {
				$lastRevId = $review->getId();
			}

			if (!$isUpdate) {
				for ($i=0;$i<count($fields);$i++) {
					$categories = new SGRB_CategoryModel();
					$categories->setReview_id(sanitize_text_field($lastRevId));
					$categories->setName(sanitize_text_field($fields[$i]));
					$categories->save();

				}
			}

		}
		echo $lastRevId;
		exit();
	}

	public function save()
	{
		global $wpdb;
		global $sgrb;

		$sgrb->includeScript('core/scripts/jquery.rateyo');
		$sgrb->includeScript('core/scripts/jquery.barrating');
		$sgrb->includeScript('core/scripts/jquery-ui.min');
		$sgrb->includeScript('core/scripts/jquery-ui-slider-pips.min');
		$sgrb->includeScript('page/scripts/save');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$sgrb->includeStyle('core/styles/css/jquery.rateyo');
		$sgrb->includeStyle('core/styles/css/bars-1to10');
		$sgrb->includeStyle('core/styles/css/jquery-ui.min');
		$sgrb->includeStyle('core/styles/css/jquery-ui-slider-pips.min');
		$sgrb->includeStyle('page/styles/save');
		$sgrb->includeStyle('page/styles/sg-box-cols');
		if (SGRB_PRO_VERSION) {
			$sgrb->includeStyle('page/styles/bootstrap-formhelpers.min');
			$sgrb->includeScript('page/scripts/bootstrap-formhelpers.min');
		}

		$sgrbId = 0;
		$sgrbDataArray = array();
		$sgrbOptions = array();
		$fields = array();
		$ratings = array();
		$allTemplates = array();
		$tempView = new SGRB_TemplateDesignView();
		$allTemplates = SGRB_TemplateDesignModel::finder()->findAllBySql("SELECT * from ".$tempView->getTablename()."  ORDER BY ".$tempView->getTablename().".sgrb_pro_version DESC");

		isset($_GET['id']) ? $sgrbId = (int)$_GET['id'] : 0;
		$sgrbRev = SGRB_ReviewModel::finder()->findByPk($sgrbId);
		$allCommentsUrl = $sgrb->adminUrl('Comment/index','id='.$sgrbId);
		$sgrbSaveUrl = $sgrb->adminUrl('Review/save');
		//If edit
		if ($sgrbRev) {

			$sgrbDataArray = array();

			$fields = SGRB_CategoryModel::finder()->findAll('review_id = %d', $sgrbId);
			$sgrbOptions = $sgrbRev->getOptions();
			$sgrbOptions = json_decode($sgrbOptions, true);
			if (!$sgrbRev) {
				$sgrbRev = new SGRB_ReviewModel();
				//die('Error, review not found');
			}
			$tempId = $sgrbRev->getTemplate_id();
			$template = SGRB_TemplateModel::finder()->findByPk($tempId);
			if (!$tempId) {
				$template = new SGRB_TemplateModel();
			}
			$tempName = $template->getName();
			// if template not found 'tempo' as default
			if (!$tempName) {
				$tempName = 'full_width';
			}
			$temp = new Template($tempName,$tempId);
			$res = $temp->adminRender();

			$title = $sgrbRev->getTitle();
			$template = $sgrbRev->getTemplate_id();

			$options = $sgrbRev->getOptions();
			$options = json_decode($options, true);

			$sgrbDataArray['title'] = $title;
			$sgrbDataArray['notify'] = @$options["notify"];
			$sgrbDataArray['required-title-checkbox'] = @$options["required-title-checkbox"];
			$sgrbDataArray['required-email-checkbox'] = @$options["required-email-checkbox"];
			$sgrbDataArray['auto-approve-checkbox'] = @$options["auto-approve-checkbox"];
			$sgrbDataArray['sgrb-google-search-on'] = @$options["sgrb-google-search-on"];
			$sgrbDataArray['total-rate'] = @$options["total-rate"];
			$sgrbDataArray['show-comments'] = @$options["show-comments"];
			$sgrbDataArray['captcha-on'] = @$options["captcha-on"];
			$sgrbDataArray['total-rate-background-color'] = @$options["total-rate-background-color"];
			$sgrbDataArray['rate-type'] = @$options["rate-type"];
			$sgrbDataArray['skin-color'] = @$options["skin-color"];
			$sgrbDataArray['rate-text-color'] = @$options["rate-text-color"];
			$sgrbDataArray['template-font'] = @$options["template-font"];
			$sgrbDataArray['template-background-color'] = @$options["template-background-color"];
			$sgrbDataArray['template-text-color'] = @$options["template-text-color"];
			$sgrbDataArray['post-category'] = @$options["post-category"];
			$sgrbDataArray['disableWPcomments'] = @$options["disableWPcomments"];
			$sgrbDataArray['comments-count-to-show'] = @$options["comments-count-to-show"];
			$sgrbDataArray['comments-count-to-load'] = @$options["comments-count-to-load"];

			if (@$options['template-field-shadow-on']) {
				$sgrbDataArray['template-field-shadow-on'] = @$options['template-field-shadow-on'];
				$sgrbDataArray['shadow-left-right'] = @$options["shadow-left-right"];
				$sgrbDataArray['shadow-top-bottom'] = @$options["shadow-top-bottom"];
				$sgrbDataArray['template-shadow-color'] = @$options["template-shadow-color"];
				$sgrbDataArray['shadow-blur'] = @$options["shadow-blur"];
			}

			$selectedTemplate = SGRB_TemplateModel::finder()->findByPk($template);
			$sgrbDataArray['template'] = $selectedTemplate->getName();
		}
		else {
			$sgrbRev = new SGRB_ReviewModel();
			$sgrbId = 0;
			$temp = new Template('full_width');
			$res = $temp->adminRender();
		}
		SGRB_AdminView::render('Review/save', array(
			'sgrbDataArray' => $sgrbDataArray,
			'sgrbSaveUrl' => $sgrbSaveUrl,
			'sgrbRevId' => $sgrbId,
			'sgrbRev' => $sgrbRev,
			'fields' => $fields,
			'ratings' => $ratings,
			'allCommentsUrl' => $allCommentsUrl,
			'res' => $res,
			'allTemplates' => $allTemplates
		));
	}

	public function morePlugins()
	{
		global $sgrb;
		$sgrb->includeStyle('page/styles/save');
		$sgrb->includeStyle('page/styles/sg-box-cols');
		SGRB_AdminView::render('Review/morePlugins');
	}

	public function ajaxCloseBanner()
	{
		delete_option(SG_REVIEW_BANNER);
		exit();
	}

	// delete review
	public function ajaxDelete()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		$deletedReview = SGRB_ReviewModel::finder()->findByPk($id);
		SGRB_CategoryModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_TemplateModel::finder()->deleteByPk($deletedReview->getTemplate_id());
		SGRB_CategoryModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_CommentModel::finder()->deleteAll('review_id = %d', $id);
		SGRB_ReviewModel::finder()->deleteByPk($id);
		exit();
	}

	// delete review field
	public function ajaxDeleteField()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		SGRB_CategoryModel::finder()->deleteByPk($id);
		SGRB_Comment_RatingModel::finder()->deleteAll('category_id = %d', $id);
		exit();
	}

	public function createWidgetReviewHtml ($review)
	{
		$arr = array();
		$html = '';
		if ($review) {
			$title = $review->getTitle();
			$templateId = $review->getTemplate_id();
			$options = $review->getOptions();
			$template = SGRB_TemplateModel::finder()->findByPk($templateId);
			$templateOptions = $template->getOptions();
			$templateOptions = json_decode($templateOptions, true);

			$arr['title'] = $title;
			$arr['id'] = $review->getId();
			$arr['template-id'] = $templateId;
			$arr['options'] = json_decode($options,true);
			$arr['template'] = $template;
			$arr['widget-image'] = $templateOptions['images'][0];
			$sgrbDataArray[] = $arr;
			$html .= $this->createReviewHtml($sgrbDataArray, true);
		}

		return $html;
	}

	public function createPostReviewHtml ($review)
	{
		$arr = array();
		$title = $review->getTitle();
		$templateId = $review->getTemplate_id();
		$options = $review->getOptions();
		$template = SGRB_TemplateModel::finder()->findByPk($templateId);

		$arr['title'] = $title;
		$arr['id'] = $review->getId();
		$arr['template-id'] = $templateId;
		$arr['options'] = json_decode($options,true);
		$arr['template'] = $template;
		$sgrbDataArray[] = $arr;

		$html = $this->createReviewHtml($sgrbDataArray);
		return $html;
	}

	// create all review front
	private function createReviewHtml($review, $isWidget=false)
	{
		global $sgrb;
		if (SGRB_PRO_VERSION) {
			$sgrb->includeStyle('page/styles/bootstrap-formhelpers.min');
			$sgrb->includeScript('page/scripts/bootstrap-formhelpers.min');
		}
		$sgrb->includeStyle('core/styles/css/main-front');
		$sgrb->includeStyle('page/styles/sg-box-cols');
		// SGRB.php 431 and 448 line
		//$sgrb->includeStyle('page/styles/save');
		$sgrb->includeScript('page/scripts/save');
		$sgrb->includeScript('core/scripts/supportFunctions');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/jquery.cookie');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');

		$commentRatingModel = new SGRB_Comment_RatingModel();
		$commentTablename = $sgrb->tablename($commentRatingModel::TABLE);

		$html = '';
		$commentForm = '';
		$ratedHtml = false;

		$mainTemplate = $review[0]['template'];

		$template = new Template($mainTemplate->getName(),$mainTemplate->getId());
		$result = $template->render();

		$categories = SGRB_CategoryModel::finder()->findAll('review_id = %d', $review[0]['id']);
		$ratesArray = array();
		$eachRatesArray = array();
		if (!$review[0]['options']['total-rate-background-color']) {
			$review[0]['options']['total-rate-background-color'] = '#f7f7f7';
		}

		if (!$review[0]['options']['rate-text-color']) {
			$review[0]['options']['rate-text-color'] = '#4c4c4c';
		}

		$postId = '';
		$sgrbWidgetWrapper = 'sgrb-common-wrapper';
		$closeHtml = '';
		$eachCategoryHide = '';
		$isPostReview = false;
		if (is_singular('post') && !is_page()) {
			$currentPost = get_post();
			$postId = get_post()->ID;
			$isPostReview = true;
			$result = '<div class="sg-template-wrapper"></div>';
			$closeHtml = '</div></div></div>';
		}
		else if ($isWidget) {
			$result = '<div class="sg-template-wrapper"><img src="'.$review[0]["widget-image"].'" width="280" height="210"></div>';
			$eachCategoryHide = 'style="display:none;"';
			$sgrbWidgetWrapper = 'sgrb-widget-wrapper';
		}

		if ($review[0]['options']['total-rate'] == 1 || SGRB_PRO_VERSION) {
			$countRates = 0;
			foreach ($categories as $category) {
				if ($isPostReview) {
					$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d && post_id = %d', array($review[0]['id'], 1, $postId));
				}
				else {
					$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d', array($review[0]['id'], 1));
				}
				$sgrbIndex = 0;
				foreach ($approvedComments as $approvedComment) {
					$sgrbIndex++;
					$rates = SGRB_Comment_RatingModel::finder()->findAll('category_id = %d && comment_id = %d', array($category->getId(), $approvedComment->getId()));
					$eachRates = SGRB_Comment_RatingModel::finder()->findBySql('SELECT AVG(rate) AS average, category_id FROM '.$commentTablename.' WHERE category_id='.$category->getId().' GROUP BY category_id');
					$ratesArray[] = $rates;
					$eachRatesArray[$category->getId()][] = $eachRates;
				}
			}
			$countRates = 0;
			$rating = 0;

			foreach ($ratesArray as $rate) {
				$countRates += 1;
				$rating += $rate[0]->getRate();
			}
			if (!$countRates) {
				$totalRate = 0;
			}
			else {
				$totalRate = round($rating / $countRates);
			}

		}

		$commentsArray = array();
		if ($isPostReview) {
			$userIps = SGRB_Rate_LogModel::finder()->findAll('review_id = %d && post_id = %d', array($review[0]['id'], $postId));
		}
		$userIps = SGRB_Rate_LogModel::finder()->findAll('review_id = %d', $review[0]['id']);
		foreach ($categories as $category) {
			if ($isPostReview) {
				$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d && post_id = %d', array($review[0]['id'], 1, $postId));
			}
			else {
				$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d', array($review[0]['id'], 1));
			}

			$commentsArray = $approvedComments;
		}

		$allApprovedComments = '<div class="sgrb-approved-comments-to-show">';
		$allApprovedComments .= '</div>';
		if (!@$review[0]['options']['comments-count-to-show']) {
			@$review[0]['options']['comments-count-to-show'] = SGRB_COMMENTS_PER_PAGE;
		}
		if (!@$review[0]['options']['comments-count-to-load']) {
			!@$review[0]['options']['comments-count-to-load'] = 3;
		}
		if (!@$review[0]['options']['show-comments']) {
			$allApprovedComments = '';
		}
		if ($commentsArray && @$review[0]['options']['show-comments'] == 1) {
			$allApprovedComments .= '<div class="sgrb-pagination" style="background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';">';
			$allApprovedComments .= '<input class="sgrb-comments-per-page" type="hidden" value="'.@$review[0]['options']['comments-count-to-show'].'">';
			$perPage = @$review[0]['options']['comments-count-to-show'];
			$tmp = ceil(count($commentsArray)/SGRB_COMMENTS_PER_PAGE);
			$allApprovedComments .= '<input class="sgrb-page-count" type="hidden" value="'.$tmp.'">';
			$allApprovedComments .= '<input class="sgrb-comments-count" type="hidden" value="'.@$review[0]['options']['comments-count-to-show'].'">';
			$allApprovedComments .= '<input class="sgrb-comments-count-load" type="hidden" value="'.@$review[0]['options']['comments-count-to-load'].'">';
			$allApprovedComments .= '<input class="sgrb-post-id" type="hidden" value="'.$postId.'">';
			$allApprovedComments .= '<i class="sgrb-loading-spinner"><img src='.$sgrb->app_url.'/assets/page/img/comment-loader.gif></i>';
			$allApprovedComments .= '<a class="sgrb-comment-load" href="javascript:void(0)">Load more</a>';
			$allApprovedComments .= '</div>';
		}

		$commentFieldAsterisk = '<i class="sgrb-comment-form-asterisk">*</i>';
		$titleRequiredAsterisk = '';
		$emailRequiredAsterisk = '';
		if (@$review[0]['options']['required-title-checkbox']) {
			$titleRequiredAsterisk = $commentFieldAsterisk;
		}
		if (@$review[0]['options']['required-email-checkbox']) {
			$emailRequiredAsterisk = $commentFieldAsterisk;
		}

		$captchaHtml = '';
		if (SGRB_PRO_VERSION && @$review[0]['options']['captcha-on'] && !$isWidget) {
			@session_start();
			require_once($sgrb->app_path.'/com/lib/captcha/simple-php-captcha.php');
			$_SESSION['sgrb-captcha'] = simple_php_captcha();
			$captchaHtml = '<div class="sgrb-captcha-wrapper" style="width: 20%;font-size: 14px;padding: 10px 0px;overflow: hidden;">
							<div class="sgrb-captcha-notice"><span class="sgrb-captcha-notice-text"></span></div>
								<img src="'.$_SESSION['sgrb-captcha']['image_src'].'"/>
								<input type="hidden" name="captchaCode" value="'.$_SESSION['sgrb-captcha']['code'].'">
								<span style="float:left;">Type here: </span>
								<input class="sgrb-add-title" name="addCaptcha" type="text" style="width: 89px;float: left;">
							</div>';
		}

		$commentForm = '<div class="sgrb-user-comment-wrapper" style="background-color: '.@$review[0]['options']['total-rate-background-color'].';color: '.$review[0]['options']['rate-text-color'].';">
							<div class="sgrb-notice"><span class="sgrb-notice-text"></span></div>
							<div class="sgrb-hide-show-wrapper">
								<div class="sgrb-front-comment-rows">
									<span class="sgrb-comment-title">Your name </span>'.$commentFieldAsterisk.'
									<input class="sgrb-add-fname" name="addName" type="text" placeholder="name">
								</div>
								<div class="sgrb-front-comment-rows">
									<span class="sgrb-comment-title">Email </span>'.$emailRequiredAsterisk.'
									<input class="sgrb-add-email" name="addEmail" type="email" placeholder="email">
								</div>
								<div class="sgrb-front-comment-rows">
									<span class="sgrb-comment-title">Title </span>'.$titleRequiredAsterisk.'
									<input class="sgrb-add-title" name="addTitle" type="text" placeholder="title">
								</div>
								<div class="sgrb-front-comment-rows">
									<span class="sgrb-comment-title">Comment </span>'.$commentFieldAsterisk.'
									<textarea class="sgrb-add-comment" name="addComment" placeholder="Your comment here"></textarea>'.$captchaHtml.'
								</div>
								<input name="addPostId" type="hidden" value="'.$postId.'">
								<div class="sgrb-post-comment-button">
									<input type="button" value="Post Comment" onclick="SGRB.prototype.ajaxUserRate()" class="sgrb-user-comment-submit" style="background-color: '.$review[0]['options']['total-rate-background-color'].';color: '.$review[0]['options']['rate-text-color'].';">
								</div>
							</div>
						</div>';

		$eachCategoryRate = false;
		foreach ($userIps as $userIp) {
			if (@$_SERVER['REMOTE_ADDR'] == $userIp->getIp() || isset($_COOKIE['rater'])) {
				$eachCategoryRate = true;
				if ($isPostReview) {
					if ($userIp->getPost_id() == $postId) {
						$commentForm = '';
						$ratedHtml = true;
					}
				}
				else {
					$commentForm = '';
					$ratedHtml = true;
				}
			}
		}

		if ($isWidget) {
			$commentForm = '';
			$ratedHtml = true;
		}

		$mainCommentsCount = count($commentsArray);
		$sgrbWidgetTooltip = '';
		if ($isWidget) {
			$sgrbWidgetTooltip = '-widget';
		}
		$sgrbSearchCommentsCount = '';
		if (!$commentsArray) {
			$allApprovedComments = '';
			$mainCommentsCount = '<div class="sgrb-tooltip'.$sgrbWidgetTooltip.'"><span class="sgrb-tooltip'.$sgrbWidgetTooltip.'-text">no rates</span></div>';
		}
		else {
			if ($mainCommentsCount == 1) {
				$sgrbSearchCommentsCount = $mainCommentsCount;
				$mainCommentsCount = '<div class="sgrb-tooltip'.$sgrbWidgetTooltip.'"><span class="sgrb-tooltip'.$sgrbWidgetTooltip.'-text">'.$mainCommentsCount.' rate</span></div>';
			}
			else {
				$sgrbSearchCommentsCount = $mainCommentsCount;
				$mainCommentsCount = '<div class="sgrb-tooltip'.$sgrbWidgetTooltip.'"><span class="sgrb-tooltip'.$sgrbWidgetTooltip.'-text">'.$mainCommentsCount.' rates</span></div>';
			}
		}

// template options first column
		$templateStyles = '';

		if (@$review[0]['options']['template-background-color']) {
			$templateStyles .= 'background-color: '.@$review[0]["options"]["template-background-color"].';';
		}
		if (@$review[0]['options']['template-text-color']) {
			$templateStyles .= 'color: '.@$review[0]["options"]["template-text-color"].';';
		}
		if (@$review[0]['options']['template-font']) {
			$templateStyles .= 'font-family: '.@$review[0]["options"]["template-font"].';';
		}

		if ($templateStyles) $templateStyles = 'style="'.$templateStyles.'"';

		// template shadow options
		$templateShadow = '';

		if (@$review[0]['options']['template-field-shadow-on']) {
			if (@$review[0]["options"]["shadow-left-right"] && @$review[0]["options"]["shadow-top-bottom"]) {
				$templateShadow .= 'box-shadow: '.@$review[0]["options"]["shadow-left-right"].'px '.@$review[0]["options"]["shadow-top-bottom"].'px ';
				if (@$review[0]['options']['shadow-blur']) {
					$templateShadow .= @$review[0]['options']['shadow-blur'].'px ';
				}
				if (@$review[0]['options']['template-shadow-color']) {
					$templateShadow .= @$review[0]['options']['template-shadow-color'];
				}
			}
		}

		$html .= '<input class="sgrb-template-shadow-style" type="hidden" value="'.$templateShadow.'">';
		$html .= '<div class="sgrb-template-custom-style" '.$templateStyles.'>';

		$totalRateSymbol = '';
		if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_STAR) {
			$totalRateSymbol = '&#9733';
			$bestRating = 5;
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_PERCENT) {
			$totalRateSymbol = '%';
			$bestRating = 100;
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_POINT) {
			$totalRateSymbol = '&#8226';
			$bestRating = 10;
		}

		// show google search
		$googleSearchResult = '';
		if (SGRB_PRO_VERSION && @$review[0]['options']['sgrb-google-search-on']) {
			if ($totalRate) {
				$googleSearchResult .= '<div itemscope="" itemtype="http://schema.org/AggregateRating">
											<span style="display:none;" itemprop="itemreviewed">'.@$review[0]['title'].'</span>
											<meta content="'.@$totalRate.'" itemprop="ratingValue">
											<meta content="'.@$sgrbSearchCommentsCount.'" itemprop="ratingCount">
											<meta content="'.@$sgrbSearchCommentsCount.'" itemprop="reviewCount">
											<meta content="'.@$bestRating.'" itemprop="bestRating">
											<meta content="1" itemprop="worstRating"></div>';
			}
			else {
				$googleSearchResult .= '<div style="display:none;" itemscope="" itemtype="http://schema.org/AggregateRating">
											<span itemprop="itemreviewed">'.@$review[0]['title'].'</span>
											<meta content="0" itemprop="ratingValue">
											<meta content="0" itemprop="ratingCount">
											<meta content="0" itemprop="reviewCount">
											<meta content="'.@$bestRating.'" itemprop="bestRating">
											<meta content="1" itemprop="worstRating"></div>';
			}
		}

		/////////
		$html .= $googleSearchResult;
		$html .= $result;

		$html .= '<input value="'.esc_attr(@$review[0]['id']).'" type="hidden" class="sgrb-reviewId" name="reviewId">';

		$html .= '<input value="'.esc_attr(@$_SERVER['REMOTE_ADDR']).'" type="hidden" class="sgrb-cookie">';
		$html .= '<input value="'.$review[0]['options']['required-title-checkbox'].'" type="hidden" class="sgrb-requiredTitle">';
		$html .= '<input value="'.$review[0]['options']['required-email-checkbox'].'" type="hidden" class="sgrb-requiredEmail">';

		$html .= '<input class="sgrb-skin-color" type="hidden" value="'.esc_attr($review[0]['options']['skin-color']).'">';
		$html .= '<input class="sgrb-current-font" type="hidden" value="'.esc_attr($review[0]['options']['template-font']).'">';
		$html .= '<input class="sgrb-rating-type" type="hidden" value="'.esc_attr($review[0]['options']['rate-type']).'">';
		$html .= '<input class="sgrb-rate-text-color" type="hidden" value="'.esc_attr($review[0]['options']['rate-text-color']).'">';
		$html .= '<input class="sgrb-rate-background-color" type="hidden" value="'.esc_attr($review[0]['options']['total-rate-background-color']).'">';

		$totalRateHtml = '<div class="sgrb-total-rate-wrapper" style="background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';">
					<div class="sgrb-total-rate-title">
					<div class="sgrb-total-rate-title-text"><span>Total rating</span></div></div>
					<div class="sgrb-total-rate-count">'.$mainCommentsCount.'
					<div class="sgrb-total-rate-count-text sgrb-show-tooltip'.$sgrbWidgetTooltip.'" title=""><span>'.esc_attr(@$totalRate).$totalRateSymbol.'</span></div></div>';

		$mainStyles = '';

		if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_STAR) {
			$sgrb->includeScript('core/scripts/jquery.rateyo');
			$sgrb->includeStyle('core/styles/css/jquery.rateyo');

			if ($review[0]['options']['total-rate'] == 1) {
				$html .= $totalRateHtml;
			}
			else {
				if ($ratedHtml) {
					$categoryRowStyles = '';
				}
				else {
					$categoryRowStyles = 'background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';';
				}
				$html .= '<div class="sgrb-total-rate-wrapper" style="'.$categoryRowStyles.'">';
			}
			if ($categories) {
				foreach ($categories as $category) {
					if (strlen($category->getName()) > 31) {
						$text = substr($category->getName(), 0, 28).'...';
					}
					else {
						$text = $category->getName();
					}
					$html .= '<input class="sgrb-fieldId" name="field[]" type="hidden" value="'.esc_attr($category->getId()).'">';
					$html .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<input class="sgrb-each-rate-skin" name="rate[]" type="hidden" value="">
							<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					if ($eachCategoryRate && !empty($eachRatesArray) && !empty($eachRatesArray[$category->getId()][0])) {
						$eachCategoryRate = round($eachRatesArray[$category->getId()][0]->getAverage(), 1);
					}
					$html .= '<div class="sgrb-rate-each-skin-wrapper"><input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'"><div class="rateYo"></div><div class="sgrb-counter"></div></div></div>';
				}
			}
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_PERCENT) {
			$sgrb->includeScript('core/scripts/jquery-ui.min');
			$sgrb->includeScript('core/scripts/jquery-ui-slider-pips.min');
			$sgrb->includeStyle('core/styles/css/jquery-ui.min');
			$sgrb->includeStyle('core/styles/css/jquery-ui-slider-pips.min');

			if ($review[0]['options']['total-rate'] == 1) {
				$html .= $totalRateHtml;
			}
			else {
				if ($ratedHtml) {
					$categoryRowStyles = '';
				}
				else {
					$categoryRowStyles = 'background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';';
				}
				$html .= '<div class="sgrb-total-rate-wrapper" style="'.$categoryRowStyles.'">';
			}
			if ($categories) {
				foreach ($categories as $category) {
					if (strlen($category->getName()) > 31) {
						$text = substr($category->getName(), 0, 28).'...';
					}
					else {
						$text = $category->getName();
					}
					$html .= '<input class="sgrb-fieldId" name="field[]" type="hidden" value="'.esc_attr($category->getId()).'">';
					$html .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<input class="sgrb-each-rate-skin" name="rate[]" type="hidden" value="">
							<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					$mainStyles = '';
					if ($review[0]['options']['skin-color']) {
						$mainStyles .= 'background-color:'.esc_attr($review[0]['options']['skin-color']).';';
					}
					if ($mainStyles) $mainStyles = ' style="'.$mainStyles.'"';
					if ($eachCategoryRate && !empty($eachRatesArray) && !empty($eachRatesArray[$category->getId()][0])) {
						$eachCategoryRate = round($eachRatesArray[$category->getId()][0]->getAverage(), 1);
					}
					$html .= '<div class="sgrb-each-percent-skin-wrapper"><input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'"><div '.$mainStyles.' class="circles-slider"></div></div></div>';
				}
			}
		}
		else if ($review[0]['options']['rate-type'] == SGRB_RATE_TYPE_POINT) {
			$sgrb->includeScript('core/scripts/jquery.barrating');
			$sgrb->includeStyle('core/styles/css/bars-1to10');

			if ($review[0]['options']['total-rate'] == 1) {
				$html .= $totalRateHtml;
			}
			else {
				if ($ratedHtml) {
					$categoryRowStyles = '';
				}
				else {
					$categoryRowStyles = 'background-color:'.esc_attr($review[0]['options']['total-rate-background-color']).';color: '.$review[0]['options']['rate-text-color'].';';
				}
				$html .= '<div class="sgrb-total-rate-wrapper" style="'.$categoryRowStyles.'">';
			}
			if ($categories) {
				foreach ($categories as $category) {
					if (strlen($category->getName()) > 31) {
						$text = substr($category->getName(), 0, 28).'...';
					}
					else {
						$text = $category->getName();
					}
					$html .= '<div class="sgrb-row-category" '.$eachCategoryHide.'>
							<input class="sgrb-each-rate-skin" name="rate[]" type="hidden" value="">
							<input class="sgrb-fieldId" name="field[]" type="hidden" value="'.esc_attr($category->getId()).'">
								<div class="sgrb-row-category-name"><i>'.esc_attr($text).'</i></div>';
					if ($eachCategoryRate && !empty($eachRatesArray) && !empty($eachRatesArray[$category->getId()][0])) {
						$eachCategoryRate = round($eachRatesArray[$category->getId()][0]->getAverage(), 1);
					}
					$html .= '<div class="sgrb-rate-each-skin-wrapper">
							<input class="sgrb-each-category-total" name="" type="hidden" value="'.$eachCategoryRate.'">
							<select class="sgrb-point">
								  <option value="1">1</option>
								  <option value="2">2</option>
								  <option value="3">3</option>
								  <option value="4">4</option>
								  <option value="5">5</option>
								  <option value="6">6</option>
								  <option value="7">7</option>
								  <option value="8">8</option>
								  <option value="9">9</option>
								  <option value="10">10</option>
							</select></div></div>';
				}
			}
		}
		$html .= $closeHtml;
		return '<form class="sgrb-user-rate-js-form"><div class="'.$sgrbWidgetWrapper.'">'.$html.'</div></div>'.$commentForm.$allApprovedComments.'</div></form>';
	}

	// create new comment and rate calls in front
	public function ajaxUserRate()
	{
		global $sgrb;
		global $wpdb;
		$ratedFields = array();
		$cookieValue = '';
		$title = '';
		$comment = '';
		$ratedFields['fields'] = @$_POST['field'];
		$ratedFields['rates'] = @$_POST['rate'];

		$reviewId = $_POST['reviewId'];
		$post = $_POST['addPostId'];
		$currentReview = SGRB_ReviewModel::finder()->findByPk($reviewId);
		$reviewOptions = $currentReview->getOptions();
		$options = json_decode($reviewOptions,true);

		$adminEmail = $options['notify'];
		$isRequiredTitle = $options['required-title-checkbox'];
		$isRequiredEmail = $options['required-email-checkbox'];
		$autoApprove = $options['auto-approve-checkbox'];
		if (!$autoApprove) {
			$autoApprove = 0;
		}
		$reviewTitle = $currentReview->getTitle();
		$name = @$_POST['addName'];
		$mainEmail = @$_POST['addEmail'];
		if ($mainEmail) {
			$email = filter_var($mainEmail, FILTER_VALIDATE_EMAIL);
		}
		$title = @$_POST['addTitle'];
		$comment = @$_POST['addComment'];
		if (count($_POST)) {
			$cookieValue = $_SERVER['REMOTE_ADDR'];
			for ($i=0;$i<count($ratedFields['fields']);$i++) {
				if (!$ratedFields['rates'][$i]) {
					$commonRate = false;
					echo $commonRate;
					return;
				}
			}
			$mainComment = new SGRB_CommentModel();
			$mainComment->setCdate(sanitize_text_field(date('Y-m-d-h-m-s')));
			$mainComment->setReview_id(sanitize_text_field($reviewId));
			$mainComment->setPost_id(sanitize_text_field($post));
			$mainComment->setApproved($autoApprove);
			$mainComment->setName(sanitize_text_field($name));
			$mainComment->setEmail(sanitize_text_field($email));
			$mainComment->setTitle(sanitize_text_field($title));
			$mainComment->setComment(sanitize_text_field($comment));

			$mainComment->save();
			$lastComId = $wpdb->insert_id;
			if ($adminEmail) {
				$currentUser = wp_get_current_user();
				$currentUserName = $currentUser->user_nicename;
				$subject = 'Review Builder Wordpress plugin.';
				$blogName = get_option('blogname');
				$editUrl = $sgrb->adminUrl('Comment/index').'sgrb_allComms&id='.$reviewId;
				$headers = 'Hi '.ucfirst($currentUserName).'! Your '.ucfirst($reviewTitle).' review created in Wordpress,  '.$blogName.' blog, has been commented.';
				$message = 'Follow this link '.$editUrl.' to edit it.';

				mail($adminEmail, $subject, $message, $headers);
			}

			// if admin selects to notify about new comment
			if ($adminEmail) {
				$currentUser = wp_get_current_user();
				$currentUserName = $currentUser->user_nicename;
				$subject = 'Review Builder Wordpress plugin.';
				$blogName = get_option('blogname');
				$editUrl = $sgrb->adminUrl('Comment/index').'sgrb_allComms&id='.$reviewId;
				$headers = 'Hi '.ucfirst($currentUserName).'! Your '.ucfirst($reviewTitle).' review created in Wordpress,  '.$blogName.' blog, has been commented.';
				$message = 'Follow this link '.$editUrl.' to edit it.';

				mail($adminEmail, $subject, $message, $headers);
			}

			$rate = 0;

			// ($ratedFields['fields']) & ($ratedFields['rates']) have equal count;
			for ($i=0;$i<count($ratedFields['fields']);$i++) {
				$mainRate = new SGRB_Comment_RatingModel();
				$mainRate->setComment_id(sanitize_text_field($lastComId));
				$mainRate->setRate(sanitize_text_field($ratedFields['rates'][$i]));
				$mainRate->setCategory_id(sanitize_text_field($ratedFields['fields'][$i]));
				$mainRate->save();
				$rate += $ratedFields['rates'][$i];
				$commonRate = $rate / count($ratedFields['rates']);
				if ($commonRate !== 10) {
					$commonRate = str_replace('0','',$commonRate);
				}
			}

			// if new insert, save the rater
			if ($lastComId) {
				$newUser = new SGRB_Rate_LogModel();
				$newUser->setReview_id(sanitize_text_field($reviewId));
				if ($post) {
					$newUser->setPost_id(sanitize_text_field($post));
				}
				$newUser->setIp(sanitize_text_field($cookieValue));
				$newUser->save();
			}
			echo $lastComId;
			exit();
		}
	}

	public function ajaxSelectTemplate ()
	{
		global $sgrb;
		$tempName = $_POST['template'];
		$mainTemplate = new Template($tempName);
		$res = $mainTemplate->adminRender();
		echo $res;
		exit();
	}

	public function ajaxPagination ()
	{
		global $sgrb;
		global $wpdb;
		$currentPage = $_POST['page'];
		$start = $_POST['itemsRangeStart'];
		$perPage = $_POST['perPage'];
		$postId = $_POST['postId'];

		if ($postId) {
			$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d && post_id = %d GROUP BY cdate DESC LIMIT '.$start.', '.$perPage.' ' , array($_POST['review'], 1, $postId));
		}
		else {
			$approvedComments = SGRB_CommentModel::finder()->findAll('review_id = %d && approved = %d GROUP BY cdate DESC LIMIT '.$start.', '.$perPage.' ' , array($_POST['review'], 1));
		}

		$allApprovedComments = '';
		$arr = array();
		$i = 0;
		foreach ($approvedComments as $appComment) {
			$i++;
			$commentId = $appComment->getId();
			$rates = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', array($commentId));
			foreach ($rates as $rate) {
				$arr[$i]['rates'][] = $rate->getRate();
			}
			$arr[$i]['title'] = esc_attr($appComment->getTitle());
			$arr[$i]['comment'] = esc_attr($appComment->getComment());
			$arr[$i]['name'] = esc_attr($appComment->getName());
			$arr[$i]['date'] = esc_attr($appComment->getCdate());
		}
		$allApprovedComments = json_encode($arr);
		echo $allApprovedComments;
		exit();
	}

	public function getPostReview ($post,$review)
	{
		global $wpdb;
		$sql = $wpdb->prepare("SELECT meta_value FROM ". $wpdb->prefix ."postmeta WHERE post_id = %d AND meta_key = %s",$post,$review);
		$row = $wpdb->get_row($sql);
		$id = 0;
		if($row) {
			$id =  (int)@$row->meta_value;
		}
		return $id;
	}
}
?>
