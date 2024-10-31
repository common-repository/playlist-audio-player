<?php
if(is_user_logged_in())
{
	global $wpdb;
	if(isset($_REQUEST["param"]))
	{
		switch(esc_attr($_REQUEST["param"]))
		{
			case "save_track":
			$id = intval($_REQUEST["id"]);
			$track_data = json_decode(stripcslashes($_REQUEST["track_array"]),true);
			if($id != 0)
			{
				$wpdb->query
				(
					$wpdb->prepare
						(
							"UPDATE " . track_list() . " SET track_title = %s, track_description = %s, is_featured = %d, track_thumbnail = %s, track_name = %s, track_path = %s WHERE track_id = %d",
							htmlspecialchars($track_data[0]),
							htmlspecialchars($track_data[1]),
							$track_data[2],
							$track_data[3],
							$track_data[4],
							$track_data[5],
							$id
						)
				);
			}
			else
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"INSERT INTO " . track_list() . " (track_title,track_description,is_featured,track_thumbnail,track_name,track_path,creation_date) VALUES(%s,%s,%d,%s,%s,%s,CURDATE())",
						htmlspecialchars($track_data[0]),
						htmlspecialchars($track_data[1]),
						$track_data[2],
						$track_data[3],
						$track_data[4],
						$track_data[5]
					)
				);
			}
			break;
			case "update_track_featuring":
				$track_id = intval($_REQUEST["track_id"]);
				$featured = intval($_REQUEST["featured"]);
				$wpdb->query
				(
					$wpdb->prepare
						(
							"UPDATE " . track_list() . " SET is_featured = %d WHERE track_id = %d",
							$featured,
							$track_id
						)
				);
			break;
			case "delete_track":
				$track_id = intval($_REQUEST["track_id"]);
				$track_name = stripslashes(htmlspecialchars($_REQUEST["track_name"]));
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM " . track_list() . " WHERE track_id = %d",
						$track_id
					)
				);
				
				$DelFilePath = PLAYER_UPLOAD_DIR."/" . $track_name;
				if (file_exists($DelFilePath)) { unlink ($DelFilePath); }
			break;
		}
		die();
	}
}
?>
