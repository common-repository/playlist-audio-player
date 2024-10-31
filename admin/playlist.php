<?php 
if(is_user_logged_in())
{
	include_once PLAYER_PATH . '/admin/queries.php';
	if (!is_dir(PLAYER_MAIN_DIR))
	{
		?>
		<div class="updated notice is-dismissible">
			<p> <?php _e("Warning!");?> <strong><?php _e("If you are getting problems with uploading tracks, then you need to set 775(write) permissions to ".PLAYER_MAIN_DIR." (recursive files &amp; directories) in order to save the tracks or contact support team.",playlist_player);?></strong></p>
		</div>
		<?php
	}
	?>
	<div class="container">
		<div class="main_box">
			<div class="row">
				<h2><?php _e("Playlist Audio Player",playlist_player) ?></h2>
				<p><?php _e("Create your top 5 tracks playlist.",playlist_player) ?></p>
				<div class="col-md-12 no-padding">
					<form id="frm_create_list" name="frm_create_list" class="form_bg">
						<?php
						if(!isset($_REQUEST["track_id"]) && (count($get_track_records) < 5))
						{
						?>				
							<div class="form-group">
								<button type="button" id="btn_create_list" class="btn btn-info" name="btn_create_list"><?php _e("Create List",playlist_player) ?></button>
							</div>
						<?php
						}
						?>
						<div id="ux_playlist" style="<?php echo !isset($_REQUEST["track_id"]) ? 'display:none;' : '';?>" >
							<div class="form-group">
								<label for="music_title" class="col-sm-2 control-label no-padding">
									<?php _e("Music Title",playlist_player) ?>
								</label>
								<div class="col-sm-10 no-padding">
									<input type="text" placeholder="<?php _e("Enter title of music",playlist_player) ?>" class="form-control input-field" name="txt_music_title" id="txt_music_title" value="<?php echo isset($get_track->track_title) ? stripcslashes(htmlentities(urldecode($get_track->track_title))) : ""; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label for="music_title" class="col-sm-2 control-label no-padding">
									<?php _e("Music Description",playlist_player) ?>
								</label>
								<div class="col-sm-10 no-padding">
									<textarea placeholder="<?php _e("Enter description of music",playlist_player) ?>" class="form-control input-field" name="txt_music_desc" id="txt_music_desc"><?php echo isset($get_track->track_description) ? stripcslashes(htmlentities(urldecode($get_track->track_description))) : ""; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="music_title" class="col-sm-2 control-label no-padding">
									<?php _e("Image",playlist_player) ?>
								</label>
								<div class="col-sm-10 no-padding">
									<div class="col-sm-4 no-padding">
										<a class="btn btn-danger" id="upload_cover_image" href=""><?php _e( "Browse ", playlist_player ); ?></a>
									</div>
									<div class="col-sm-6" id="preview_image">
									<?php 
									if(isset($get_track->track_thumbnail) && !empty($get_track->track_thumbnail))
									{
										?>
										<img id="thumbnail_image" src="<?php echo PLAYER_IMAGE_URL.$get_track->track_thumbnail;?>" img_path="<?php echo $get_track->track_thumbnail;?>" width="150px" />
										<?php
									}
									?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="music_title" class="col-sm-2 control-label no-padding">
									<?php _e("Upload Track",playlist_player) ?>
								</label>
								<div class="col-sm-10 no-padding" id="div_upload_track">
									<div class="col-sm-6 no-padding">
										<input type="text" name="ux_track" class="form-control input-field col-sm-6" readonly="readonly" id="ux_track" placeholder="<?php _e("Url of track", playlist_player);?>" track_path="<?php echo isset($get_track->track_path) ? $get_track->track_path : ""; ?>" track_name="<?php echo isset($get_track->track_name) ? $get_track->track_name : ""; ?>" value="<?php echo isset($get_track->track_name) ? PLAYER_UPLOAD_URL.$get_track->track_name : ""; ?>"/>
									</div>
									<div class="col-sm-2 no-padding-right">
										<button type="button" class="btn btn-danger" id="btn_upload_track" name="btn_upload_track"><?php _e("Upload",playlist_player) ?></button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="music_title" class="col-sm-2 control-label no-padding">
									<?php _e("Set Featured",playlist_player) ?>
								</label>
								<div class="col-sm-10 no-padding" >
									<input type="checkbox" <?php echo isset($get_track->is_featured) && $get_track->is_featured == 1 ? "checked='checked'" : ""; ?> name="chk_featured" class="form-control " id="chk_featured" value="1"/>
								</div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success" id="btn_upload" name="btn_upload"><?php _e("Save Changes",playlist_player) ?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="row">
				<h2><?php _e("Tracks Listing",playlist_player) ?></h2>
				<div class="col-md-12 no-padding">
					<table class="table" id="tbl-track-listing">
						<thead>
							<th style="width:25%;"><?php _e("Track Name",playlist_player) ?></th>
							<th><?php _e("Title",playlist_player) ?></th>
							<th><?php _e("Image",playlist_player) ?></th>
							<th></th>
						</thead>
						<tbody id="bind_tracks">
							<?php
							if(count($get_track_records) > 0)
							{
								foreach($get_track_records as $track)
								{
									?>
									<tr>
										<td><?php echo esc_attr($track->track_name);?></td>
										<td><?php echo stripcslashes(htmlentities(urldecode($track->track_title)));?></td>
										<td><img src="<?php echo !empty($track->track_thumbnail) ? PLAYER_IMAGE_URL.$track->track_thumbnail : PLAYER_URL."/images/default-image.jpg";?>" style="height:80px;width:80px;"/></td>
										<td>
											<a data-toggle="tooltip" title="<?php echo _("Edit");?>" href="?page=music_playlist&track_id=<?php echo $track->track_id;?>" class="btn btn-info" ><i class="fa fa-eye"></i><?php echo _("Edit");?></a>
											<button data-toggle="tooltip" title="<?php echo ($track->is_featured == 0 ? _e("Set Featured") : _e("Featured"));?>" type="button" id="btn_featured<?php echo $track->track_id;?>" class="btn <?php echo ($track->is_featured == 0 ? "btn-warning" : "btn-success");?>" onclick="<?php echo ($track->is_featured == 0 ? 'set_featured('.$track->track_id.',1)' : 'set_featured('.$track->track_id.',0)');?>">
												<i class="<?php echo ($track->is_featured == 0 ? 'fa fa-heart-o' : 'fa fa-heart');?>"></i>
												<?php echo ($track->is_featured == 0 ? _e("Set Featured") : _e("Featured"));?>
											</button>
											<button data-toggle="tooltip" title="<?php echo _("Delete");?>" type="button" class="btn btn-danger" track_name="<?php echo esc_attr($track->track_path);?>"  onclick="delete_track(<?php echo $track->track_id;?>,this);">
												<i class="fa fa-trash-o"></i>
												<?php echo _("Delete");?>
											</button>
										</td>
									</tr>
									<?php
								}
							} 
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var thumbnail_path = "";
		var pl_upload_url = "<?php echo PLAYER_URL ?>/js/pluploader/";
		jQuery(document).ready(function()
		{
			var oTable = jQuery("#tbl-track-listing").dataTable
			({
				"bJQueryUI": false,
				"bAutoWidth": true,
				"sPaginationType": "full_numbers",
				"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
				"oLanguage": 
				{
					"sLengthMenu": "<span>Show entries:</span> _MENU_"
				},
				"aaSorting": [[ 0, "asc" ]],
				"aoColumnDefs": [{ "bSortable": false, "aTargets": [3] }]
			});
			var uploader = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'btn_upload_track', // you can pass an id...
				container: document.getElementById('div_upload_track'), // ... or DOM Element itself
				url : ajaxurl + "?param=upload_track&action=upload_library",
				unique_names : true,
				multi_selection: false,
				max_file_count: 1,
				flash_swf_url : pl_upload_url+'Moxie.swf',
				silverlight_xap_url : pl_upload_url+'/Moxie.xap',
				filters : {
					max_file_size : '10mb',
					mime_types: [
						{title : "music files", extensions : "mp3"},
					]
				},

				init: {
					FileUploaded: function (up, file) {
							var track_path = file.target_name;
							jQuery("#ux_track").val("<?php echo PLAYER_UPLOAD_URL;?>"+track_path);
							jQuery("#ux_track").attr("track_name",file.name);
							jQuery("#ux_track").attr("track_path",track_path);
					},
			        FilesAdded: function(up, files) {
						uploader.start();
			        },
			 		Error: function(up, err) {
			            document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
			        }
			    }
			});

			uploader.init();
		});
		jQuery("#btn_create_list").click(function()
		{
			jQuery("#ux_playlist").slideToggle("slow");
		});
		var cover_file_frame;  
		jQuery('#upload_cover_image').live('click', function( event ){
			event.preventDefault();
			cover_file_frame = wp.media.frames.cover_file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
					text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false
			});
			cover_file_frame.on( 'select', function() {
				var selection = cover_file_frame.state().get('selection');
				selection.map( function( attachment ) {
					attachment = attachment.toJSON();
					var image_path = attachment.url.split("wp-content");
					var created_image = jQuery("<img id=\"thumbnail_image\" width=\"150px\" img_path=\""+image_path[1]+"\" src=\""+attachment.url+"\"/>");
					jQuery("#preview_image").html(created_image);
					thumbnail_path = attachment.url;
				});
			});
			cover_file_frame.open();
		});
		
		function removeImage(control)
		{
			jQuery(control).parent("div").remove();
		}
		
		jQuery("#frm_create_list").validate
		({
			rules:
			{
				txt_music_title: {
					required: true,
				},
				ux_track: {
					required: true
				}
			},
			submitHandler: function () 
			{
				var tarck_array = [];
				var id = "<?php echo isset($_REQUEST["track_id"]) ? intval($_REQUEST["track_id"]) : 0;?>";
				var title = encodeURIComponent(jQuery("#txt_music_title").val());
				var description = encodeURIComponent(jQuery("#txt_music_desc").val());
				var checkbox = jQuery("#chk_featured").prop("checked");
				var isfeatured = checkbox == true ? 1 : 0;
				var image_path = jQuery("#thumbnail_image").attr("img_path");
				var track_name = jQuery("#ux_track").attr("track_name");
				var track_path = jQuery("#ux_track").attr("track_path");
				tarck_array.push(title);
				tarck_array.push(description);
				tarck_array.push(isfeatured);
				tarck_array.push(image_path);
				tarck_array.push(track_name);
				tarck_array.push(track_path);
				jQuery.post(ajaxurl, "track_array="+encodeURIComponent(JSON.stringify(tarck_array))+"&id="+id+"&param=save_track&action=add_track_library",function()
				{
					window.location.href = "admin.php?page=music_playlist";
				});
			}
		});
		
		function set_featured(track_id,featured)
		{
			jQuery.post(ajaxurl, "track_id="+track_id+"&featured="+featured+"&param=update_track_featuring&action=add_track_library",function()
			{
				var newStatus = featured == 0 ? 1 : 0;
				var oldclass = featured == 0 ? "btn-success" : "btn-warning";
				var newclass = featured == 0 ? "btn-warning" : "btn-success";
				var newvalue = featured == 0 ? "<?php _e("Set Featured",playlist_player);?>" : "<?php _e("Featured",playlist_player);?>";
				var new_icon = featured == 0 ? '<i class="fa fa-heart-o"></i>' : '<i class="fa fa-heart"></i>';
				jQuery("#btn_featured"+track_id).attr("onclick","set_featured("+track_id+","+newStatus+")");
				jQuery("#btn_featured"+track_id).removeClass(oldclass).addClass(newclass);
				jQuery("#btn_featured"+track_id).html(new_icon+newvalue);
			});
		}
		
		function delete_track(track_id,control)
		{
			var r = confirm("<?php _e( "Are you sure you want to delete this track?", playlist_player ); ?>");
			if(r == true)
			{
				var track_name = jQuery(control).attr("track_name");
				jQuery.post(ajaxurl, "track_id="+track_id+"&track_name="+encodeURIComponent(track_name)+"&param=delete_track&action=add_track_library",function()
				{
					window.location.href = "admin.php?page=music_playlist";
				});
			}
		}
	</script>
	<?php
}
?>
