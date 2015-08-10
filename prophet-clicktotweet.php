<?php
/**
 * Plugin Name: Prophet ClickToTweet
 * Plugin URI: http://github.com/mmaedler
 * Description: Adds the ability to instantly send a text excerpt to twitter by adding the shortcode [clicktotweet]
 * Author: Moritz Mädler
 * Version: 1.0
 * Author URI: http://prophet.com
 */

class PbsClickToTweet {
	private $shortcode = "clicktotweet";
	private $default_atts = array(
		"popup_width" 			=> 600,
		"popup_height"			=> 260,
		"popup_url"				=> "https://twitter.com/intent/tweet?text=%s&url=%s",
		// automatically truncates the tweet to (140-truncate_url_length) char long string:
		"truncate"				=> true,
		// the amount of chars that should be deducted from the tweet text to fit url in (if truncate == true):
		"truncate_url_length" 	=> -1, // -1 = auto, != -1 fixed length
		// the ellipsis for truncation (if truncate == true)
		"truncate_ellipsis" 	=> "...",


		// html template for shortcode replacement
		"_tmpl" => '<a href="%s" class="pbsclicktotweet" data-dimensions="%s">%s</a>'

	);
	private $atts = array();


	public function __construct () {

		//
		// Load default atts from internal or admin page
		//

		$this->load_defaults();


		//
		// Register shortcode
		//

		add_shortcode($this->shortcode, array($this, "render"));

		//
		// Add TinyMCE buttons
		//

		add_filter( 'mce_buttons', array($this, "mce_register_buttons") );
		add_filter( "mce_external_plugins", array($this, "mce_add_buttons") );
		add_action( 'admin_print_scripts', array($this, "add_quicktags"));


		// Add page to admin>settings menue
		if (is_admin()) {
			add_action("admin_menu", array($this, "action_admin_menu"));
			add_action("admin_init", array($this, "action_admin_init"));
		}

	}

	public function render ($atts, $content = null) {
		$this->atts = shortcode_atts($this->default_atts, $atts, $this->shortcode);
		$tweet = (strlen($atts["tweet"]) > 0) ? $atts["tweet"] : $content;

		//
		// neither tweet nor content? return empty string
		//

		if (strlen($tweet) == 0) return "";

		//
		// make sure $tweet is clean of html tags, line breaks, and multi spaces
		//

		$tweet = preg_replace("/\s+/", " ", strip_tags($tweet));

		//
		// if truncate is enabled, truncate tweet
		//

		if ((bool) $this->atts["truncate"]) {
			$tweet = $this->truncate($tweet);
		}

		//
		// setup popup url
		//

		$popup_url = sprintf(
			$this->atts["popup_url"],
			urlencode($tweet),
			get_permalink()
		);

		//
		// enqueue static files
		// - we do that here so that they are only included if they are really needed
		//

		$this->embed_static();


		//
		// return link html
		//
		//
		return sprintf(
			$this->atts["_tmpl"],
			$popup_url,
			$this->atts["popup_width"]."x".$this->atts["popup_height"],
			$content
		);
	}

	public function add_quicktags () {
		if (wp_script_is("quicktags")) {
			wp_enqueue_script("pbsclicktotweet-qt", plugins_url("/tinymce/quicktags.js", __FILE__), array("quicktags"));
		}
	}
	
	public function mce_register_buttons ($buttons) {
		array_push($buttons, 'pbsclicktotweet');
		return $buttons;
	}

	public function mce_add_buttons ($plugin_array) {
		$plugin_array["pbsclicktotweet"] = plugins_url("/tinymce/plugin.js", __FILE__);
		return $plugin_array;
	}
	
	private function load_defaults () {
		foreach (array_keys($this->default_atts) as $setting) {
			if (preg_match("/^_/", $setting)) continue;

			if (get_option($setting)) {
				$this->default_atts[$setting] = get_option($setting, $this->default_atts[$setting]);
			} else {
				update_option($setting, $this->default_atts[$setting]);
			}
		}
	}

	private function embed_static () {
		if (! wp_style_is("pbsclicktotweet-style")) {
			wp_enqueue_style("pbsclicktotweet-style-themify", plugins_url("/public/vendor/themify-icons/themify-icons.css", __FILE__));
			wp_enqueue_style("pbsclicktotweet-style", plugins_url("/public/prophet-clicktotweet.css", __FILE__));
		}

		if (! wp_script_is("pbsclicktotweet-script")) {
			wp_enqueue_script("pbsclicktotweet-script", plugins_url("/public/prophet-clicktotweet.js", __FILE__));
		}
	}

	private function truncate ($tweet) {
		$urllength = ($this->atts["truncate_url_length"] == -1) ? strlen(get_permalink()) : $this->atts["truncate_url_length"];
		$length = 140 - ($urllength + strlen($this->atts["truncate_ellipsis"]) + 2); // add 2 more chars for spaces

		return (strlen($tweet) > $length)
			? substr($tweet, 0, $length).$this->atts["truncate_ellipsis"]
			: $tweet;
	}

	//
	// ------------------------------------------------------------------------------------------------
	// Admin Settings Page related functions
	// ------------------------------------------------------------------------------------------------
	//

	/**
	 * Setup clicktotweet related admin variables
	 */
	public function  action_admin_init () {
		register_setting("pbs_clicktotweet_option_group", "popup_width");
		register_setting("pbs_clicktotweet_option_group", "popup_height");
		register_setting("pbs_clicktotweet_option_group", "popup_url");
		register_setting("pbs_clicktotweet_option_group", "truncate");
		register_setting("pbs_clicktotweet_option_group", "truncate_url_length");
		register_setting("pbs_clicktotweet_option_group", "truncate_ellipsis");
	}

	/**
	 * Include a link into the admin menue
	 */
	public function action_admin_menu () {
		add_options_page(
			"Prophet ClickToTweet Settings",
			"Prophet ClickToTweet",
			"manage_options",
			$this->shortcode."_settings",
			array($this, "add_options_page")
		);
	}

	/**
	 * Add the options page
	 */
	public function add_options_page () {
		echo self::load_settings_file();
	}

	private static function load_settings_file () {
		ob_start();
		require plugin_dir_path(__FILE__)."/adminsettings.php";
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}


}

$clk2twt = new PbsClickToTweet();
