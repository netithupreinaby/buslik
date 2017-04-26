<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><? $arDays = array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY');

foreach ($arDays as $day): ?>
	<div id='ys-timeline'>
		<div
			<? 	if ($arParams[$day]=='Y'):?>
					class="ys-work"
				<? else: ?>
					class="ys-weekend"
				<?endif?> >	
		</div>
	</div>
<?endforeach; ?>

<script>
	function show_lunch() {
		$('#ys-lunch').fadeIn('normal');
	}
	function hide_lunch() {
		$('#ys-lunch').fadeOut('normal');
	}
</script>

<div onMouseOver='show_lunch()' onMouseOut='hide_lunch()' id='ys-time-work' > <?=$arParams["TIME_WORK"] ?> </div>
<div onMouseOver='show_lunch()' onMouseOut='hide_lunch()' id='ys-time-weekend' > <?=$arParams["TIME_WEEKEND"] ?> </div>

<div id='ys-lunch' ><?=$arParams["LUNCH"] ?></div>