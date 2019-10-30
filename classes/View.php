<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class View
{
	public static function renderPartial($template, $data = [] , $return = false)
	{
	    $view = DEPARTMENTS_PATH . '/partials/'.$template.'.php';
	    self::guardViewExist($view);
	    $content = self::renderInternal($view, $data);
        if($return) return $content;  
        else echo $content;
	}

	static protected function renderInternal($view, $data)
	{
	    extract($data);
	    ob_start();
	    require $view;
	    $content = ob_get_contents();
	    ob_end_clean();
	    return $content;
	}
	static protected function guardViewExist($view)
	{

		clearstatcache(TRUE, $view);
    	if(!file_exists($view)){
    		throw new NotFoundException("View file not found");
    	}
    	else{
    		return true;
    	} 
	}
}