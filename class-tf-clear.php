<?php


class TF_Clear
{
	private $opts;
	
	public static function getInstance()
	{
		static $instance = null;
		if (null === $instance) {
			$instance = new static();
		}
		
		return $instance;
	}
	
	public function __construct()
	{
		if (!class_exists('autoptimizeCache'))
			return;
		
		$default_opts = array(
			'size' => 800,
			'email' => ''
		);
		
		$this->opts = get_option( 'tf_clear_autoptimize_options', $default_opts );
		
		$this->clear();
	}
	
	private function clear()
	{
		$size = $this->dirSize();
		if ($size >= $this->opts['size'] && class_exists('autoptimizeCache')) {
			autoptimizeCache::clearall();
			if(!empty($this->opts['email'])){
				mail($this->opts['email'], 'Autooptimze Cache cleared!', 'Autoptimzie cache cleared. It got up to ' . $size . ' MB');
			}
			return;
		}
	}
	
	private function dirSize()
	{
		$stats = autoptimizeCache::stats();
		return $stats[0];
	}
	
}