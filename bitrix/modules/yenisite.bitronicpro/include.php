<?php

/**
 * yenisite.bitronic
 * @package yenisite.bitronic
 * @version 0.5
 */
 
 CModule::AddAutoloadClasses(
	"yenisite.bitronicpro",
	array(
		"CYSBitronicSettings" => "classes/general/CYSBitronicSettings.php",
		"CYSElementEvents" => "classes/general/CYSElementEvents.php",
		"CYSBitronicInit" => "classes/general/CYSBitronicInit.php",
	)
);

?>