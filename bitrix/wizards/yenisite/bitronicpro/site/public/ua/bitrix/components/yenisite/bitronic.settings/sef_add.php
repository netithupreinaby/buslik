<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");

CModule::IncludeModule('iblock');

$sef 	= COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
$arch 	= COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);
if ($sef === 'Y') die('end');
$ID = '';

$type 		= $_REQUEST['type'];

$type 		= str_replace("catalog_", "", $type);
$type 		= str_replace(SITE_ID.'_', "", $type);
$typeDir    = str_replace("_", "-", $type);

$ibDir = $_REQUEST['iblock'];

if ($arch == 'multi') {
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/page-([0-9]+)/#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&PAGEN_1=$6&page_count=$5",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page-([0-9]+)/#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&PAGEN_1=$5",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/page-([0-9]+)/#",
		"RULE"  =>  "SECTION_CODE=$1&PAGEN_1=$2",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/page_count-([0-9]+)/#",
		"RULE"  =>  "SECTION_CODE=$1&page_count=$2",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/sort-(.*[^-])-(.*)/#",
		"RULE"  =>  "SECTION_CODE=$1&order=$2&by=$3",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/view-(\\w*)/#",
		"RULE"  =>  "SECTION_CODE=$1&view=$2",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/(.*).html(.*)#",
		"RULE"  =>  "ELEMENT_CODE=$2",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/page-([0-9]+)/#",
		"RULE"  =>  "order=$2&by=$3&view=$1&PAGEN_1=$5&page_count=$4",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/view-(\\w*)/sort-(\\w*)-(\\w*)/page-([0-9]+)/#",
		"RULE"  =>  "order=$2&by=$3&view=$1&PAGEN_1=$4",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/#",
		"RULE"  =>  "order=$2&by=$3&view=$1&page_count=$4",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/view-(\\w*)/sort-(\\w*)-(\\w*)/#",
		"RULE"  =>  "order=$2&by=$3&view=$1",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/compare/(.*)/#",
		"RULE"  =>  "action=COMPARE&compareQuery=$1",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/view-(\\w*)/#",
		"RULE"  =>  "view=$1",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/page-([0-9]+)/#",
		"RULE"  =>  "PAGEN_1=$1",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/page_count-([0-9]+)/#",
		"RULE"  =>  "page_count=$1",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/sort-(.*[^-])-(.*)/#",
		"RULE"  =>  "order=$1&by=$2",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*)/(.*)#",
		"RULE"  =>  "SECTION_CODE=$1",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/(.*).html(.*)#",
		"RULE"  =>  "ELEMENT_CODE=$1",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);
	
	/* ABCD FILTER SEF */
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5&letter=$6&PAGEN_1=$7",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&letter=$5&PAGEN_1=$6",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/letter-([^/]{1,3})/(.*)#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5&letter=$6",
		"ID"    =>  $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/([^/]*)/?letter-([^/]{1,3})/page_count-([0-9]+)/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&letter=$2&page_count=$3",
		"ID" 	=> $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/([^/]*)/?page_count-([0-9]+)/letter-([^/]{1,3})/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&page_count=$2&letter=$3",
		"ID" 	=> $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/([^/]*)/?letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&letter=$2&PAGEN_1=$3",
		"ID" 	=> $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/".$ibDir."/([^/]*)/?letter-([^/]{1,3})/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&letter=$2",
		"ID" 	=> $ID,
		"PATH"  =>  "/".$typeDir."/".$ibDir."/index.php",
	);
	CUrlRewriter::Add($url);
	/* END ABCD FILTER SEF */
	
	$path = $_SERVER["DOCUMENT_ROOT"].'/'.$typeDir.'/'.$ibDir.'/index.php';

	if (file_exists($path)) {
		$str = file_get_contents($path);
		$str = str_replace('"SEF_MODE" => "Y"', '"SEF_MODE" => "N"', $str);
		$str = str_replace('SEF_URL_TEMPLATES', 'SEF_URL_TEMPLATES__SEF', $str);
		file_put_contents($path, $str);

		$fh = fopen($path, 'r+');
		$buffer = '';
		while (!feof($fh)) {
			$str = fgets($fh);
			if (strpos($str, "VARIABLE_ALIASES") === false) {
				$buffer .= $str;
			} else {
				$buffer .= '"VARIABLE_ALIASES" => array("SECTION_ID" => "SECTION_ID","ELEMENT_ID" => "ELEMENT_ID",)),false);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';
				break;
			}
		}
		fclose($fh);

		$fh = fopen($path, 'w+');
		fwrite($fh, $buffer);
		fclose($fh);
	}
} else {
	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/page-([0-9]+)/#",
		"RULE" => "SECTION_CODE=\$1&order=\$3&by=\$4&view=\$2&PAGEN_1=\$6&page_count=\$5",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/#",
		"RULE" => "SECTION_CODE=\$1&order=\$3&by=\$4&view=\$2&page_count=\$5",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/page-([0-9]+)/#",
		"RULE" => "SECTION_CODE=\$1&order=\$3&by=\$4&view=\$2&PAGEN_1=\$5",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/view-(\\w*)/sort-(\\w*)-(\\w*)/#",
		"RULE" => "SECTION_CODE=\$1&order=\$3&by=\$4&view=\$2",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/page_count-([0-9]+)/#",
		"RULE" => "SECTION_CODE=\$1&page_count=\$2",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/sort-(.*[^-])-(.*)/#",
		"RULE" => "SECTION_CODE=\$1&order=\$2&by=\$3",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/page-([0-9]+)/#",
		"RULE" => "SECTION_CODE=\$1&PAGEN_1=\$2",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.*)/view-(\\w*)/#",
		"RULE" => "SECTION_CODE=\$1&view=\$2",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.+)/(.+).html(.*)#",
		"RULE" => "SECTION_CODE=\$1&ELEMENT_CODE=\$2",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/compare/(.*)/(.*)#",
		"RULE" => "action=COMPARE&compareQuery=\$1",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.+)/(.+)#",
		"RULE" => "SECTION_CODE=\$1",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);
	
	$url = array(
		"CONDITION" => "#^/".$typeDir."/(.+)/#",
		"RULE" => "SECTION_CODE=\$1",
		"ID" => $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	/* ABCD FILTER SEF */
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5&letter=$6&PAGEN_1=$7",
		"ID"    =>  $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&letter=$5&PAGEN_1=$6",
		"ID"    =>  $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);

	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/([^/]*)/?view-(\\w*)/sort-(\\w*)-(\\w*)/page_count-([0-9]+)/letter-([^/]{1,3})/(.*)#",
		"RULE"  =>  "SECTION_CODE=$1&order=$3&by=$4&view=$2&page_count=$5&letter=$6",
		"ID"    =>  $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/([^/]*)/?letter-([^/]{1,3})/page_count-([0-9]+)/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&letter=$2&page_count=$3",
		"ID" 	=> $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/([^/]*)/?page_count-([0-9]+)/letter-([^/]{1,3})/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&page_count=$2&letter=$3",
		"ID" 	=> $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/([^/]*)/?letter-([^/]{1,3})/page-([0-9]+)/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&letter=$2&PAGEN_1=$3",
		"ID" 	=> $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);
	$url = array(
		"CONDITION" =>  "#^/".$typeDir."/([^/]*)/?letter-([^/]{1,3})/(.*)#",
		"RULE" 	=> "SECTION_CODE=$1&letter=$2",
		"ID" 	=> $ID,
		"PATH" => "/".$typeDir."/index.php",
	);
	CUrlRewriter::Add($url);
	/* END ABCD FILTER SEF */
	
	$path = $_SERVER["DOCUMENT_ROOT"].'/'.$typeDir.'/index.php';

	if (file_exists($path)) {
		$str = file_get_contents($path);
		$str = str_replace('"SEF_MODE" => "Y"', '"SEF_MODE" => "N"', $str);
		$str = str_replace('SEF_URL_TEMPLATES', 'SEF_URL_TEMPLATES__SEF', $str);
		file_put_contents($path, $str);

		$fh = fopen($path, 'r+');
		$buffer = '';
		while (!feof($fh)) {
			$str = fgets($fh);
			if (strpos($str, "VARIABLE_ALIASES") === false) {
				$buffer .= $str;
			} else {
				$buffer .= '"VARIABLE_ALIASES" => array("SECTION_ID" => "SECTION_ID","ELEMENT_ID" => "ELEMENT_ID",)),false);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';
				break;
			}
		}
		fclose($fh);

		$fh = fopen($path, 'w+');
		fwrite($fh, $buffer);
		fclose($fh);
	}
}

?>