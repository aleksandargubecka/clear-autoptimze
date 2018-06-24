<?php

class TF_Settings {
	
	private $is_autoptimize_active;
	
	private $opts;
	
	public static function getInstance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
		}
		
		return $instance;
	}
	
	public function __construct() {
		$this->check_if_autoptimize_active();
		
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'plugin_action_links_'.TF_CA_BASENAME, array( &$this, 'add_settings_link' ) );
		
		$default_opts = array(
			'size' => 800,
			'email' => '',
		);
		
		$this->opts = get_option( 'tf_clear_autoptimize', $default_opts );
		$this->opts = $this->parse_args( $this->opts, $default_opts );
	}
	
	private function check_if_autoptimize_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		$this->is_autoptimize_active = is_plugin_active( 'autoptimize/autoptimize.php' );
	}
	
	public function register_settings() {
		
		add_settings_section( 'tf_clear_autoptimize', 'Clear Autoptimize Options', array(
			&$this,
			'settings_section_info',
		), 'general' );
		
		if($this->is_autoptimize_active){
			add_settings_field( 'tf_clear_autoptimize[size]', __( 'Clear autoptimize after cache size is more then:', 'tf-clear-autoptimize' ), array(
				&$this,
				'size_callback',
			), 'general', 'tf_clear_autoptimize', $this->opts['size'] );
			
			add_settings_field( 'tf_clear_autoptimize[email]', __( 'Notify me when cache is cleared:', 'tf-clear-autoptimize' ), array(
				&$this,
				'email_callback',
			), 'general', 'tf_clear_autoptimize', $this->opts['email'] );
		}
		
		register_setting( 'general', 'tf_clear_autoptimize', array( &$this, 'sanitize_opts' ) );
	}
	
	public function settings_section_info() {
		echo '<div id="tfclearautoptimize"></div>';
		if(!$this->is_autoptimize_active){
			echo 'Autoptimize cache clearing can\'t work without Autoptimze plugin';
		}
	}
	
	
	public function size_callback( $size ) {
		echo '<input type="number" name="tf_clear_autoptimize[size]" value="' . $size . '" class="small-text" style="height: 28px; vertical-align: top;"><p class="description">Size is in MBs</p>';
	}
	
	public function email_callback( $email ) {
		echo '<input type="email" name="tf_clear_autoptimize[email]" value="' . $email . '" style="height: 28px; vertical-align: top;"><p class="description">Leave empty if you don\'t want to be notified</p>';
	}
	
	public function sanitize_opts( $opts ) {
		$opts['size'] = absint($opts['size']);
		return $opts;
	}
	
	public function add_settings_link( $links ) {
		$admin_url = admin_url();
		$link = array( '<a href="'.$admin_url.'options-general.php#tfclearautoptimize">Settings</a>' );
		
		return array_merge( $links, $link );
	}
	
	public function parse_args( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$r = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $r[ $k ] ) ) {
				$r[ $k ] = $this->parse_args( $v, $r[ $k ] );
			} else {
				$r[ $k ] = $v;
			}
		}
		
		return $r;
	}
}