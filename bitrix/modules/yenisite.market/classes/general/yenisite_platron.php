<?
class CMarketPlatronSignature {

	/**
	 * Get script name from URL (for use as parameter in self::make, self::check, etc.)
	 *
	 * @param string $url
	 * @return string
	 */
	public static function getScriptNameFromUrl ( $url )
	{
		$path = parse_url($url, PHP_URL_PATH);
		$len  = strlen($path);
		if ( $len == 0  ||  '/' == $path{$len-1} ) {
			return "";
		}
		return basename($path);
	}

	/**
	 * Get name of currently executed script (need to check signature of incoming message using self::check)
	 *
	 * @return string
	 */
	public static function getOurScriptName ()
	{
		return self::getScriptNameFromUrl( $_SERVER['PHP_SELF'] );
	}

	/**
	 * Creates a signature
	 *
	 * @param array $arrParams  associative array of parameters for the signature
	 * @param string $strSecretKey
	 * @return string
	 */
	public static function make ( $strScriptName, $arrParams, $strSecretKey )
	{
		return md5( self::makeSigStr($strScriptName, $arrParams, $strSecretKey) );
	}

	/**
	 * Verifies the signature
	 *
	 * @param string $signature
	 * @param array $arrParams  associative array of parameters for the signature
	 * @param string $strSecretKey
	 * @return bool
	 */
	public static function check ( $signature, $strScriptName, $arrParams, $strSecretKey )
	{
		return (string)$signature === self::make($strScriptName, $arrParams, $strSecretKey);
	}


	/**
	 * Returns a string, a hash of which coincide with the result of the make() method.
	 * WARNING: This method can be used only for debugging purposes!
	 *
	 * @param array $arrParams  associative array of parameters for the signature
	 * @param string $strSecretKey
	 * @return string
	 */
	static function debug_only_SigStr ( $strScriptName, $arrParams, $strSecretKey ) {
		return self::makeSigStr($strScriptName, $arrParams, $strSecretKey);
	}


	private static function makeSigStr ( $strScriptName, $arrParams, $strSecretKey ) {
		unset($arrParams['pg_sig']);

		ksort($arrParams);

		array_unshift($arrParams, $strScriptName);
		array_push   ($arrParams, $strSecretKey);

		return join(';', $arrParams);
	}

	/********************** singing XML ***********************/

	/**
	 * make the signature for XML
	 *
	 * @param string|SimpleXMLElement $xml
	 * @param string $strSecretKey
	 * @return string
	 */
	public static function makeXML ( $strScriptName, $xml, $strSecretKey )
	{
		$arrFlatParams = self::makeFlatParamsXML($xml);
		return self::make($strScriptName, $arrFlatParams, $strSecretKey);
	}

	/**
	 * Verifies the signature of XML
	 *
	 * @param string|SimpleXMLElement $xml
	 * @param string $strSecretKey
	 * @return bool
	 */
	public static function checkXML ( $strScriptName, $xml, $strSecretKey )
	{
		if ( ! $xml instanceof SimpleXMLElement ) {
			$xml = new SimpleXMLElement($xml);
		}
		$arrFlatParams = self::makeFlatParamsXML($xml);
		return self::check((string)$xml->pg_sig, $strScriptName, $arrFlatParams, $strSecretKey);
	}

	/**
	 * Returns a string, a hash of which coincide with the result of the makeXML() method.
	 * WARNING: This method can be used only for debugging purposes!
	 *
	 * @param string|SimpleXMLElement $xml
	 * @param string $strSecretKey
	 * @return string
	 */
	public static function debug_only_SigStrXML ( $strScriptName, $xml, $strSecretKey )
	{
		$arrFlatParams = self::makeFlatParamsXML($xml);
		return self::makeSigStr($strScriptName, $arrFlatParams, $strSecretKey);
	}

