<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Model{
	protected $attributes;

	public function __construct()
	{
		$this->attributes = [];
	}

	public function __get($name)
	{
		if(array_key_exists($name, $this->attributes)){
			return $this->attributes[$name];
		}
		return false;
	}
	
	//Loads data from wordpress get_post_meta into attributes//
	public function get($id, $fields)
	{
		$model = get_post_meta( $id, '', true);
		foreach ($fields as $field)
		{
			$this->attributes[$field] = $model[$field][0];
		}
	}
	//Loads data from array ($_GET, $_POST, OTHER) into attributes
	public function load($array, $fields)
	{
		foreach($fields as $field){
			if (array_key_exists($field, $array) && $array[$field]!=''){
				$this->attributes[$field] = $array[$field];
			}	
		}
	}
	//Save attributes to post meta
	public function save($model_id)
	{
		$fields = $this->attributes;  
		foreach($fields as $field => $value)
		{
			update_post_meta( $model_id, $field, $value );	
		}
	}
}
