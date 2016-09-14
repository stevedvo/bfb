<?php

global $sgrb;
$sgrb->includeController('Controller');
$sgrb->includeView('Admin');
$sgrb->includeView('Review');
$sgrb->includeView('Comment');
$sgrb->includeModel('Review');
$sgrb->includeModel('Comment');
$sgrb->includeModel('Comment_Rating');
$sgrb->includeModel('Template');
$sgrb->includeModel('Category');

class SGRB_CommentController extends SGRB_Controller
{
	public function index()
	{
		global $sgrb;
		$sgrb->includeScript('page/scripts/save');
		$sgrb->includeStyle('page/styles/saveComment');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$sgrb->includeScript('page/scripts/saveComment');
		$comment = new SGRB_CommentView;
		$createNewUrl = $sgrb->adminUrl('Comment/save');

		SGRB_AdminView::render('Comment/index', array(
			'createNewUrl' => $createNewUrl,
			'comment' => $comment
		));
	}

	public function ajaxSave()
	{
		global $wpdb;
		if (count($_POST)) {
			$sgrbId = (int)$_POST['sgrb-id'];
			$sgrbComId = (int)$_POST['sgrb-com-id'];

			$title = @$_POST['title'];
			$email = @$_POST['email'];
			$comment = @$_POST['comment'];
			$name = @$_POST['name'];
			$review = @$_POST['review'];
			$rates = @$_POST['rates'];
			$categories = @$_POST['categories'];
			$post = @$_POST['post'];
			$postCategory = @$_POST['post-category'];

			isset($_POST['isApproved']) ? $isApproved = (int)@$_POST['isApproved'] : 0;

			$sgrbComment = SGRB_CommentModel::finder()->findByPk($sgrbId);

			if (!$sgrbComId) {
				$sgrbComment = new SGRB_CommentModel();
			}

			$sgrbComment->setReview_id(sanitize_text_field($review));
			$sgrbComment->setCategory_id(sanitize_text_field($postCategory));
			$sgrbComment->setPost_id(sanitize_text_field($post));
			$sgrbComment->setTitle(sanitize_text_field($title));
			$sgrbComment->setEmail(sanitize_text_field($email));
			$sgrbComment->setComment(sanitize_text_field($comment));
			$sgrbComment->setName(sanitize_text_field($name));
			$sgrbComment->setCdate(sanitize_text_field(date('Y-m-d-h-m-s')));
			$sgrbComment->setApproved(sanitize_text_field($isApproved));
			$sgrbComment->save();
			$lastCommentId = $wpdb->insert_id;
			if (!$lastCommentId) {
				$lastCommentId = $sgrbComment->getId();
			}

			for ($i=0;$i<count($rates);$i++) {
				if (!$sgrbComId) {
					$commentRates = new SGRB_Comment_RatingModel();
					$commentRates->setComment_id(sanitize_text_field($lastCommentId));
					$commentRates->setCategory_id(sanitize_text_field($categories[$i]));
					$commentRates->setRate(sanitize_text_field($rates[$i]));
					$commentRates->save();
				}
				else {
					$commentRates = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', $lastCommentId);
					$commentRates[$i]->setComment_id(sanitize_text_field($lastCommentId));
					$commentRates[$i]->setCategory_id(sanitize_text_field($categories[$i]));
					$commentRates[$i]->setRate(sanitize_text_field($rates[$i]));
					$commentRates[$i]->save();
				}
			}
		}
		echo $lastCommentId;
		exit();
	}