	/**
	 * Returns flat array of XML params
	 *
	 * @param (string|SimpleXMLElement) $xml
	 * @return array
	 */
	private static function makeFlatParamsXML ( $xml, $parent_name = '' )
	{
		if ( ! $xml instanceof SimpleXMLElement ) {
			$xml = new SimpleXMLElement($xml);
		}

		$arrParams = array();
		$i = 0;
		foreach ( $xml->children() as $tag ) {

			$i++;
			if ( 'pg_sig' == $tag->getName() )
				continue;

			/**
			 * ��� ������ ���� tag001subtag001
			 * ����� ����� ���� ����� ��������� ������������� � ��������� ���� �� ���������� ��� ����������
			 */
			$name = $parent_name . $tag->getName().sprintf('%03d', $i);

			if ( $tag->children() ) {
				$arrParams = array_merge($arrParams, self::makeFlatParamsXML($tag, $name));
				continue;
			}

			$arrParams += array($name => (string)$tag);
		}

		return $arrParams;
	}
}

class CMarketPlatronIO {
	static public function getRequest ()
	{
		global $HTTP_RAW_POST_DATA;

		if (isset($_POST['pg_xml'])) {
			$inData['pg_xml'] = $_POST['pg_xml'];
		}
		elseif (isset($_POST['pg_sig'])) {
			$inData = $_POST;
		}
		elseif (isset($_GET['pg_sig'])) {
			$inData = $_GET;
		}
		elseif ( !empty($HTTP_RAW_POST_DATA) ) {
			$inData['pg_xml'] = $HTTP_RAW_POST_DATA;
		}
		elseif ( ($HTTP_RAW_POST_DATA = file_get_contents("php://input")) ) {
			// get HTTP_RAW_POST_DATA if it is not set in php.ini to always_populate_raw_post_data
			$inData['pg_xml'] = $HTTP_RAW_POST_DATA;
		}
		else 
			return null;

		return $inData;
		
	}
		
	static public function makeResponse( $strScriptName, $strShopKey, $strStatus, $strDescription, $strSalt = null)
	{
		global $APPLICATION;
		if(!$strSalt)
			$strSalt = uniqid();
		
		$APPLICATION->RestartBuffer();
		
		$arrResponse = array();
		$arrResponse['pg_salt'] = rand(21,43433);
		$arrResponse['pg_status'] = $strStatus;
		$arrResponse['pg_description'] = $strDescription;
		$arrResponse['pg_sig'] = CMarketPlatronSignature::make($strScriptName, $arrResponse, $strShopKey);

		header("Content-Type: text/xml");
		header("Pragma: no-cache");
		$strResponse = "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n";
		$strResponse .= "<response>";
			$strResponse .= "<pg_salt>".$arrResponse['pg_salt']."</pg_salt>";
			$strResponse .= "<pg_status>".$arrResponse['pg_status']."</pg_status>";
			$strResponse .= "<pg_description>".$arrResponse['pg_description']."</pg_description>";
			$strResponse .= "<pg_sig>".$arrResponse['pg_sig']."</pg_sig>";
		$strResponse .= "</response>";
		echo $strResponse;
		exit();
	}
	
	/**
	 *
	 * @param string $strPhone
	 * @return null|string 
	 */
	public static function checkAndConvertUserPhone ( $strPhone )
	{
		$strPhone = ltrim(self::toDigits(trim($strPhone)), '0');
		
		if($strPhone[0]==8 && $strPhone[1]==9 && strlen($strPhone)==11)
			$strPhone[0] = 7;
		elseif (strlen($strPhone)==10 && $strPhone[0]=='9') {
			$strPhone='7'.$strPhone;
		}
		
		if (strlen($strPhone)<9) {
			return null;
		}
		elseif (strlen($strPhone)>14) {
			return null;
		}
		elseif ($strPhone[0]==7 && strlen($strPhone)!=11)	{
			return null;
		}
		elseif (preg_match('/^70|^789|^89/', $strPhone)) {
			return null;
		}
		
		return $strPhone;
	}
	
	/**
	 * 
	 */
	private static function toDigits($str)
	{
		return preg_replace("/\D/s", "", $str);
	}

	/**
	 * 
	 */
	public static function emailIsValid($strEmail)
	{
		return preg_match('/^[\w.+-]+@[\w.-]+\.\w{2,}$/s', $strEmail);
	}
}