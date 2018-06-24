<?php


class CA_Clear
{
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
		
		$this->clear();
	}
	
	private function clear()
	{
		$size = $this->dirSize();
		if ($size >= 800 && class_exists('autoptimizeCache')) {
			autoptimizeCache::clearall();
			mail('example@gmail.com', 'Autooptimze Cache cleared!', 'Autoptimzie cache cleared. It got up to ' . $size . ' MB');
			return;
		}
	}
	
	private function dirSize()
	{
		$stats = autoptimizeCache::stats();
		return $stats[0];
	}
	
}