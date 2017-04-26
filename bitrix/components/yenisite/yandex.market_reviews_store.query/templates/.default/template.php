<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(method_exists($this, 'createFrame')) $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));
if(is_array($arResult["REVIEWS"])):
if(!isset($arResult["REVIEWS"]["errors"])):
?>
	<div class="hiddenblock page"><?=$arResult["REVIEWS"]["shopOpinions"]["page"];?></div>
	<div class="hiddenblock count"><?=$arResult["REVIEWS"]["shopOpinions"]["count"];?></div>
	<div class="hiddenblock total"><?=$arResult["REVIEWS"]["shopOpinions"]["total"];?></div>
	<? //functions for help
		function rdate($param, $time=0) {
			if(intval($time)==0)$time=time();
			if(strpos($param,'F')===false) return date($param, $time);
				else return date(str_replace('F',GetMessage('MONTH_'.date('n',$time)),$param), $time);
		}
		function date_text($date) {
			$date = substr($date, 0, -3);
			$day = 24*60*60;
			if (date("d m Y", mktime(0, 0, 0)) == date("d m Y", $date)){
				return GetMessage('TODAY');
			}
			elseif (date("d m Y", mktime(0, 0, 0) - $day) == date("d m Y", $date)){
				return GetMessage('YESTERDAY');
			}
			elseif(date("Y") == date("Y", $date)){
				return rdate("j F", $date);
			}
			else{
				return rdate("j F Y", $date);
			}
		}
		function rate_text($rate) {
			return GetMessage('RATE_'.($rate+3));
		}
		function getNumEnding($number, $endingArray)
		{
			$number = $number % 100;
			if ($number>=11 && $number<=19) {
				$ending=$endingArray[2];
			}
			else {
				$i = $number % 10;
				switch ($i)
				{
					case (1): $ending = $endingArray[0]; break;
					case (2):
					case (3):
					case (4): $ending = $endingArray[1]; break;
					default: $ending=$endingArray[2];
				}
			}
			return $ending;
		}
		function reviews_num($num) {	
			$endingArray = array(
				GetMessage('REVIEWS_0'),
				GetMessage('REVIEWS_1'),
				GetMessage('REVIEWS_2')
			);
			return $num." ".getNumEnding($num, $endingArray);
		}
		function social_split($social) {	
			$arSocial = array(
				'VKONTAKTE' => "vk",
				'FACEBOOK' => "fb",
				'TWITTER' => "tw",
				'MAILRU' => "mr",
				'GOOGLE' => "gp",
				'ODNOKLASSNIKI' => "ok",
				'FOURSQUARE' => "fq",
				'LASTFM' => "lf"
			);

			return $arSocial[$social];
		}		
		
	?>

	<?//reviews...
	foreach ($arResult["REVIEWS"]["shopOpinions"]["opinion"] as $review) {
	?>

		<div id="review-<?=$review["id"];?>" class="b-aura-review b-aura-review_collapsed js-review js-review-shop" data-grade-id=<?=$review["id"];?> itemprop="review" itemscope="itemscope" itemtype="http://schema.org/Review">
		<div class="b-aura-review__title">
			<div class="b-aura-user">
				<?if($review["anonymous"]):?>
					<div class="b-aura-user__image">
					</div>
					<span class="b-aura-username_anonymous" itemprop="author">
						<?=GetMessage('ANONYMOUS');?>
					</span>
				<?else:?>
					<div class="b-aura-user__image">
						<?if($review["authorInfo"]["avatarUrl"]):?>
							<img class="b-icon" src="<?=$review["authorInfo"]["avatarUrl"];?>" alt="">
						<?endif;?>
					</div>
					<span class="b-aura-username" itemprop="author"><?=$review["author"];?></span>
					<div class="b-aura-user__social">
						<?//social...
						foreach ($review["authorInfo"]["socialProviders"] as $social) {
						?>
							<a href="<?=$social["url"];?>" class="b-link" target="_blank" rel="nofollow">
								<span class="b-aura-social b-aura-social_size_m b-aura-social_sn_<?=social_split($social["type"]);?>"></span>
							</a>
						<?}?>
					</div>
					<div class="b-aura-userates">
						<?if($review["authorInfo"]["grades"] > 1):?>
							<a class="b-link"><?=reviews_num($review["authorInfo"]["grades"]);?></a>
						<?endif;?>
					</div>
				<?endif;?>
			</div>
			<div class="b-aura-usergeo">
				<span class="b-aura-usergeo__date">
				<?=date_text($review["date"]);?>
				<?/*<meta itemprop="datePublished" content="2013-05-30T16:38:14">*/?>
				</span>
			</div>
		</div>
		<div class="b-aura-review__rate i-clearfix">
			<span class="b-aura-rating b-aura-rating_state_<?=($review["grade"]+3);?>" title="" data-title="" data-rate=<?=($review["grade"]+3);?>>
				<i class="b-aura-rating__item b-aura-rating__item_1"></i>
				<i class="b-aura-rating__item b-aura-rating__item_2"></i>
				<i class="b-aura-rating__item b-aura-rating__item_3"></i>
				<i class="b-aura-rating__item b-aura-rating__item_4"></i>
				<i class="b-aura-rating__item b-aura-rating__item_5"></i>
			</span>
			<span class="b-aura-rating__text">
				<?=rate_text($review["grade"]);?>
			</span>
			<span itemprop="reviewRating" itemscope="itemscope" itemtype="http://schema.org/Rating">
			<meta itemprop="ratingValue" content="1"><meta itemprop="bestRating" content="5"></span>
		</div>
		
		<div class="b-aura-review__verdict">
			<?if(isset($review["pro"]) && $review["pro"] !== ""):?>
				<div class="b-aura-userverdict">
					<div class="b-aura-userverdict__title"><?=GetMessage('PRO');?></div>
					<div class="b-aura-userverdict__text" itemprop="pro"><?=$review["pro"];?></div>
				</div>
			<?endif;?>
			<?if(isset($review["contra"]) && $review["contra"] !== ""):?>
				<div class="b-aura-userverdict">
					<div class="b-aura-userverdict__title"><?=GetMessage('CONTRA');?></div>
					<div class="b-aura-userverdict__text" itemprop="contra"><?=$review["contra"];?></div>
				</div>
			<?endif;?>
			<?if(isset($review["text"]) && $review["text"] !== ""):?>
				<div class="b-aura-userverdict  b-aura-userverdict_type_newline">
					<?if((isset($review["pro"]) && $review["pro"] !== "")||(isset($review["contra"]) && $review["contra"] !== "")):?>
						<div class="b-aura-userverdict__title"><?=GetMessage('TEXT');?></div>
					<?endif;?>
					<div class="b-aura-userverdict__text" itemprop="description"><?=$review["text"];?></div>
				</div>
			<?endif;?>
		</div>
		
		<div class="b-aura-review__footer">
			<div class="b-aura-usergrade b-aura-usergrade_active_yes">
				<div class="b-spin b-spin_size_27 b-spin_theme_grey-27 i-bem b-spin_js_inited" onclick="return {'b-spin':{name:'b-spin'}}">
				<img alt="" src="//yandex.st/lego/_/La6qi18Z8LwgnZdsAr1qy1GwCwo.gif" class="b-icon b-spin__icon">
				</div><?=GetMessage('HELPFULNESS');?><span class="b-aura-usergrade__votes">
				<span class="b-aura-usergrade__pro"><?=GetMessage('HELPFULNESS_YES');?></span> 
				<span class="b-aura-usergrade__pro-num"><?=$review["agree"];?></span>
				/ 
				<span class="b-aura-usergrade__contra"><?=GetMessage('HELPFULNESS_NO');?></span> 
				<span class="b-aura-usergrade__contra-num"></span><?=$review["reject"];?></span>
			</div>
		</div>
		</div>
	<?}?>

<?else://print errors?>
	<?=GetMessage('ERROR');?>
	<?
	global $USER;
	if($USER->IsAdmin())
	{?>
		<div class="debug">
			<p><?=GetMessage('ADMIN_INFO');?></p>
			<?=GetMessage('ERROR_LIST');?>
				<ul>
					<?foreach ($arResult["REVIEWS"]["errors"] as $err) {?>
						<li><?=$err;?></li>
					<?}?>
				</ul>
			<a href="http://api.yandex.ru/market/content/doc/dg/concepts/error-codes.xml" target="_blank"><?=GetMessage('ERROR_INFO');?></a>
		</div>
	<?
	}
	?>
<?
endif;
else:
	echo GetMessage('ERROR');
	global $USER;
	if($USER->IsAdmin())
	{
		?><div class="debug"><?
			?><p><?echo GetMessage('ADMIN_INFO');?></p><?
			echo 'query error '.$json['errno'];
		?></div><?
	}
endif;
?>


