jQuery(window).load(function ($) {
    window.ricupdatePlayer = false;
    /*
     * Play song in list if it is single
     */
    jQuery.fn.playSongIfSingle = function () {

        if (jQuery(".music-playlist .music-single-row").length == 1)
        {
            jQuery("#song-player").attr('src', jQuery(".music-playlist .music-single-row").find('audio').attr('src'));
        }
    };
    /*
     * add music to play list and cookie
     * 
     * @param {object} result
     * @returns {undefined}
     */
    jQuery.fn.saveMusicPlaylist = function (dragged_el) {
        jQuery(".music-single-row-empty").hide();
        var array_of_object = new Array();
        var i;
        var music_id = 0;
        if (jQuery.cookie('rcmusicplaylist') == undefined)
        {//play list is empty

        } else {
            var already_exists;
            for (i = 0; i < JSON.parse(jQuery.cookie('rcmusicplaylist')).length; i++)
            {//go through existing elements
                array_of_object.push(JSON.parse((jQuery.cookie('rcmusicplaylist')))[i]);
                if (JSON.parse((jQuery.cookie('rcmusicplaylist')))[i].id >= music_id) {
                    music_id = JSON.parse((jQuery.cookie('rcmusicplaylist')))[i].id;
                }
                if (JSON.parse((jQuery.cookie('rcmusicplaylist')))[i].url == dragged_el.parent().find('audio').attr('src'))
                {
                    already_exists = true;
                    break;
                }
            }
        }

        if (!already_exists) {
            var image_url;
            if (dragged_el.parent().parent().parent().parent().parent().parent().find('.monstersSprites .music-inner-bg img').length > 0 && dragged_el.parent().parent().parent().parent().parent().parent().find('.monstersSprites .music-inner-bg img').attr('src') != '')
            {
                image_url = dragged_el.parent().parent().parent().parent().parent().parent().find('.monstersSprites .music-inner-bg img').attr('src');
            } else {
                image_url = playerobj.path + "/images/default-image.jpg";
            }
            var music_uploaded = {id: music_id + 1, url: dragged_el.parent().find('audio').attr('src'), name: dragged_el.text(), image: image_url, current_song: 0};//save current music
            array_of_object.push(music_uploaded);
            jQuery.cookie('rcmusicplaylist', JSON.stringify(array_of_object), '');
            var image_url_def = playerobj.path + "/images/music-loader.gif";
            var chnage_music = "changeMusic('audio-single-id-" + (music_id + 1) + "')";
            var auto_html = jQuery('<div  class=" row music-single-row" data-music-id="' + (music_id + 1) + '">\n\
		\n\
<div class="col-md-2"><img src="' + image_url + '"/><audio class="hidden" onplay="' + chnage_music + '" id="audio-single-id-' + (music_id + 1) + '" src="' + dragged_el.parent().find('audio').attr('src') + '" controls="">not </audio></div>\n\
<div class="col-md-4 song-name">' + dragged_el.text() + '</div>\n\
<div class="col-md-2"><span class="song-duration song-duration-id-' + ((music_id + 1)) + '"><img alt="Loader" src="' + image_url_def + '"/><span></div>\n\
<div class="col-md-2"><span class="glyphicon glyphicon glyphicon-play play-music cursor-pointer"></span></div>\n\
<div class="col-md-2"><span class="glyphicon glyphicon-remove-sign remove-music cursor-pointer"></span></div>\n\
</div>');
            jQuery(".music-playlist .music-list").find('.ui-effects-wrapper').remove();
            jQuery(auto_html).insertBefore(".music-playlist .music-list .music-single-row-empty");
            jQuery.fn.playSongIfSingle();
            return true;
        } else {
            return false;
        }
    };
    /*
     * add drop effect
     * 
     * @param {objetc} dragged_el
     * @returns {undefined}
     */
    jQuery.fn.musicAddEffect = function (dragged_el) {

        var selectedEffect = 'scale';
// most effect types need no options passed by default
        var options = {};
// some effects have required parameters
        if (selectedEffect === "scale") {
            options = {percent: 0};
        } else if (selectedEffect === "size") {
            options = {to: {width: 200, height: 60}};
        }
// run the effect
        jQuery(dragged_el).hide(selectedEffect, options, 500);
    };
    /*
     * remove effect
     * 
     * @param {string} effect
     * @returns {undefined}
     */
    jQuery.fn.customEffects = function (effect, callback) {

        var selectedEffect = effect;
// most effect types need no options passed by default
        var options = {};
// some effects have required parameters
        if (selectedEffect === "scale") {
            options = {percent: 0};
        } else if (selectedEffect === "size") {
            options = {to: {width: 200, height: 60}};
        }
// run the effect
        jQuery(this).hide(selectedEffect, options, 500, callback);
    };
    /*
     *  play last current song
     */
    jQuery.fn.playLastCurrentSong = function () {
        if (jQuery(".music-playlist .music-single-row").length > 0)
        {
            var last_played_song_exist = 0;
            var temp_music_cookie_list = JSON.parse(jQuery.cookie('rcmusicplaylist'));
            var i = 0;
            for (i = 0; i < (temp_music_cookie_list).length; i++)
            {//go through existing elements

                if (((temp_music_cookie_list)[i].current_song) == 1) {
                    last_played_song_exist = 1;
                    //set current song
                    jQuery(".music-playlist .music-single-row").each(function () {
                        if (jQuery(this).attr('data-music-id') == temp_music_cookie_list[i].id)
                        {
                            jQuery(this).find('.play-music').trigger('click');
                        }
                    });
                    break;
                }

            }
            if (last_played_song_exist == 0)
            {
                //not last song in list
//		jQuery(".music-playlist .music-single-row:nth-child(1)").find('.play-music').trigger('click');
            }

        }
    };
    /*
     * set current music
     */
    jQuery.fn.setLastCurrentSong = function (remove_current_song) {
        var temp_music_cookie_list = JSON.parse(jQuery.cookie('rcmusicplaylist'));
        jQuery.removeCookie('rcmusicplaylist', {path: '/'});
        var array_of_object = new Array();
        var i = 0;
        var counter = 0;
        for (i = 0; i < (temp_music_cookie_list).length; i++)
        {//go through existing elements

            if ((((temp_music_cookie_list))[i].id) == (jQuery(this).parent().parent().attr('data-music-id'))) {
                if (remove_current_song) {
                    //prevent song from playing at next load
                    temp_music_cookie_list[i].current_song = 0;
                } else {
                    //set current song
                    temp_music_cookie_list[i].current_song = 1;
                }
            } else {
                //remove current song
                temp_music_cookie_list[i].current_song = 0;
            }
            array_of_object.push(((temp_music_cookie_list))[i]);
        }
        jQuery.cookie('rcmusicplaylist', JSON.stringify(array_of_object), '');

    };
    /*
     * show full length of song
     */
    jQuery.fn.showSongLength = function (current_el) {
        jQuery(".music-playlist .music-single-row").each(function () {
            var song_length = document.getElementById(jQuery(this).find('audio').attr('id')).duration;
            if (song_length < 60) {
                jQuery(this).find('.song-duration').text("00:" + parseInt(((song_length)))).fadeIn();
            } else {
                jQuery(this).find('.song-duration').text(parseFloat(parseFloat((song_length)) / 60).toFixed(2)).fadeIn();
            }
        });
    };
    
    /*
     * reset player
     */
    jQuery.fn.resetPlayer = function () {
        document.getElementById('song-player').currentTime = 0;
    };
    
    /*
     * reset player cookie
     */
    jQuery.fn.resetPlayerCookie = function () {
        setTimeout(function () {
			//alert(jQuery.cookie('rcmlastsongtime'));
            //jQuery.cookie('rcmlastsongtime', 0, {expires: 30, path: '/'});
        }, 1000);
    };

    /*
     * open play list 		
     */
    jQuery(".pull-playlist").on({
        click: function () {
            jQuery(".music-playlist").slideToggle({duration: 500, easing: 'swing', complete: function () {
                    if (!jQuery(".pull-playlist").is(":animated")) {
                        if (jQuery(".music-playlist ").is(":visible")) {
                        } else {
                            jQuery('.pull-playlist span').animate({'top': '0px'});
                        }
                    }
                }});
           // jQuery('.pull-playlist span').tooltip("destroy");
        }
    });
    /*
     * remove music
     */
    jQuery(document).on("click", ".remove-music",
            function () {
                if (JSON.parse(jQuery.cookie('rcmusicplaylist')).length == 1)
                {
                    jQuery.removeCookie('rcmusicplaylist', {path: '/'});
                    jQuery(".music-single-row-empty").fadeIn();//show empty list
                    jQuery("#song-player").fadeOut();
                } else {
                    var temp_music_cookie_list = jQuery.cookie('rcmusicplaylist');
                    jQuery.removeCookie('rcmusicplaylist', {path: '/'});
                    var array_of_object = new Array();
                    var i = 0;
                    var counter = 0;
                    for (i = 0; i < JSON.parse(temp_music_cookie_list).length; i++)
                    {//go through existing elements
                        if ((JSON.parse((temp_music_cookie_list))[i].id) != (jQuery(this).parent().parent().attr('data-music-id'))) {
                            array_of_object.push(JSON.parse((temp_music_cookie_list))[i]);
                        }
                    }
                    jQuery.cookie('rcmusicplaylist', JSON.stringify(array_of_object), '');
                }
                jQuery(this).parent().parent().customEffects('drop', function () {
                    jQuery(this).remove();
                    jQuery(".music-playlist .music-list").find('.ui-effects-wrapper').remove();
                });

                /*
                 * check this music is currently playing
                 */
                if (jQuery(this).parent().parent().find('audio').attr('src') == jQuery("#song-player").attr('src'))
                {
                    jQuery("#song-player").attr('src', '');
                }
            });
    /*
     * play music
     */
    jQuery(document).on("click", ".play-music",
            function (event) {
                //user wants to play this song
                if (jQuery(this).hasClass('glyphicon-play')) {
                    jQuery(this).removeClass('glyphicon-play').addClass('glyphicon-pause');
                    if (jQuery("#song-player").attr('src') != jQuery(this).parent().parent().find('audio').attr('src')) {
                        if (event.originalEvent === undefined) {
        //alert('not human')
					//console.log(jQuery(this).parent().parent().find('audio').attr('src'));
                        } else {
                            document.getElementById('song-player').currentTime = 0;
                            
                        }
						jQuery("#song-player").attr('src', jQuery(this).parent().parent().find('audio').attr('src'));
                        
                    }

                    document.getElementById('song-player').play();
                    jQuery(this).parent().parent().siblings().find('.glyphicon-pause').removeClass('glyphicon-pause').addClass('glyphicon-play');
                    //set current song
                    jQuery(this).setLastCurrentSong(false);
                    //set current class
                    jQuery(this).parent().parent()
                            .addClass('current-playing-song')
                            .siblings().removeClass('current-playing-song');
                } else {
                    //user has paused this song
                    jQuery(this).removeClass('glyphicon-pause').addClass('glyphicon-play');
                    document.getElementById('song-player').pause();
                    //remove current class
                    jQuery(this).parent().parent().removeClass('current-playing-song');
                    //unset current song
                    jQuery(this).setLastCurrentSong(true);
                    //jQuery.fn.resetPlayerCookie();//unset cookie
                }

            });
    /*
     * pull playlist icon
     */
    jQuery(".music-player").on({
        mouseenter: function () {

            if (jQuery(".music-playlist ").is(":visible")) {

            } else {
                if (!jQuery(".pull-playlist span").is(":animated"))
                {
					jQuery('.pull-playlist span').css("display","block");
                    jQuery('.pull-playlist span').animate({'top': '-43px'});
                }
            }


        }, mouseleave: function () {
            if (jQuery(".music-playlist ").is(":visible")) {
            } else {
                if (!jQuery(".pull-playlist span").is(":animated"))
                {
                    jQuery('.pull-playlist span').animate({'top': '0px'});
                    jQuery('.pull-playlist span').css("display","none");
                }
            }
        }
    });
    
    /*
     * show player
     */
    jQuery(".song-player").css('position', 'absolute').fadeIn();
    /*
     * play last currentsong
     */
    jQuery.fn.playLastCurrentSong();
    /*
     * display song length
     */
//    setTimeout(function () {
//	jQuery.fn.showSongLength();
//    }, 500);
   
    /*
     *init scroller
     */
    jQuery(".music-playlist").mCustomScrollbar({
//					autoHideScrollbar:true,
        theme: "rounded",
        advanced: {updateOnContentResize: true},
        callbacks: {
            onScroll: function () {
                jQuery(".music-playlist").mCustomScrollbar("update");
            }
        }
    });

//check audio support
    if (Modernizr.audio.mp3) {
//	window.console.log('mp3');
    } else {
        jQuery('.music-player .drag').css({'width': '100%', 'text-align': 'center'});
        jQuery('.music-player .drag p').text('Your browser does not support this player');
        jQuery('.music-player .playlist-container,.music-player .drag .music-icon,#song-player').remove();

    }
    /*
     * update player on every tab
     */
    jQuery.fn.playlistMusicAdder = function () {
        if (jQuery.cookie('rcmusicplaylist') != undefined)
        {//we have music in playlist
            var song_found, song_url;
            var j = 0;
            var temp_music_cookie_list = JSON.parse(jQuery.cookie('rcmusicplaylist'));//get cookie
            var i = 0;

            for (i = 0; i < (temp_music_cookie_list).length; i++)
            {//go through existing elements
                song_found = 0;
                for (j = 0; j < jQuery(".music-playlist .music-list .music-single-row").length; j++) {//loop through songs in html			

                    if (((temp_music_cookie_list)[i].url) == jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('audio').attr('src')) {
                        song_found = 1;
                        break;
                    }
                }
                if (song_found == 0)
                {
                    var this_song_exists = 0;
                    jQuery(".music-playlist .music-single-row").each(function () {//loop through songs
                        if ((jQuery(this).attr('data-music-id')) == (temp_music_cookie_list)[i].id) {
                            this_song_exists = 1;
                        }
                    });
                    if (this_song_exists == 0)
                    {
//			    //song not found
                        var image_url = playerobj.path + "/images/music-loader.gif";
                        var chnage_music = "changeMusic('audio-single-id-" + ((temp_music_cookie_list)[i].id) + "')";
                        var auto_html = jQuery('<div  class=" row music-single-row" data-music-id="' + ((temp_music_cookie_list)[i].id) + '">\n\
		\n\
<div class="col-md-2"><img src="' + ((temp_music_cookie_list)[i].image) + '"/><audio class="hidden" onplay="' + chnage_music + '" id="audio-single-id-' + ((temp_music_cookie_list)[i].id) + '" src="' + ((temp_music_cookie_list)[i].url) + '" controls="">not </audio></div>\n\
<div class="col-md-4 song-name">' + ((temp_music_cookie_list)[i].name) + '</div>\n\
<div class="col-md-2"><span class="song-duration song-duration-id-' + ((temp_music_cookie_list)[i].id) + '"><img alt="loader" src="' + image_url + '"/><span></div>\n\
<div class="col-md-2"><span class="glyphicon glyphicon glyphicon-play play-music cursor-pointer"></span></div>\n\
<div class="col-md-2"><span class="glyphicon glyphicon-remove-sign remove-music cursor-pointer"></span></div>\n\
</div>');
                        jQuery(".music-playlist .music-list .music-single-row-empty").hide();
                        jQuery(".music-playlist .music-list").find('.ui-effects-wrapper').remove();
                        jQuery(auto_html).insertBefore(".music-playlist .music-list .music-single-row-empty");

                    }
                }
            }
        }
    };
    jQuery.fn.playlistMusicRemover = function () {
        if (jQuery(".music-playlist .music-list .music-single-row").length > 0) {//at least one music is in playlist

            if (jQuery.cookie('rcmusicplaylist') == undefined)
            {//no cookie means no music
                jQuery(".music-playlist .music-single-row").remove().queue(function () {
                    jQuery(".music-playlist .music-single-row-empty").fadeIn();//display message
                });//remove html
            } else {
                //remove only specific music from list		    
                var song_found;
                song_url;
                var j = 0;
//		    window.console.log("playlist music length:" + jQuery(".music-playlist .music-list .music-single-row").length);
//		    window.console.log("cookie music length:" + (JSON.parse(jQuery.cookie('rcmusicplaylist'))).length);

                for (j; j < jQuery(".music-playlist .music-list .music-single-row").length; j++) {//loop through songs in html
                    //now lets see this song is in cookie or not
                    //if not then why wshould we keep this in playlist,yeahhhhh
                    var temp_music_cookie_list = JSON.parse(jQuery.cookie('rcmusicplaylist'));//get cookie
                    var i = 0;
                    var song_url = '';
                    song_found = 0;
                    for (i = 0; i < (temp_music_cookie_list).length; i++)
                    {//go through existing elements
                        if (((temp_music_cookie_list)[i].url) == jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('audio').attr('src')) {
                            song_found = 1;
                            break;
                        }
                        song_url = jQuery(".music-playlist .music-list .music-single-row:nth-child(" + (j + 1) + ")").find('audio').attr('src');
                      //  window.console.log("song:" + song_url);
                    }
                    if (song_found == 0)
                    {

//			    //song not found
                        jQuery(".music-playlist .music-single-row").each(function () {//loop through songs
                            if (jQuery(this).find('audio').attr('src') == song_url)
                            {
                                if (jQuery(".music-playlist .music-list").find('.ui-effects-wrapper').length == 0) {
                                    if (!jQuery(this).is(":animated")) {
                                        jQuery(this).remove();
                                        jQuery(".music-playlist .music-list").find('.ui-effects-wrapper').remove();
                                    }
                                }
                            }
                        });
                    } else {
                    }

                }

            }
        }
    };
    jQuery.fn.secondsToTimeFormat = function (secs) {
        var hours = Math.floor(secs / (60 * 60));

        var divisor_for_minutes = secs % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);

        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.ceil(divisor_for_seconds);

        var obj = {
            "h": hours,
            "m": minutes,
            "s": seconds
        };
        return obj;
    };
    jQuery.fn.playlistMusicLengthFinder = function () {
        var j = 0;
        for (j = 0; j < jQuery(".music-playlist .music-list .music-single-row").length; j++) {//loop through songs in html			
            if (jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('.song-duration').has("img") || jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('.song-duration').text() == "" || jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('.song-duration').text() == 'NaN' || jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('.song-duration').text() == NaN) {
                var last_id = (jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").attr('data-music-id'));

		//window.console.log("audio-single-id-" + last_id+"  :   "    +(document.getElementById("audio-single-id-" + last_id).duration));
                if (!isNaN(document.getElementById("audio-single-id-" + last_id).duration))
                {
		    //window.console.log(document.getElementById("audio-single-id-" + last_id).duration);
                    if (jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('.song-duration img').length == 1) {
                        jQuery(".music-playlist .music-single-row:nth-child(" + (j + 1) + ")").find('.song-duration img').remove();
                    }
                    if (document.getElementById("audio-single-id-" + last_id).duration < 60) {

                        if ((parseInt(document.getElementById("audio-single-id-" + last_id).duration)) != NaN) {

                            jQuery(".song-duration-id-" + last_id).text("00:" + (parseInt(document.getElementById("audio-single-id-" + last_id).duration))).fadeIn();
                        }
                    } else {
                        if ((parseInt(document.getElementById("audio-single-id-" + last_id).duration)) != NaN) {
                            var m, s;
                            if ((jQuery.fn.secondsToTimeFormat(parseFloat(document.getElementById("audio-single-id-" + last_id).duration))).m < 10)
                            {
                                m = "0" + (jQuery.fn.secondsToTimeFormat(parseFloat(document.getElementById("audio-single-id-" + last_id).duration))).m;
                            } else {
                                m = (jQuery.fn.secondsToTimeFormat(parseFloat(document.getElementById("audio-single-id-" + last_id).duration))).m;
                            }
                            if ((jQuery.fn.secondsToTimeFormat(parseFloat(document.getElementById("audio-single-id-" + last_id).duration))).s < 10)
                            {
                                s = "0" + (jQuery.fn.secondsToTimeFormat(parseFloat(document.getElementById("audio-single-id-" + last_id).duration))).s;
                            } else {
                                s = (jQuery.fn.secondsToTimeFormat(parseFloat(document.getElementById("audio-single-id-" + last_id).duration))).s;
                            }
                            jQuery(".song-duration-id-" + last_id).text(m + ":" + s).fadeIn();
                        }
                    }
                } else {

                }

            }
//		    }
        }
    };
    if (typeof (Worker) !== "undefined") {
        if (Modernizr.audio.mp3) {

            jQuery.fn.playlistManager = function () {
                // Yes! Web worker support!

                jQuery.fn.playlistMusicAdder();
                jQuery.fn.playlistMusicRemover();
                jQuery.fn.playlistMusicLengthFinder();
                setTimeout(function () {
                    jQuery.fn.playlistManager();
                }, 2000);
            };

            jQuery.fn.playlistManager();
        }
    } else {
        // Sorry! No Web Worker support..
        if (Modernizr.audio.mp3) {

            jQuery.fn.playlistManager = function () {

                jQuery.fn.playlistMusicRemover();
                jQuery.fn.playlistMusicAdder();
                jQuery.fn.playlistMusicLengthFinder();
                setTimeout(function () {
                    jQuery.fn.playlistManager();
                }, 2000);
            };

            jQuery.fn.playlistManager();
        }
    }
});
/*
 * click and play current audio play
 */
