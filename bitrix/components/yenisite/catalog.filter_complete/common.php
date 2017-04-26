<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER;

/**
* @param	$array
* @result	cache id
*/
if (!function_exists("getCacheIdFromParams"))
{
	function getCacheIdFromParams($array)
	{
		foreach ($array as $k => $v)
		if (strncmp("~", $k, 1))
			$param[$k] = $v;
	
		$resultParams = '';
		foreach($param as $k => $v)
		{
			if (!is_array($v))
				$resultParams .= $v.',';
			else
				foreach($v as $val)
					$resultParams .= $val.',';
		}
		
		return $resultParams;
	}
}

?>