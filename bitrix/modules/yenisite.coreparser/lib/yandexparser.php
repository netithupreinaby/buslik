<?php

namespace Yenisite\CoreParser;

abstract class YandexParser
{
	const moduldeID = 'yenisite.coreparser';
	const ERROR_NONE    = 0;
	const ERROR_CAPTCHA = 1;
	const ERROR_OTHER   = 2;
	const CACHE_TIME    = 3600;
	const CACHE_PATH    = '/yenisite.coreparser/';
	protected static $fileLog = '' ;
	protected static $arProxies = NULL;
	protected static $errCode = self::ERROR_NONE;
	protected static $bLog = true;

	protected static function clearLog()
	{
		if (self::$fileLog)
		{
			if (file_exists(self::$fileLog)) {
				$arLog = json_decode(file_get_contents(self::$fileLog));
			}
			if(!is_array($arLog)){
				$arLog = array() ;
			}

			if(count($arLog) > 100){
				file_put_contents(self::$fileLog, '', LOCK_EX);
			}
			else{
				$arLog[] = array('s'=>9999, 'p'=>false) ;
				file_put_contents(self::$fileLog, json_encode($arLog), LOCK_EX);
			}
		}
	}

	protected static function writeLog($n_status, $param = '')
	{
		if(self::$fileLog && self::$bLog)
		{
			$arLog = json_decode(file_get_contents(self::$fileLog));
			if(!is_array($arLog))
				$arLog = array() ;

			$arLog[] = array('s'=>$n_status, 'p'=>$param) ;

			file_put_contents(self::$fileLog, json_encode($arLog), LOCK_EX);
		}
	}

	protected static function disableLog()
	{
		self::$bLog = false;
	}

	protected static function enableLog()
	{
		self::$bLog = true;
	}

	protected static function setLogFile($id)
	{
		if (($id = intval($id)) < 1) return false;
		self::$fileLog = $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/js/' . static::moduldeID . '/log/log-' . $id . '.txt';
		self::clearLog();
	}

	protected static function getProxies()
	{
		$modId = static::moduldeID;
		if (isset($_SESSION[$modId]['proxyServer'])) {
			$strProxy = $_SESSION[$modId]['proxyServer'];
			if (isset($_SESSION[$modId]['proxyServerAuth'])) {
				$strProxy .= '|' . $_SESSION[$modId]['proxyServerAuth'];
			}
			return array($strProxy);
		}
		if (self::$arProxies !== NULL) return self::$arProxies;
		if (isset($_SESSION[$modId]['arProxies'])) {
			self::$arProxies = $_SESSION[$modId]['arProxies'];
			return self::$arProxies;
		}

		$proxyStr = \COption::GetOptionString($modId, 'proxy_ip', '');
		$arProxyServer = explode(chr(10),$proxyStr);
		$unsetKeys = array();
		foreach ($arProxyServer as $key => $proxyStr) {
			$arProxyServer[$key] = str_replace( array(' ', chr(13)), '', $proxyStr );
			if (empty($arProxyServer[$key])) $unsetKeys[] = $key;
		}
		foreach ($unsetKeys as $key) {
			unset($arProxyServer[$key]);
		}
		self::$arProxies = $arProxyServer;
		$_SESSION[$modId]['arProxies'] = $arProxyServer;
		return self::$arProxies;
	}

	protected static function releaseProxy()
	{
		$modId = static::moduldeID;
		unset($_SESSION[$modId]['proxyServer']);
		unset($_SESSION[$modId]['proxyServerAuth']);
		unset($_SESSION[$modId]['userAgent']);
	}

	protected static function removeProxy($proxyIP)
	{
		self::releaseProxy();
		self::getProxies();

		foreach (self::$arProxies as $key => $proxyStr) {
			if (strpos($proxyStr, $proxyIP) !== 0) continue;
			unset(self::$arProxies[$key]);
			break;
		}
		if (count(self::$arProxies) > 0) {
			$_SESSION[static::moduldeID]['arProxies'] = self::$arProxies;
		} else {
			self::$arProxies = NULL;
			unset($_SESSION[static::moduldeID]['arProxies']);
		}
	}

