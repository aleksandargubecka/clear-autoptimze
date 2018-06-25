<?php
/*
Plugin Name: Themes Factory Clear Autoptimize
Plugin URI: https://themesfactory.io
Description: This plugin will run a hourly cron job that will clear autoptimze cache
Version: 1.0.0
Author: themesfactory
Author URI: https://themesfactory.io
*/

define( 'TF_CA_BASENAME', plugin_basename( __FILE__ ) );

add_action('wp', 'tf_add_clear_cache_to_cron_schedule');

function tf_add_clear_cache_to_cron_schedule()
{
    if (!wp_next_scheduled('tf_clear_autoptimize_cache')) {
        wp_schedule_event(time(), 'hourly', 'tf_clear_autoptimize_cache');
    }
}

add_action('tf_clear_autoptimize_cache', 'tf_call_clear_autoptimize');

function tf_call_clear_autoptimize(){
	require_once 'class-tf-clear.php';
	TF_Clear::getInstance();
}

require_once 'class-tf-settings.php';
TF_Settings::getInstance();