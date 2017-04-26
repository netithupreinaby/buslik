<?
$APPLICATION->AddChainItem(GetMessage('TITUL'));

if(count($arResult['ITEMS'])>1 )
{
	$stringName = ' ';
	$slitterSign = ', ';
	$slitterWord = ' '.GetMessage('OR').' ';
	$first = reset($arResult['ITEMS']);
	$last = end($arResult['ITEMS']);
	foreach($arResult['ITEMS'] as $item)
	{
		if($item == $first)
		{ 
			// nothing
		} 
		elseif($item == $last)
		{
			$stringName .= $slitterWord;
		} 
		else
			$stringName .= $slitterSign;
			
		$stringName .= $item['NAME'];
	}
	$stringName .= ' ?';
	
	if(!empty($arParams['COMPARE_META_KEYWORDS']))
		$APPLICATION->SetPageProperty("keywords", $arParams['COMPARE_META_KEYWORDS'].$stringName);
	if(!empty($arParams['COMPARE_META_DESCRIPTION']))
		$APPLICATION->SetPageProperty("description", $arParams['COMPARE_META_DESCRIPTION'].$stringName);
	
}
$titleH1 = !empty($arParams['COMPARE_META_H1']) ? $arParams['COMPARE_META_H1'].$stringName : GetMessage('TITUL');
$titleBrowser = !empty($arParams['COMPARE_META_TITLE_PROP']) ? $arParams['COMPARE_META_TITLE_PROP'].$stringName : GetMessage('TITUL');
$APPLICATION->SetPageProperty("title", $titleBrowser);
$APPLICATION->SetTitle($titleH1);
?>