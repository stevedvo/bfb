<?php

global $sgrb;
$sgrb->includeModel('Model');
define('SGRB_IMAGES_PATH', $sgrb->asset('page/img/'));

class SGRB_TemplateDesignModel extends SGRB_Model
{
	const TABLE = 'template_design';
	protected $id;
	protected $name;
	protected $sgrb_pro_version;
	protected $img_url;
	protected $thtml;
	protected $tcss;

	public static function finder($class = __CLASS__)
	{
		return parent::finder($class);
	}

	public static function create()
	{
		global $sgrb;
		global $wpdb;
		$tablename = $sgrb->tablename(self::TABLE);
		$path = $sgrb->app_url.'assets/page/img/';

		$dropQquery = "DROP TABLE IF EXISTS $tablename";
		$createQuery = "CREATE TABLE $tablename (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) NOT NULL,
					  `sgrb_pro_version` tinyint(3) unsigned DEFAULT NULL,
					  `img_url` varchar(255) NOT NULL,
					  `thtml` longtext NOT NULL,
					  `tcss` longtext NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

		require_once($sgrb->app_path.'sql/templates_sql.php');

		$wpdb->query($dropQquery);
		$wpdb->query($createQuery);
		$wpdb->query($insertQuery1);
		$wpdb->query($insertQuery2);
		$wpdb->query($insertQuery3);
		$wpdb->query($insertQuery4);
		$wpdb->query($insertQuery5);
		$wpdb->query($insertQuery6);
		$wpdb->query($insertQuery7);
		$wpdb->query($insertQuery8);
		$wpdb->query($insertQuery9);
		$wpdb->query($insertQuery10);
		$wpdb->query($insertQuery11);
		$wpdb->query($insertQuery12);
		$wpdb->query($insertQuery13);
		$wpdb->query($insertQuery14);
		$wpdb->query($insertQuery15);
		$wpdb->query($insertQuery16);
		$wpdb->query($insertQuery17);
		$wpdb->query($insertQuery18);
		$wpdb->query($insertQuery19);
		$wpdb->query($insertQuery20);
		$wpdb->query($insertQuery21);
		$wpdb->query($insertQuery22);
		$wpdb->query($insertQuery23);
		$wpdb->query($insertQuery24);
		$wpdb->query($insertQuery25);
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
