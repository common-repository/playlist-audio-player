<?php
global $wpdb;
require_once(ABSPATH . "wp-admin/includes/upgrade.php");
/****************************************** Code for Table Creation **********************/
if(!function_exists("create_table_track_list"))
{
	function create_table_track_list()
	{
		$sql = "CREATE TABLE " . track_list() . "(
            track_id BIGINT(25) UNSIGNED NOT NULL AUTO_INCREMENT,
            track_title VARCHAR(100),
            track_description TEXT,
            is_featured INTEGER (1),
            track_thumbnail VARCHAR(225),
            track_name VARCHAR(225),
            track_path VARCHAR(225),
            creation_date DATE,
            PRIMARY KEY (track_id)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
		dbDelta($sql);
	}
}
if (count($wpdb->get_var("SHOW TABLES LIKE '" . track_list() . "'")) == 0)
{
	create_table_track_list();
}
?>
