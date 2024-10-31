<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Qvidocalc
 *
 * @author php
 */
class playlist_player {

    //init class
    public function __construct() 
    {
		add_action("wp_enqueue_scripts", array($this, 'playerAddJs'), 11); //add plugin js
		add_action("wp_enqueue_scripts", array($this, 'playerAddCss'), 11); //add plugin js
		add_action("admin_init", array($this, 'playerAddBackendJs'));
		add_action("admin_init", array($this, 'playerAddBackendCss'));
		add_action("init", array( $this,'include_ajax_functions_for_player'));
		add_action("wp_enqueue_scripts", array($this, 'playerAddInitJs'), 11); //add plugin js
		add_action("admin_menu", array($this, 'playerMenu'));
		add_shortcode('playlist_player', array($this, 'createPlayerShortcode'));
    }

    /**
     * add calculator js 
     */
    public function playerAddJs() 
    {
		wp_enqueue_script('jquery');
		wp_register_script('player-cookie-js', (PLAYER_URL . '/assets/js/jquery.cookie.js')); //register script
		wp_enqueue_script('player-cookie-js');
		wp_register_script('player-modernizr-custom-js', (PLAYER_URL . '/assets/js/modernizr.custom.26880.js')); //register script
		wp_enqueue_script('player-modernizr-custom-js');
	//	wp_register_script('player-nicescroll-js', (PLAYER_URL . '/assets/js/jquery.nicescroll.min.js')); //register script
	//	wp_enqueue_script('player-nicescroll-js');
		wp_register_script('player-ui-js', (PLAYER_URL . '/assets/js/jquery-ui.min.js')); //register script
		wp_enqueue_script('player-ui-js');
		wp_register_script('player-scroll-js', (PLAYER_URL . '/assets/js/jquery.mCustomScrollbar.concat.min.js')); //register script
		wp_enqueue_script('player-scroll-js');
    }

    /**
     * add calculator js 
     */
    public function playerAddInitJs() 
    {
		wp_enqueue_script('jquery');
	//	wp_register_script(player-init-js', (PLAYER_URL . '/assets/js/playerInit.js'), array(), '1.0.0', false); //register script
		wp_register_script('player-init-js', (PLAYER_URL . '/assets/js/playerInit.js')); //register script
		// Localize the script with new data
		$data_array = array(	   
			'path' => PLAYER_URL
		);
		wp_localize_script('player-init-js', 'playerobj', $data_array);
		wp_enqueue_script('player-init-js');
    }

    /**
     * add css
     */
    public function playerAddCss() 
    {
		wp_register_style('player-bootstrap-css', PLAYER_URL . '/assets/css/bootstrap/bootstrap.min.css');
		wp_enqueue_style('player-bootstrap-css');
		wp_register_style('player-scroll-css', PLAYER_URL . '/assets/css/jquery.mCustomScrollbar.min.css');
		wp_enqueue_style('player-scroll-css');
		wp_register_style('player-jquery-ui', PLAYER_URL . '/assets/css/jquery-ui.css');
		wp_enqueue_style('player-jquery-ui');
		wp_register_style('player-custom-player', PLAYER_URL . '/assets/css/custom-player.css');
		wp_enqueue_style('player-custom-player');
		wp_register_style('player-design', PLAYER_URL . '/assets/css/design.css');
		wp_enqueue_style('player-design');
    }
    
    /**
     * add backend css
     */
	public function playerAddBackendCss() 
	{
		wp_enqueue_style('player-bootstrap-css',PLAYER_URL . '/assets/css/bootstrap/bootstrap.min.css');
		wp_enqueue_style('player-custom-style',PLAYER_URL . '/assets/css/custom-style.css');
    }
    
    public function playerAddBackendJs()
    {
		wp_enqueue_script('jquery');
		wp_enqueue_media();
		wp_enqueue_script('jquery.validate.min',PLAYER_URL . '/assets/js/jquery.validate.min.js');
		wp_enqueue_script('jquery.dataTables.min',PLAYER_URL . '/assets/js/jquery.dataTables.min.js');
		wp_enqueue_script('plupload.full.min-js',PLAYER_URL . '/assets/js/pluploader/js/plupload.full.min.js');
		wp_enqueue_script('jquery.plupload.queue-js',PLAYER_URL . '/assets/js/pluploader/js/jquery.plupload.queue.js');
	}
    
	
    /**
     * create calculator using shortcode     
     */
    public function createPlayerShortcode() 
    {
		require_once PLAYER_PATH . '/views/player.php';
    }
    
    /*
     * Function to create Menu
     */
     
     public function playerMenu()
     {
		 add_menu_page(__( 'Playlist Audio Player', playlist_player ), __( 'Playlist Audio Player', playlist_player ), "read", "music_playlist", "", "");
		 add_submenu_page("", __( 'Playlist Audio Player', playlist_player ), __( 'Playlist Audio Player', playlist_player ), "read", "music_playlist", array( $this, 'music_playlist' ));
	 }
	
	/*
	 * Function to execute file on menu click
	 */
	public function music_playlist()
	{
		include_once PLAYER_PATH."/admin/playlist.php";
	}
	
	/**
	 * Functio for Ajax actions
	 */
	 
	public function include_ajax_functions_for_player()
	{
		if(isset($_REQUEST["action"]))
		{
			switch(esc_attr($_REQUEST["action"]))
			{
				case "upload_library":
					include_once PLAYER_PATH . '/classes/upload.php';
				break;
				case "add_track_library":
					include_once PLAYER_PATH . '/classes/track_class.php';
				break;
			}
		}
	}
}
