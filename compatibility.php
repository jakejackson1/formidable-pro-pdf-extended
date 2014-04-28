<?php

/*
 * Add in compatibility so we don't need to rewrite all the functions from Gravity Forms into native PHP
 */
 if(!function_exists('rgpost'))
 {
	function rgpost($key)
	{
		if(isset($_POST[$key]))
		{
			return $_POST[$key];	
		}
		return '';
	}
 }
 
 if(!function_exists('rgget'))
 {
	function rgget($key)
	{
		if(isset($_GET[$key]))
		{
			return $_GET[$key];	
		}
		return '';
	}
 } 
 
 if(!function_exists('rgempty'))
 {
	function rgempty($key, $array)
	{
		if(!isset($array[$key]))
		{
			return true;	
		}
		elseif(strlen($array[$key]) == 0)
		{
			return true;	
		}
		return false;
	}
 }

?>