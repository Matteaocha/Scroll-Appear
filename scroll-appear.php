<?php
/**
* Plugin Name: Scroll Appear
* Description: A plugin that let's you add simple classes to any object that make it appear gracefully on scroll
* Author: Matteaocha
* Author URI: http://teaochadesign.com
* Version: 1.0
* License: GPL2
*/

/*
Copyright (C) 2016 matteaocha@gmail.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'plugins_loaded', array( 'Scroll_Appear', 'get_instance' ) );


class Scroll_Appear {

	private static $instance = null;

	public static function get_instance () {
		if ( ! isset( self::$instance ) )
			self::$instance = new self;

		return self::$instance;
	}

	private function __construct () {
		add_action('wp_enqueue_scripts', array($this, 'load_assets'));
		add_action('wp_print_scripts', array($this, 'inline_scripts'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	function register_settings () {

		add_settings_field(
			'scroll_appear_dynamic',
			"<label for='scroll_appear_dynamic'>Scroll-Appear elements can be dynamically inserted</label>",
			array($this, 'render_setting'),
			'general',
			'default'
		);

		register_setting('general', 'scroll_appear_dynamic', array($this, 'sanitize_setting'));
	}

	function sanitize_setting ($value) {

		$options = array('Yes', 'No');

		if(!in_array($value, $options)) {
			return 'No';
		}
		else {
			return $value;
		}
	}

	function render_setting () {

		$checked = get_option("scroll_appear_dynamic", "No");
		$yesChecked = ($checked === "Yes"? "checked='checked'" : "");
		$noChecked = ($checked === "No"? "checked='checked'" : "");

		echo "
			Yes <input id='scroll_appear_dynamic' type='radio' name='scroll_appear_dynamic' value='Yes' $yesChecked />
			No <input type='radio' name='scroll_appear_dynamic' value='No' $noChecked />
		";
	}

	function inline_scripts () { 

		$dynamicElements = (get_option("scroll_appear_dynamic", "No") === "Yes" ? "true" : "false");

		echo "
			<script>
				(function (ScrollAppear) {

					ScrollAppear.dynamicElements = $dynamicElements

				})(window.ScrollAppear ? window.ScrollAppear : window.ScrollAppear = {})
			</script>
		";
	}

	function load_assets () {

		wp_register_style( 'scroll_appear_styles', plugins_url('scroll-appear/styles.css'));
		wp_register_script( 'scroll_appear_scripts', plugins_url('scroll-appear/scripts.js'), array('jquery'));

		wp_enqueue_style('scroll_appear_styles');
		wp_enqueue_script('scroll_appear_scripts');
	}
}
