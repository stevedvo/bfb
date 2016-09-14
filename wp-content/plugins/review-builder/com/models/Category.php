<?php

global $sgrb;
$sgrb->includeModel('Model');

class SGRB_CategoryModel extends SGRB_Model
{
	const TABLE = 'category';
	protected $id;
	protected $review_id;
	protected $name;


	public static function finder($class = __CLASS__)
	{
		return parent::finder($class);
	}

	public static function create()
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE);

		$query = "CREATE TABLE IF NOT EXISTS $tablename (
					`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`review_id` int(10) unsigned NOT NULL,
					`name` varchar(255) NOT NULL,
					 PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
		$wpdb->query($query);
	}

	public static function drop()
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE);
		$query = "DROP TABLE $tablename";
		$wpdb->query($query);
	}

	public static function toArray($sgrbField)
	{
		$dataArray = array();
		$dataArray['id'] = $sgrbField->getId();
		$dataArray['review_id'] = $sgrbField->getReview_id();
		$dataArray['name'] = $sgrbField->getName();
		return $dataArray;
	}
}
