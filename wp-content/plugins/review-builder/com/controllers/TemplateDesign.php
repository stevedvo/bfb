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

class SGRB_TemplateDesignController extends SGRB_Controller
{

	public function index()
	{
		global $sgrb;
		$sgrb->includeScript('page/scripts/saveTemplate');
		$sgrb->includeStyle('page/styles/save');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$template = new SGRB_TemplateDesignView();
		$createNewUrl = $sgrb->adminUrl('TemplateDesign/save');

		SGRB_AdminView::render('TemplateDesign/index', array(
			'createNewUrl' => $createNewUrl,
			'template' => $template
		));
	}

	public function save()
	{
		global $sgrb;
		global $wpdb;
		$sgrb->includeScript('page/scripts/saveTemplate');
		$sgrb->includeScript('core/scripts/main');
		$sgrb->includeScript('core/scripts/sgrbRequestHandler');
		$sgrb->includeStyle('page/styles/save');
		$sgrb->includeStyle('page/styles/saveComment');
		$sgrb->includeStyle('page/styles/sg-box-cols');
		$sgrbTemplateId = 0;
		$sgrbTemplateDataArray = array();

		isset($_GET['id']) ? $sgrbTemplateId = (int)$_GET['id'] : 0;
		$sgrbTemplate = SGRB_TemplateDesignModel::finder()->findByPk($sgrbTemplateId);
		if ($sgrbTemplate) {

			$templateName = $sgrbTemplate->getName();
			$templateImage = $sgrbTemplate->getImg_url();
			$templateHtmlContent = $sgrbTemplate->getThtml();
			$templateCssContent = $sgrbTemplate->getTcss();

			$sgrbTemplateDataArray['templateName'] = $templateName;
			$sgrbTemplateDataArray['templateImage'] = $templateImage;
			$sgrbTemplateDataArray['templateHtmlContent'] = $templateHtmlContent;
			$sgrbTemplateDataArray['templateCssContent'] = $templateCssContent;
		}
		else {
			$sgrbTemplate = new SGRB_TemplateDesignModel();
		}
		SGRB_AdminView::render('TemplateDesign/save', array(
			'sgrbTemplateId' => $sgrbTemplateId,
			'sgrbTemplateDataArray' => $sgrbTemplateDataArray
		));
	}

	public function ajaxDeleteTemplate()
	{
		global $sgrb;

		$id = (int)$_POST['id'];
		$canDelete = true;
		
		$deletedTemplate = SGRB_TemplateDesignModel::finder()->findByPk($id);
		$deletedTemplateName = $deletedTemplate->getName();
		$allDeleted = SGRB_TemplateModel::finder()->findAll('name = %s', $deletedTemplateName);

		if ($allDeleted) {
			$canDelete = false;
		}

		if ($canDelete) {
			SGRB_TemplateDesignModel::finder()->deleteByPk($id);
		}
		echo $canDelete;
		exit();
	}

	public function ajaxSave()
	{
		global $wpdb;
		global $sgrb;
		$sgrbTemplateId = 0;
		$templateName = '';
		$templateHtml = '';
		$templateCss = '';

		if (count($_POST)) {
			$templateName = $_POST['sgrbTemplateName'];
			$templateImage = $_POST['sgrbTemplateImage'];
			$templateHtml = stripslashes($_POST['sgrbHtmlContent']);
			$templateCss = stripslashes($_POST['sgrbCssContent']);

			$sgrbTemplateId = (int)$_POST['sgrbTemplateId'];

			$template = new SGRB_TemplateDesignModel();
			$isUpdate = false;

			if ($sgrbTemplateId) {
				$isUpdate = true;
				$template = SGRB_TemplateDesignModel::finder()->findByPk($sgrbTemplateId);
			}

			$coincideTemplates = SGRB_TemplateDesignModel::finder()->findAll('name = %s', $templateName);
			if (!$coincideTemplates || ($isUpdate && $coincideTemplates)) {
				$template->setName($templateName);
				$template->setSgrb_pro_version(1);
				$template->setImg_url($templateImage);
				$template->setThtml($templateHtml);
				$template->setTcss($templateCss);
				$template->save();
				$lastId = $wpdb->insert_id;
				if (!$lastId) {
					$lastId = $template->getId();
				}
				echo $lastId;
			}
			else {
				echo false;
				exit();
			}			
		}
		exit();

	}
}
?>