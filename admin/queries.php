<?php
if(is_user_logged_in())
{
	global $wpdb;
	if(isset($_REQUEST["page"]))
	{
		switch(esc_attr($_REQUEST["page"]))
		{
			case "music_playlist":
				if(isset($_REQUEST["track_id"]))
				{
					$get_track = $wpdb->get_row
					(
						$wpdb->prepare
						(
							"SELECT * FROM ".track_list()." WHERE track_id = %d",
							intval($_REQUEST["track_id"])
						)
					);
				}
				$get_track_records = $wpdb->get_results
				(
					"SELECT * FROM ".track_list()." order by track_id DESC"
				);
			break;
		}
	}
}
?>
