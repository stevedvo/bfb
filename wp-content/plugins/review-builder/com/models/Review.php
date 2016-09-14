<?php

global $sgrb;
$sgrb->includeModel('Model');

class SGRB_ReviewModel extends SGRB_Model
{
	const TABLE = 'review';
	protected $id;
	protected $title;
	protected $template_id;
	protected $options;

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
					  `title` varchar(255) NOT NULL,
					  `template_id` int(10) unsigned NOT NULL,
					  `options` text NOT NULL,
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
}
