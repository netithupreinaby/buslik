<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
	//Группа опросов
if(CModule::IncludeModule("vote"))
{
	$arVoted = CVoteChannel::GetList($by='ID', $order='ASC', Array('SYMBOLIC_NAME'=>'BITRONIC_DEMO'), $is_filtered=true)->Fetch() ;
	if(!is_array($arVoted))
	{
	
		/* $arFields = array_intersect_key($_REQUEST,
			array_flip(array("TITLE", "SYMBOLIC_NAME", "ACTIVE", "HIDDEN", "C_SORT", "VOTE_SINGLE", "USE_CAPTCHA", "SITE", "GROUP_ID")));
		if (is_array($arFields["SITE"]))
			$arFields["FIRST_SITE_ID"] = reset($arFields["SITE"]);
		foreach(array("ACTIVE", "HIDDEN", "VOTE_SINGLE", "USE_CAPTCHA") as $key)
			if (!isset($arFields[$key]))
				$arFields[$key] = "N";
		foreach(array("SITE", "GROUP_ID") as $key)
			if (!isset($arFields[$key]))
				$arFields[$key] = array(); */
			$arFields = array('SITE'=>Array(WIZARD_SITE_ID),
								'VOTES' => 0, 
								'C_SORT' => 100,
								'ACTIVE' => 'Y',
								'VOTE_SINGLE' => 'Y',
								'USE_CAPTCHA' => 'N',
								'HIDDEN' => 'N',
								'SYMBOLIC_NAME' => 'BITRONIC_DEMO',
								'TITLE' => 'BITRONIC_DEMO',
								);
			
		echo '<pre>'; print_r($arFields); echo '</pre>';
		$ID_channel = CVoteChannel::Add($arFields);
		var_dump($ID_channel);
		//Опрос
			$arFields = array(
			"CHANNEL_ID"		=> $ID_channel,
			"C_SORT"			=> intVal("100"),
			"ACTIVE"			=> "Y",
			"DATE_START"		=> date("d.m.Y 00:00:00"),
			"DATE_END"			=> "01.01.2031 00:00:00",
			"TITLE"				=> "Demo opros",
			"DESCRIPTION"		=> "",
			"DESCRIPTION_TYPE"	=> "html",
			"IMAGE_ID"			=> 0,
			"EVENT1" => "vote",
			"EVENT2" => "demo",
			"UNIQUE_TYPE" => 12, // IP
			"DELAY"				=> 10,
			"DELAY_TYPE" => "M",
			"TEMPLATE"			=> "default.php",
			"RESULT_TEMPLATE"	=> "",
			"NOTIFY"			=> "N"
		);

		$result = false;
		$arFields["IMAGE_ID"]["del"] = $_POST["IMAGE_ID_del"];
		
		$ID_vote = CVote::Add($arFields);
		
		//Вопрос
		$arFields = array(
			"ACTIVE"		=> "Y",
			"VOTE_ID"		=> $ID_vote,
			"C_SORT"		=> CVoteQuestion::GetNextSort($ID_vote),
			"QUESTION"		=> "Demo question?",
			"QUESTION_TYPE"	=> "html",
			"IMAGE_ID"		=> "", 
			"DIAGRAM"		=> "Y",
			"REQUIRED"		=> "N",
			"DIAGRAM_TYPE"	=> VOTE_DEFAULT_DIAGRAM_TYPE, 
			"TEMPLATE"		=> "default.php",
			"TEMPLATE_NEW"	=> "default.php"
		);
			
		$ID_question = CVoteQuestion::Add($arFields);
		//Ответы
		for ($i = 1; $i <= 5; $i++) {
			$arAnswer = array(
				"ID" => $i, 
				"QUESTION_ID" => $ID_question, 
				"ACTIVE" => 'Y', 
				"C_SORT" => "".$i*100, 
				"MESSAGE" => 'demo_answer '.$i, 
				"FIELD_TYPE" => "", 
				"FIELD_WIDTH" => "", 
				"FIELD_HEIGHT" => "", 
				"FIELD_PARAM" => "", 
				"COLOR" => ""
				
			);
			CVoteAnswer::Add($arAnswer);
		};
	}
}?>