	static function browser($url, $referer=false, $use_anonymouse=true, $iteration=0, $c_file="", $bImage=false)
	{
		if (isset($_SESSION[static::moduldeID]['captcha']) && isset($_REQUEST['captcha'])) {
			$html = self::sendCaptcha($_REQUEST['captcha']);
		} else {
			$html = self::browserInvoke($url, $referer, $use_anonymouse, $iteration, $c_file, $bImage);
		}

		if (self::$errCode == self::ERROR_CAPTCHA) {
			self::prepareCaptcha($html, $url);
		}
		return $html;
	}

	static function browserInvoke($url, $referer=false, $use_anonymouse=true, $iteration=0, $c_file="", $bImage=false)
	{
		$modId = static::moduldeID;
		if (strpos($url, '//') === 0) {
			$url = 'https:' . $url;
		}
		$url_for_repeat = $url;

		libxml_use_internal_errors(true);
		$arProxyServer = self::getProxies();
		if(!empty($arProxyServer))
		{
			$use_proxy = true;
			$use_anonymouse=false;
		}
		$iteration++;
		$max_iteration = 2;
		if(empty($c_file))
			$c_file=$_SERVER['DOCUMENT_ROOT']."/yandex_cook.txt";
		$user_cookie_file = $c_file;

		if(!$use_proxy)
		{
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//Work blocked if not use proxy servers
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			self::writeLog(21);
			self::$errCode = self::ERROR_OTHER;
			return false;

			self::writeLog(1, $url);
			$anonimizer_type = \COption::GetOptionString($modId, 'anonimizer', 'seogadget');

			switch ($anonimizer_type)
			{
				case 'seogadget':
				default:
					//for www.seogadget.ru/anonymizer
					//$arDomains = array('https://market.yandex.ru/',            'https://mdata.yandex.net/');
					//$arProxies = array('http://sss.market.yandex.ru.seogadpxy.ru/', 'http://sss.mdata.yandex.net.seogadpxy.ru/');
					//$url = str_replace($arDomains, $arProxies, $url);
					$referer = $url;
					$url = 'http://sss.market.yandex.ru.seogadpxy.ru/home287/cmd?mode=STD';
					$use_anonymouse = false;
					$proxyServer = '.seogadpxy.ru';
					break;
			}
		}
		else
		{
			$max_iteration = \COption::GetOptionInt($modId, 'proxy_retries', 5);
			$proxyServer = $arProxyServer[array_rand($arProxyServer, 1)];
			$arProxyServer = explode('|',$proxyServer);
			if(count($arProxyServer)>1)
			{
				$proxyServer = $arProxyServer[0];
				$proxyServerAuth = $arProxyServer[1];
			}
		}
		$arUserAgent = Array(
			'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.48822)',
			'Opera/9.80 (X11; Linux x86_64; U; ru) Presto/2.2.15 Version/10.10',
			'Mozilla/5.0 (X11; Linux x86_64; U; ru; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 10.10',
			'Mozilla/4.0 (compatible; MSIE 6.0; X11; Linux x86_64; ru) Opera 10.10',
			'Mozilla/5.0 (X11; Linux x86_64; U; ru; rv:1.8.1) Gecko/20061208 Firefox/2.0.0',
			'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru)',
			'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36',
			'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:22.0) Gecko/20100101 Firefox/22.0',
			'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
		);
		if (!function_exists('curl_init')) {
			self::writeLog(14);
			// return false; //???
		}
		$ch = curl_init($url);
		self::writeLog(2, $url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		//curl_setopt($ch, CURLOPT_HEADER, true);
		if($use_proxy)
		{
			self::writeLog(3, $proxyServer);
			$timeout = \COption::GetOptionInt($modId, 'proxy_timeout', 5);
			if (strpos($url, 'captcha') !== false) {
				$timeout += 10;
			}
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt($ch, CURLOPT_PROXY, $proxyServer);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout+1);
			if($proxyServerAuth)
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyServerAuth);
		}
		else
		{
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		}
		if(!$use_anonymouse)
		{
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $user_cookie_file);
			curl_setopt($ch, CURLOPT_COOKIEJAR,  $user_cookie_file);
		}
		$userAgent = isset($_SESSION[$modId]['userAgent'])
		           ? $_SESSION[$modId]['userAgent']
		           : $arUserAgent[array_rand($arUserAgent, 1)];
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_REFERER, ($referer ? $referer : 'https://www.google.ru/'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		//curl_setopt($ch, CURLOPT_INTERFACE, "93.157.243.133");

		$html = curl_exec($ch);

		$errno = curl_errno($ch);
		self::writeLog(4, $errno) ;
		$HTTPcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($errno <= 0) {
			self::writeLog(13, $HTTPcode) ;
		}
		$info = curl_getinfo($ch);
		/*
		//UNCOMMENT TO SEE SYSTEM LOG
		$syslog = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.static::moduldeID.'/syslog.txt';
		$logStr = file_exists($syslog) ? file_get_contents($syslog) : '';
		$logStr .= "\nError number: " . $errno;
		$logStr .= "\nError string: " . curl_error($ch);
		$logStr .= "\nConnection info: " . var_export($info, true);
		$logStr .= "\n\nExecution result: " . var_export($html, true);
		file_put_contents($syslog, $logStr."\n\n-----\n", LOCK_EX);
		*/
		curl_close($ch);
		if(!$use_proxy) {
			if ($proxyServer == '.seogadpxy.ru') {
				$html = preg_replace('#//sss.([^/]+).seogadpxy.ru/#', '//$1/', $html);
			} else {
				$html = str_replace($proxyServer, "", $html);
			}
		}

		$bError = false;

		if($html===false || empty($html) || floor($HTTPcode / 100) >= 4 || $errno)
		{
			$bError = true;
			self::writeLog(5);
			if ($bImage && $HTTPcode == 404) {
				$max_iteration = 0;
			}
		}
		elseif (
			strpos($html, 'href="http://yandex.') === false &&
			strpos($html, 'href="https://yandex.') === false &&
			strpos($html, 'href="http://www.yandex.') === false &&
			strpos($html, 'href="https://www.yandex.') === false &&
			strpos($html, 'href="//www.yandex.') === false &&
			strpos($info['content_type'], 'text/html') !== false
		) {
			$bError = true;
			self::writeLog(17);
		}
		elseif (class_exists('DomDocument'))
		{
			$dom = new \DomDocument();
			$dom->loadHTML($html);
			$xpath = new \DomXPath( $dom );
			$errors = $xpath->query(".//td[@class='headCode']");
			foreach($errors as $error)
			{
				if($error->nodeValue == '403')
				{
					$bError = true;
					self::writeLog(6) ;
				}
			}
			$captcha = $xpath->query(".//img[contains(concat(' ', normalize-space(@class), ' '), ' form__captcha ')]");
			if($captcha->length)
			{
				self::$errCode = self::ERROR_CAPTCHA;
				self::writeLog(7);
			}

		} else {
			//class_exists('DomDocument') == false
			$bError = true;
			self::writeLog(15);
			//die() ???
		}

		if ($bError) {
			self::$errCode = self::ERROR_OTHER;
			if ($use_proxy) {
				self::removeProxy($proxyServer);
			}
			if($iteration < $max_iteration) {
				$html = self::browserInvoke($url_for_repeat, $referer, !$use_proxy, $iteration);
			}
		}
		else {
			if (self::$errCode != self::ERROR_CAPTCHA) {
				self::$errCode  = self::ERROR_NONE;
			}
			if ($use_proxy) {
				$_SESSION[$modId]['proxyServer'] = $proxyServer;
				$_SESSION[$modId]['userAgent'] = $userAgent;
				if ($proxyServerAuth) {
					$_SESSION[$modId]['proxyServerAuth'] = $proxyServerAuth;
				}
			}
		}

		//file_put_contents(self::$fileLog.'.html', $html, LOCK_EX);
		return $html;

	}


	static function prepareCaptcha($content, $referer = '')
	{
		$dom = new \DomDocument();
		$dom->loadHTML($content);
		$xpath = new \DomXPath( $dom );
		$inputs = $xpath->query(".//div[@class='content']//form/input[@type='hidden']");
		foreach ($inputs as $input) {
			$_SESSION[static::moduldeID]['captcha'][$input->getAttribute('name')] = $input->getAttribute('value');
		}
		$_SESSION[static::moduldeID]['captcha_referer'] = $referer;
		$captcha = $xpath->query(".//img[@class='image form__captcha']")->item(0);

		self::$errCode = self::ERROR_NONE;
		$im = self::browserInvoke($captcha->getAttribute('src'), $referer);
		if (self::$errCode > self::ERROR_NONE) return;

		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/upload/tmp/"))
			mkdir($_SERVER['DOCUMENT_ROOT']."/upload/tmp/");
		$tmp_image_path = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/captcha.gif";
		file_put_contents($tmp_image_path, $im, LOCK_EX);
		self::$errCode = self::ERROR_CAPTCHA;
	}

	static function sendCaptcha($text)
	{
		self::writeLog(18);
		$modId = static::moduldeID;
		$tmp_image_path = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/captcha.gif";
		if (@file_exists($tmp_image_path)) {
			unlink($tmp_image_path);
		}
		$url = '';
		$domain = 'market.yandex.ru';
		if (is_array($_SESSION[$modId]['captcha'])) {
			foreach ($_SESSION[$modId]['captcha'] as $key => $value) {
				$url .= '&' . $key . '=' . urlencode($value);
			}
			$matches = null;
			$retpath = $_SESSION[$modId]['captcha']['retpath'];
			if (0 < strlen($retpath) && preg_match('#://([^/]+)/#', $retpath, $matches)) {
				$domain = $matches[1];
			}
			unset($_SESSION[$modId]['captcha']);
		}
		$url = 'https://'. $domain .'/checkcaptcha?rep=' . urlencode($text) . $url;
		return self::browserInvoke($url, $_SESSION[$modId]['captcha_referer']);
	}

	static function normalizeImageURL($URL)
	{
		if (strpos($URL, '//') === 0) $URL = 'http:' . $URL;
		if (strpos($URL, '/')  === 0) $URL = 'http://market.yandex.ru' . $URL;
		return $URL;
	}

	/**
	 * Parse and extract price found in HTML
	 *
	 * @param string $price - price string needed to be parsed
	 * @return float
	 */
	static function parsePrice($price)
	{
		$price = preg_replace('#[^\d]*#', '', $price);
		return floatval($price);
	}

	static function getModelListByURL($url)
	{
		$arReturn = array();
		$obCache = new \CPHPCache;
		$cacheID = 'getModelListByURL_'.$url;
		if ($obCache->InitCache(static::CACHE_TIME, $cacheID, static::CACHE_PATH)) {
			return $obCache->getVars();
		}
		$html = self::browser($url);

		if (self::$errCode != self::ERROR_NONE) {
			$arReturn['length'] = 0;
			$arReturn['error'] = self::$errCode;
			return $arReturn;
		}

		//j - cards counter
		$j = 0;

		//file_put_contents(self::$fileLog.'.html', $html, LOCK_EX);
		$crawler = new \Symfony\Component\DomCrawler\Crawler();
		$crawler->addHtmlContent($html, 'UTF-8');

		//search for models
		$obModels = $crawler->filter('.b-model');

		foreach ($obModels as $domElement) {
			$modelCrawler = new \Symfony\Component\DomCrawler\Crawler($domElement);

			$id    = $modelCrawler->attr('data-quickview');
			if(($id= intval($id)) <= 0) continue;
			$img   = $modelCrawler->filter('.b-model__image img')->attr('src');
			$name  = $modelCrawler->filter('.b-model__title')->text();
			$price = $modelCrawler->filter('.b-model__price .b-prices .b-prices__num')->first()->text();

			$arReturn[$j++] = array(
				'ID'     => $id,
				'IMAGES' => self::normalizeImageURL($img),
				'NAME'   => strip_tags($name),
				'PRICE'  => self::parsePrice($price)
			);
		}

		//search for cards
		$arOffers = array();
		$obCards = $crawler->filter('.snippet-card');

		foreach ($obCards as $domElement) {
			$cardCrawler = new \Symfony\Component\DomCrawler\Crawler($domElement);

			$id = $cardCrawler->attr('data-id');
			$img = $cardCrawler->filter('.snippet-card__image img')->attr('src');
			$name = $cardCrawler->filter('.snippet-card__header')->text();
			$price = $cardCrawler->filter('.snippet-card__price')->text();

			$arCard = array(
				'IMAGES' => self::normalizeImageURL($img),
				'PRICE'  => self::parsePrice($price),
				'SHOP'   => $shop,
				'NAME'   => strip_tags($name)
			);

			if (empty($id) || strpos($id, 'offer-') === 0) {
				$descr = $cardCrawler->filter('.snippet-card__desc');
				$shop = $cardCrawler->filter('.link_type_shop-name')->text();
				if (count($descr)) {
					$arCard['DESCRIPTION'] = trim($descr->text());
				}
				$arCard['SHOP'] = trim($shop);
				$arOffers[] = $arCard;
			} elseif (strpos($id, 'model-') === 0) {
				$id = str_replace('model-', '', $id);
				$descr = $cardCrawler->filter('.snippet-card__desc-list');
				$arCard['ID'] = $id;
				if (count($descr)) {
					$arCard['DESCRIPTION'] = trim($descr->text());
				}
				$arReturn[$j++] = $arCard;
			}
			unset($arCard, $cardCrawler);
		}

		$obCells = $crawler->filter('.snippet-cell');

		foreach ($obCells as $domElement) {
			$cellCrawler = new \Symfony\Component\DomCrawler\Crawler($domElement);

			$id = $cellCrawler->filter('.metrika__pixel');
			$img = $cellCrawler->filter('.snippet-cell__image img')->attr('src');
			$name = $cellCrawler->filter('.snippet-cell__header')->text();
			$price = $cellCrawler->filter('.snippet-cell__price')->text();

			$arCell = array(
				'IMAGES' => self::normalizeImageURL($img),
				'PRICE'  => self::parsePrice($price),
				'NAME'   => strip_tags($name)
			);

			if (count($id)) {
				$arCell['ID'] = $id->attr('data-id');
				$descr = $cellCrawler->filter('.snippet-cell__desc-list');
				if (count($descr)) {
					$arCell['DESCRIPTION'] = trim($descr->text());
				}
				$arReturn[$j++] = $arCell;
			} else {
				$descr = $cellCrawler->filter('.snippet-cell__desc');
				if (count($descr)) {
					$arCell['DESCRIPTION'] = trim($descr->text());
				}
				$arOffers[] = $arCell;
			}
			unset($arCell, $cellCrawler);
		}

		$arReturn = array_merge($arReturn, $arOffers);
		$arReturn['length'] = $j;
		$arReturn['length2'] = $j + count($arOffers); //all items
		
		foreach($crawler->filter('.noresult') as $noResult)
		{
			$arReturn['length'] = $arReturn['length2'] = 0;
			$arOffers = array();
			break;
		}

		self::writeLog(12, $arReturn['length']);
		self::writeLog(16, count($arOffers)); //items without pages

		if($obCache->StartDataCache()) {
			$obCache->EndDataCache($arReturn);
		}

		return $arReturn;
	}

	/**
	 * Get link of search page according to module settings
	 *
	 * @param string $name - name of your product, must be in UTF-8
	 * @param string $country - 1st level domain (by|kz|ru|ua)
	 */
	public static function getSearchLink($name, $country = 'ru')
	{
		$name = htmlspecialchars_decode($name);
		self::writeLog(9, $name);

		$name = urlencode($name);
		$brands = (\COption::GetOptionString(static::moduldeID, 'brands', 'N') == 'Y');
		if ($brands) {
			return 'https://market.yandex.'.$country.'/search?cvredirect=0&text='.$name;
		}
		return "https://market.yandex.{$country}/search.xml?text={$name}&how=aprice&np=1";
	}
}