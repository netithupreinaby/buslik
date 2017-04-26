<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>

 <?if($arElement["HIERARCHY"]["TYPE_N"]):?>
		<TR>			
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935"></TD>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935" ALIGN=LEFT VALIGN=MIDDLE  ALIGN=LEFT ><B STYLE="FONT-SIZE: 10px;"><?=$arElement["HIERARCHY"]["TYPE_N"]?></B></TD>
			<?foreach($arResult[PROPS] as $nu):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
            <?endforeach?>
            <?foreach($arResult[PRICE_TITLE] as $price):?>
       		<TD STYLE="border: 3px solid #3a3935;"></TD>
       		<?endforeach?>
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endif?>
			
		</TR>
 <?endif?>


 <?if($arElement["HIERARCHY"]["IBLOCK_N"]):?>
		<TR >
		    <TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935"></TD>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935" ALIGN=LEFT VALIGN=MIDDLE ALIGN=LEFT >
			<B STYLE="FONT-SIZE: 10px;">&nbsp;&nbsp;<?=$arElement["HIERARCHY"]["IBLOCK_N"]?></B></TD>
			<?foreach($arResult[PROPS] as $nu):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
            <?endforeach?>
            <?foreach($arResult[PRICE_TITLE] as $price):?>
       		<TD STYLE="border: 3px solid #3a3935;"></TD>
       		<?endforeach?>
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endif?>
			
		</TR>
<?endif?>
<?$i=0;foreach($arElement["HIERARCHY"]["SECTION_N"] as $sec): $i++;?>
	<?if(!in_array($sec, $_SESSION["YEN_PG"]["SECTION_N"])):?>
		<TR>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935"></TD>
			<TD STYLE="border-top: 3px solid #3a3935; border-bottom: 3px solid #3a3935; border-left: 3px solid #3a3935" ALIGN=LEFT VALIGN=MIDDLE ALIGN=LEFT>
			<B STYLE="FONT-SIZE: 10px;">
			<?$j = 0;while($j <= $i) {echo "&nbsp;&nbsp;"; $j++;}?><?=$sec?></B></TD>
			<?foreach($arResult[PROPS] as $nu):?>
			<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endforeach?>
			<?foreach($arResult[PRICE_TITLE] as $price):?>
			<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endforeach?>
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
    		<TD STYLE="border: 3px solid #3a3935;"></TD>
			<?endif?>
			
		</TR>
		<?$_SESSION["YEN_PG"]["SECTION_N"][] = $sec;?>
	<?endif?>
<?endforeach;?>
        <TR>
			<TD STYLE="border-top: 1px solid #3a3935; border-bottom: 1px solid #3a3935; border-left: 1px solid #3a3935; border-right: 1px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE><?=$arElement["ID"]?></TD>
			<TD STYLE="border-top: 1px solid #3a3935; border-bottom: 1px solid #3a3935; border-left: 1px solid #3a3935; border-right: 1px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE><?=$arElement["NAME"]?></TD>
			
            <?foreach($arResult["PRICE_TITLES"] as $price):?>
       		<TD STYLE="border: 3px solid #3a3935;" ALIGN=CENTER><?=number_format( ($arParams["DISCOUNT_CHECK"]==="N"? $arElement["PRICES"][$price]["VALUE"]: $arElement["PRICES"][$price]["DISCOUNT_VALUE"]), 2, ',', '' );?></TD>
       		<?endforeach?>			
			
			<?if($arParams["EXISTENCE_CHECK"]==="Y"):?>
			<TD STYLE="border: 3px solid #3a3935;" ALIGN=CENTER>
				<?
				if($arElement["FOR_ORDER"]==="Y")
				{
					echo GetMessage("ON_REQUEST");
				}
				else
				{
					if($arElement["CATALOG_AVAILABLE"]==="Y")
					{
						echo GetMessage("AVAILABLE");
					}
					else
					{
						echo GetMessage("NOT_AVAILABLE");
					}
				}
				?>
			</TD>
			<?endif?>
			
<?foreach($arResult[PROPS] as $pid=>$name):?>
    		<TD STYLE="border-top: 1px solid #3a3935; border-bottom: 1px solid #3a3935; border-left: 1px solid #3a3935; border-right: 1px solid #3a3935" ALIGN=CENTER VALIGN=MIDDLE>
    		<?if(is_array($arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"])):?>
        		<?=implode(", ",$arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"]);?>
    		<?else:?>
        		<?=$arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"]?$arElement["DISPLAY_PROPERTIES"][$pid]["VALUE"]:0;?>
    		<?endif?>
    		</TD>
<?endforeach?>	

		
		</TR>


<?endforeach?>
