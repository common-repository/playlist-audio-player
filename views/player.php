<?php
global $wpdb;
$get_track_records = $wpdb->get_results
        (
        $wpdb->prepare
                (
                "SELECT * FROM " . track_list() . " WHERE is_featured = %d", 1
        )
);
$track_records = array();

for ($flag = 0; $flag < count($get_track_records); $flag++) {

    $track_records[$flag]["id"] = $get_track_records[$flag]->track_id;
    $track_records[$flag]["url"] = PLAYER_UPLOAD_URL . $get_track_records[$flag]->track_path;
    $track_records[$flag]["name"] = stripcslashes(htmlentities(urldecode($get_track_records[$flag]->track_title)));
    $track_records[$flag]["image"] = !empty($get_track_records[$flag]->track_thumbnail) ? PLAYER_IMAGE_URL . $get_track_records[$flag]->track_thumbnail : PLAYER_URL . "/images/default-image.jpg";
    $track_records[$flag]["current_song"] = 0;
}
?>
<script type="text/javascript">
    jQuery(document).ready(function ()
    {
        if (!jQuery.cookie('rcmusicplaylist'))
        {
            jQuery.cookie("rcmusicplaylist", '<?php echo json_encode($track_records); ?>', '');
            jQuery.cookie('rcmrepeattrack', 'all', '');
            jQuery.cookie('rcmshuffle', 'off', '');
        }
    });
</script>
<div class="music-player">
    <div class="playlist-container col-md-12 position-relative">
        <div class="pull-playlist text-center " data-tooltiptext="test">
            <span aria-hidden="true" class="glyphicon glyphicon-triangle-top color-white"></span>
        </div>
        <div class="music-playlist position-relative">
            <div class="music-list">
              
                <?php
                $muscounter = 1;
//                  

                if (isset($_COOKIE['rcmusicplaylist']) && !empty($_COOKIE['rcmusicplaylist'])) {

                    $music = array();

                    foreach ($track_records as $value) {

                        //$music = (array) $value;
                        ?>
                        <div class="row music-single-row" data-music-id="<?php echo $value['id'] ?>">
                            <div class="col-md-2">
                                <img src="<?php echo $value['image'] ?>" />
                                <audio  class="hidden" id="audio-single-id-<?php echo $value['id'] ?>" src="<?php echo urldecode($value['url']); ?>"  onplay="changeMusic('audio-single-id-<?php echo $value['id'] ?>')">Your browser does not support the <code>audio</code> tag.</audio>
                            </div>
                            <div class="col-md-4 song-name"><?php echo $value['name']; ?></div>
                            <div class="col-md-2">
                                <span class="song-duration song-duration-id-<?php echo $value['id'] ?>">
                                    <img src="<?php echo PLAYER_URL; ?>/images/music-loader.gif" alt="Loading" /><span>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="glyphicon glyphicon glyphicon-play play-music cursor-pointer"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="glyphicon glyphicon-remove-sign remove-music cursor-pointer"></span>
                                        </div>
                                        </div>
                                        <?php
                                        $muscounter++;
                                    }
                                }
                                ?>
                                <div class="col-md-12 row music-single-row-empty " <?php
                                if ($muscounter == 0) {
                                    
                                } elseif ($muscounter > 0) {
                                    echo " style='display: none' ";
                                }
                                ?>>
                                    <div class="col-md-12"><?php _e("No music in your playlist.", playlist_player) ?></div>
                                </div>
                                </div>
                                </div>
                                </div>
                                
                                <div class="bottom-area col-md-12">
                                   <!-- <div class="container">-->
                                          <button class="delete_cookie">Get All Songs</button>
                                        <div class="dragMusic">
                                            <div class="doted-line" id="droppable">  
                                                <div class="song-player col-md-12">
                                                    <audio  onpause="musicPauseFun()" onplay="musicPlayFun()"  onloadeddata="loadMusicTime()" ontimeupdate="updateSongTime()" src="" id="song-player" controls="" style="<?php ?>" onended="songEnded(this)" ><?php _e("Sorry your browser is not supported by this player", playlist_player) ?></audio>
                                                </div>
                                            </div>
                                        </div>
                                    <!--</div>-->
                                </div>
                                </div>


<script>
    
   jQuery(document).ready(function($){
       $(".delete_cookie").click(function(){
           setCookie("rcmusicplaylist","",1);
           location.reload();
       });
   });
   
   function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
</script>
