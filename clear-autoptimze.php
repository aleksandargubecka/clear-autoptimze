<?php
/*
Plugin Name: Clear Autoptimze
Plugin URI: https://mekshq.com
Description: This plugin will run a daily cron job that will clear autoptimze cache
Version: 0.1.0
Author: MeksHQ
Author URI: http://mekshq.com
*/

add_action('wp', 'add_clear_cache_to_cron_schedule');

function add_clear_cache_to_cron_schedule()
{
    if (!wp_next_scheduled('clear_autoptimize_cache')) {
        wp_schedule_event(time(), 'hourly', 'clear_autoptimize_cache');
    }
}

add_action('clear_autoptimize_cache', 'call_clear_autoptimize');

function call_clear_autoptimize(){
    ClearAutoptmizie::getInstance();
}

class ClearAutoptmizie
{
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * ClearAutoptmizie constructor.
     */
    public function __construct()
    {
        if (!class_exists('autoptimizeCache'))
            return;

        $this->clear();
    }

    private function clear()
    {
        $size = $this->dirSize();
        if ($size >= 800 && class_exists('autoptimizeCache')) {
            autoptimizeCache::clearall();
            mail('sunnyagarwal444@gmail.com', 'Autooptimze Cache cleared!', 'Autoptimzie cache cleared. It got up to ' . $size . ' MB');
            return;
        }
    }

    private function dirSize()
    {
        $stats = autoptimizeCache::stats();
        return $stats[0];
    }

}