function changeMusic(el) {

    jQuery(".music-playlist .music-single-row").each(function () {
        if (jQuery(this).find('audio').attr('id') == el)
        {
        } else {
            document.getElementById(jQuery(this).find('audio').attr('id')).pause();
        }
    });
}


/*
 * song ended
 */
function songEnded(current_song) {
	var booltest = true;
		jQuery(".music-playlist .music-single-row").each(function () {
			if (booltest) 
			{
				if (jQuery(this).find('audio').attr('src') == jQuery(current_song).attr('src'))
				{
					if (jQuery(this).next().find('audio').attr('src') == undefined)
					{
						if (jQuery(".music-playlist .music-list .music-single-row").length == 1)
						{//we have only one song 
							booltest = false;
							jQuery.fn.resetPlayer();
							document.getElementById('song-player').play();
						} else {
	//			     we are on last song
							booltest = false;
							jQuery(".music-playlist .music-list .music-single-row:nth-child(1)").find('.play-music').trigger('click');
						}
					} else {
						booltest = false;
						jQuery(this).next().find('.play-music').trigger('click');
					}
				}
			}
		});
		
}
/*
 * on play music add pause class
 * 
 */
function musicPlayFun() {
    jQuery(".music-playlist .music-single-row").each(function () {
        if (jQuery('#song-player').attr('src') == jQuery(this).find('audio').attr('src'))
        {
            jQuery(this).find('.play-music').removeClass('glyphicon-play').addClass('glyphicon-pause');
            //add class current music
            jQuery(this).addClass('current-playing-song').siblings().removeClass('current-playing-song');
            //set current song
            jQuery(jQuery(this).find('.play-music')).setLastCurrentSong(false);
        }
    });
    if (jQuery("#song-player").attr('src') == '') {
        jQuery(".music-playlist .music-single-row:nth-child(1)").find('.play-music').trigger('click');
    }
}
/*
 * on pause music add play class
 * 
 */
