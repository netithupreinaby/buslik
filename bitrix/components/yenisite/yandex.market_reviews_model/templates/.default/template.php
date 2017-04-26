<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($this->__folder)
	$pathToTemplateFolder = $this->__folder ;
else
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));

?>
<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));?>


<div class="yandex_market_reviews_store">
	<?if($arParams["HEAD"] && $arParams["HEAD_SIZE"] && $arParams["HEAD"] != ""):?>
		<<?=$arParams["HEAD_SIZE"];?>><?=$arParams["HEAD"];?></<?=$arParams["HEAD_SIZE"];?>>
	<?endif;?>
	<div class="reviews" id="yrms_reviews">
	</div>
	<a class="prev disabled" href="javascript:void(0)">< <?=GetMessage('PREV');?></a>
	<a class="page disabled">| <span class="load"><?=GetMessage('LOAD');?></span><span class="num"><?=GetMessage('PAGE');?> <span>1</span></span> |</a>
	<a class="next" href="javascript:void(0)"><?=GetMessage('NEXT');?> ></a>
	<a class="link" href="http://market.yandex.ru/product/<?=$arParams["MODEL"];?>/reviews"><?=GetMessage('LINK');?></a>
</div>                      

<script type="text/javascript">
function updateYRMS(page, flag, path, count)
{
	if(!flag) flag = false;
	
	var ymrs = $('.yandex_market_reviews_store');
	ymrs.find('.page .load').show()
	ymrs.find('.page .num').hide()
	ymrs.find('.prev').addClass("disabled");
	ymrs.find('.next').addClass("disabled");
	
	$.ajax({
		url: path+"/ajax.handler.php",
		type: "POST",
		dataType: "html",
		data: "PAGE="+page,
		success: function(data){
			ymrs.find('.reviews').html(data)
			ymrs.find('.page .load').hide()
			ymrs.find('.page .num').show()
				
			if(ymrs.find('.reviews .total').length > 0)
			{
				var page = parseInt(ymrs.find('.reviews .page').text());
				var total = parseInt(ymrs.find('.reviews .total').text());
				var max = Math.floor(total/count);
				if(total % count != 0)
					max = max + 1;
					
				ymrs.find('.page .num span').html(page.toString() + " / " + max.toString());

				if(page > 1)
					ymrs.find(".prev.disabled").removeClass("disabled");
					
					
				if(page < max)
					ymrs.find(".next.disabled").removeClass("disabled");
					
				ymrs.find(".prev").unbind('click');
				ymrs.find(".prev").click(function(){
					if(page > 1){
						page = page - 1;
						updateYRMS(page, false, path, count);
					}
				});
				ymrs.find(".next").unbind('click');
				ymrs.find(".next").click(function(){
					if(page < max){
						page = page + 1;
						updateYRMS(page, false, path, count);
					}
				});
			}else{
				ymrs.find('.page .num span').html(" - ");
			}
			if(!flag)
			{
				$('html, body').animate({
					scrollTop: ymrs.offset().top
				}, 1000);
			}
		}
	});
}
	$(document).ready(function(){
		updateYRMS(1, true, '<?=$pathToTemplateFolder;?>','<?=$arParams["COUNT"];?>');
	})
</script>

<?if(method_exists($this, 'createFrame')) $frame->end();?>