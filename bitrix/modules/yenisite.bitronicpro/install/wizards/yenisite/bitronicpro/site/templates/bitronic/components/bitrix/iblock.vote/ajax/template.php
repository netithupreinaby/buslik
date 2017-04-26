<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if($arParams["DISPLAY_AS_RATING"] == "vote_avg")
{
	if($arResult["PROPERTIES"]["vote_count"]["VALUE"])
		$DISPLAY_VALUE = round($arResult["PROPERTIES"]["vote_sum"]["VALUE"]/$arResult["PROPERTIES"]["vote_count"]["VALUE"], 2);
	else
		$DISPLAY_VALUE = 0;
}
else
	$DISPLAY_VALUE = $arResult["PROPERTIES"]["rating"]["VALUE"];
$DISPLAY_VALUE_ROUND = round($DISPLAY_VALUE);
	
	
$onclick = "voteScript.do_vote(this, 'vote_".$arResult["ID"]."', ".$arResult["AJAX_PARAMS"].")";
?>
<div class="iblock-vote" id="vote_<?echo $arResult["ID"]?>">
	
	<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
		<div id="vote_<?=$arResult["ID"]?>_<?=$i?>" title="<?=$name?>" class="star sym 
			<?if($DISPLAY_VALUE_ROUND > $i):?>
				star-voted
			<?endif?>
				"
			<?if(!($arResult["VOTED"] || $arParams["READ_ONLY"]==="Y")):?>
				onmouseover="voteScript.trace_vote(this, true);" 
				onmouseout="voteScript.trace_vote(this, false)" 
				onclick="<?echo htmlspecialcharsbx($onclick);?>"
			<?endif?>
		>&#0116;
			<div>&#0116;</div>
			<?if($DISPLAY_VALUE > $i-1):
				$width = $DISPLAY_VALUE - $i;
			?>
				<?if($width > 0):
					$width = round($width*13);
				?>
					<div class="semifill" style="width: <?=$width?>px">&#0116;</div>
				<?endif?>
			<?endif?>
		</div>
	<?endforeach?>
</div>