	public function save()
	{
		global $wpdb;
		global $sgrb;
		$sgrb->includeStyle('page/styles/saveComment');
		$sgrb->includeStyle('page/styles/sg-box-cols');
		$sgrb->includeScript('page/scripts/save');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$sgrb->includeScript('page/scripts/saveComment');
		$sgrbId = 0;
		$sgrbDataArray = array();

		isset($_GET['id']) ? $sgrbId = (int)$_GET['id'] : 0;

		$sgrbSaveUrl = $sgrb->adminUrl('Comment/save');
		$sgrbDataArray = array();
		$ratingType = '';

		if ($sgrbId) {
			$sgrbComment = SGRB_CommentModel::finder()->findByPk($sgrbId);

			$title = $sgrbComment->getTitle();
			$email = $sgrbComment->getEmail();
			$comment = $sgrbComment->getComment();
			$name = $sgrbComment->getName();
			$isApproved = $sgrbComment->getApproved();
			$reviewId = $sgrbComment->getReview_id();
			$postCategoryId = $sgrbComment->getCategory_id();
			$postId = $sgrbComment->getPost_id();

			$category = new SGRB_CategoryModel();
			$sgrbReview = new SGRB_ReviewModel();
			if ($reviewId) {
				$category = SGRB_CategoryModel::finder()->findAll('review_id = %d', $reviewId);
				$ratings = SGRB_Comment_RatingModel::finder()->findAll('comment_id = %d', $sgrbId);
				$sgrbReview = SGRB_ReviewModel::finder()->findByPk($reviewId);
				$sgrbReviewTitle = $sgrbReview->getTitle();
				$sgrbOptions = $sgrbReview->getOptions();
				$sgrbOptions = json_decode($sgrbOptions, true);
				$ratingType = $sgrbOptions['rate-type'];
			}

			if ($ratingType == SGRB_RATE_TYPE_STAR) {
				$ratingType = 'star';
			}
			else if ($ratingType == SGRB_RATE_TYPE_PERCENT) {
				$ratingType = 'percent';
			}
			else if ($ratingType == SGRB_RATE_TYPE_POINT) {
				$ratingType = 'point';
			}

			$sgrbDataArray['review_id'] = $reviewId;
			$sgrbDataArray['review-title'] = $sgrbReviewTitle;
			$sgrbDataArray['ratingType'] = $ratingType;
			$sgrbDataArray['isApproved'] = $isApproved;
			$sgrbDataArray['title'] = $title;
			$sgrbDataArray['email'] = $email;
			$sgrbDataArray['comment'] = $comment;
			$sgrbDataArray['name'] = $name;

			// if it is post type review
			if ($postId) {
				$sgrbDataArray['post-category-title'] = get_the_category($postId)[0]->name;
				$sgrbDataArray['post-title'] = get_post($postId)->post_title;
				$sgrbDataArray['post-category-id'] = $postCategoryId;
				$sgrbDataArray['post-id'] = $postId;
			}

			$sgrbDataArray['category'] = $category;
			$sgrbDataArray['ratings'] = $ratings;
		}
		else {
			$sgrbComment = new SGRB_CommentModel();
			$sgrbDataArray['category'] = array();
			$sgrbDataArray['ratings'] = array();

		}

		$allReviews = SGRB_ReviewModel::finder()->findAll();

		if ($ratingType == SGRB_RATE_TYPE_STAR) {
			$ratingType = 'star';
		}
		else if ($ratingType == SGRB_RATE_TYPE_PERCENT) {
			$ratingType = 'percent';
		}
		else if ($ratingType == SGRB_RATE_TYPE_POINT) {
			$ratingType = 'point';
		}

		SGRB_AdminView::render('Comment/save', array(
			'sgrbDataArray' => $sgrbDataArray,
			'sgrbCommentId' => $sgrbId,
			'allReviews' => $allReviews,
			'sgrbSaveUrl' => $sgrbSaveUrl
		));
	}

	public function ajaxDelete()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		SGRB_CommentModel::finder()->deleteByPk($id);
		SGRB_Comment_RatingModel::finder()->deleteAll('comment_id = %d', $id);
		exit();
	}

	public function ajaxApproveComment()
	{
		global $sgrb;
		$id = (int)$_POST['id'];
		$currentComment = SGRB_CommentModel::finder()->findByPk($id);
		$isApproved = $currentComment->getApproved();
		if ($isApproved == 1) {
			$currentComment->setApproved(0);
		}
		else if ($isApproved == 0) {
			$currentComment->setApproved(1);
		}
		$currentComment->save();
		exit();
	}

	public function ajaxSelectReview()
	{
		global $sgrb;
		$sgrb->includeScript('page/scripts/saveComment');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');

		$sgrbDataArray = array();
		$id = (int)$_POST['id'];
		$review = SGRB_ReviewModel::finder()->findByPk($id);
		$allReviews = SGRB_ReviewModel::finder()->findAll();

		$categories = SGRB_CategoryModel::finder()->findAll('review_id = %d', $id);

		$sgrbOptions = $review->getOptions();
		$sgrbOptions = json_decode($sgrbOptions, true);
		$ratingType = $sgrbOptions['rate-type'];
		$count = 0;

		$isPostReview = $sgrbOptions['post-category'];

		if ($ratingType == SGRB_RATE_TYPE_STAR) {
			$ratingType = 'star';
			$count = 5;
		}
		else if ($ratingType == SGRB_RATE_TYPE_PERCENT) {
			$ratingType = 'percent';
			$count = 100;
		}
		else if ($ratingType == SGRB_RATE_TYPE_POINT) {
			$ratingType = 'point';
			$count = 10;
		}

		$sgrbDataArray['category'] = $categories;

		$html = '';

		$i = 0;
		$arr = array();

		if ($isPostReview) {
			$allPosts = get_posts();
			$allCategories = get_terms(array('get'=>'all'));
			foreach ($allCategories as $category) {
				$i++;
				$arr['postCategoies'][$i]['postCategoryId'] = esc_attr($category->term_id);
				$arr['postCategoies'][$i]['postCategoryTitle'] = esc_attr($category->name);
			}
			foreach ($allPosts as $singlePost) {
				if (wp_get_post_categories($singlePost->ID)[0] == $isPostReview) {
					$i++;
					$arr['posts'][$i]['postTitle'] = esc_attr($singlePost->post_title);
					$arr['posts'][$i]['postId'] = esc_attr($singlePost->ID);
				}
			}
 		}

		foreach ($sgrbDataArray['category'] as $category) {
			$i++;
			$arr[$i]['categoryId'] = esc_attr($category->getId());
			$arr[$i]['name'] = esc_attr($category->getName());
			$arr[$i]['ratingType'] = esc_attr($ratingType);
			$arr[$i]['count'] = esc_attr($count);
		}

		$html = json_encode($arr);

		echo $html;
		exit();

	}

	public function ajaxSelectPosts()
	{
		$categoryId = $_POST['categoryId'];
		$allPosts = get_posts(array('category' => $categoryId));
		$html = '';
		$i = 0;
		$arr = array();
		foreach ($allPosts as $post) {
			$i++;
			$arr[$i]['postId'] = esc_attr($post->ID);
			$arr[$i]['postTitle'] = esc_attr($post->post_title);
		}

		$html = json_encode($arr);

		echo $html;
		exit();
	}

}