function musicPauseFun() {
    jQuery(".music-playlist .music-single-row").each(function () {
        if (jQuery('#song-player').attr('src') == jQuery(this).find('audio').attr('src'))
        {
            jQuery(this).find('.play-music').removeClass('glyphicon-pause').addClass('glyphicon-play');
            //remove current class
            jQuery(this).removeClass('current-playing-song');
            //set current song
            jQuery(jQuery(this).find('.play-music')).setLastCurrentSong(true);
            //jQuery.fn.resetPlayerCookie();//unset cookie
        }
    });
}
/*
 * load song time
 */
function loadMusicTime() {
    var last_current_song_exists = false;
    var temp_music_cookie_list = JSON.parse(jQuery.cookie('rcmusicplaylist'));
    var i = 0;
    for (i = 0; i < (temp_music_cookie_list).length; i++)
    {//go through existing elements
        if (((temp_music_cookie_list)[i].current_song) == 1) {
            last_current_song_exists = true;
        }

    }
    if (last_current_song_exists && isNaN(parseInt(jQuery('.music-player').attr('loaded')))) {
       if (jQuery.cookie('rcmlastsongtime') != undefined)
        {
            var time = jQuery.cookie('rcmlastsongtime');
            document.getElementById('song-player').currentTime = time;
            
        }
ricupdatePlayer = true;
    } else {
        ricupdatePlayer = true;
        document.getElementById('song-player').currentTime = 0;
    }
    jQuery('.music-player').attr('loaded',1);
}
/*
 * update song time in cookie
 */
function updateSongTime() {
    if (ricupdatePlayer) {
        jQuery.cookie('rcmlastsongtime', document.getElementById('song-player').currentTime, '');
    }
}

/*
 * pre load images
 */
var images = new Array();
function preload() {
    for (i = 0; i < preload.arguments.length; i++) {
        images[i] = new Image();
        images[i].src = preload.arguments[i];
    }
}
preload(playerobj.path + "/images/music-loader.gif");
