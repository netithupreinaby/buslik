<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

session_start();
$arParams = $_SESSION['YMRS'];
$arParams["GRADE"] = IntVal($arParams["GRADE"]) - 3;
$arParams["PAGE"] =  $_REQUEST["PAGE"] ? IntVal($_REQUEST["PAGE"]) : 1;

if ($this->StartResultCache(false, $USER->GetGroups()))
{
	/*----API FIX*/
	if(intval($arParams["COUNT"]) == 1) {
		$arParams["COUNT"] = 3;
		$count_one = 0;
		$del = $arParams["PAGE"]/3 + 0.9;
		if($del-floor($del) > 0.5)
		{
			$count_one = 1;
			if($del-floor($del) > 0.8)
				$count_one = 2;
		}
		$arParams["PAGE"] =	floor($del);
	}
	/*---/-API FIX*/
	$arOptions = array(
		"sort", 	
		"how",
		"grade",
		"count",
	);
			
	$strOptions = "";

	foreach ($arOptions as $opt) {
		if ($opt == "grade" and $arParams["GRADE"] == -3) continue;
		$strOptions = $strOptions."&".$opt.'='.$arParams[strtoupper($opt)];
	}
  //+============================================================================================      
        
        
	$query_page = 'https://api.content.market.yandex.ru/v1/model/'.$arParams["MODEL"].'/opinion.json?geo_id=0&page='.$arParams["PAGE"].$strOptions;
	function get_web_page( $url, $accesstoken )
	{

		$headr = array();
		$headr[] = 'api.content.market.yandex.ru';
		$headr[] = 'Accept: */*';
		$headr[] = 'Authorization: '.$accesstoken;
		
		$options = array(
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER         => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => false,    // don't follow redirects
			CURLOPT_ENCODING       => "",       // handle all encodings
			CURLOPT_USERAGENT      => "spider", // who am i
			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER => $headr,
		);

		$ch      = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );

		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
			  
	}
	
	$json = get_web_page( $query_page, $arParams["ACCESSTOKEN"]);



	if ($json['errno'] == '0'):
		$arReviews = json_decode($json['content'], true);

		if ($arReviews["modelOpinions"]):
			if(LANG_CHARSET != 'UTF-8') { //'UTF-8' to 'windows-1251' or other
				foreach ($arReviews["modelOpinions"]["opinion"] as &$review) {
					$review["text"]	= iconv('UTF-8', LANG_CHARSET, $review["text"]);
					$review["contra"] = iconv('UTF-8', LANG_CHARSET, $review["contra"]);
					$review["pro"]	= iconv('UTF-8', LANG_CHARSET, $review["pro"]);
					if($review["author"])
						$review["author"] = iconv('UTF-8', LANG_CHARSET, $review["author"]);
				}unset($review);
			}
			/*----API FIX*/
			if(isset($count_one))
			{
				$arReviews["modelOpinions"]["count"] = 1;
				$arReviews["modelOpinions"]["page"] =  IntVal($_REQUEST["PAGE"]) ;
				$arReviews["modelOpinions"]["opinion"] = array($arReviews["modelOpinions"]["opinion"][$count_one]);
			}
			/*---/-API FIX*/
		endif;
		
		$arResult["REVIEWS"] = $arReviews;
	else:
		$arResult["REVIEWS"] = $json['errno'];
	endif;

	//abort
	if(!is_array($arResult["REVIEWS"]))
		$this->AbortResultCache();
	else
	{
		if(isset($arResult["REVIEWS"]["errors"]))
			$this->AbortResultCache();	
	}
	
	$this->IncludeComponentTemplate();
}

?>
