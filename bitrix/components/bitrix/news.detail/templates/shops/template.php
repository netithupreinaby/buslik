<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?

	function getMarkerShopsType($typeshops){
		switch ($typeshops) {
	case 128:
		return '/local/templates/buslik/static/img/icons/pin-medium-blue.png';
		break;
	  case 129:
		return '/local/templates/buslik/static/img/icons/pin-medium-red.png';
		break;
	  case 130:
		return '/local/templates/buslik/static/img/icons/pin-medium-green.png';
		break;
	  default:
		return '/local/templates/buslik/static/img/icons/pin-medium-blue.png';
		}
	}	
?>
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
								<script type="text/javascript">
								$(document).ready(function(){
									function getSizeMap(){
										var widthcont = $('#mapleft').parent().width();
										var cof = 0.45115170770452740270055599682288;
										var heightcont = widthcont*cof;
										zoom  = 10;
										$('#mapleft').css("width",widthcont);
										$('#mapleft').css("height",heightcont);   
									}
											//debugger;
									getSizeMap();
									ymaps;
									
									ymaps.ready(init);
									 $(window).resize(function (){
											getSizeMap();
											//ymaps.redraw();
									});
									
									function init(){
																var zoomitem = Number(<?=$arResult['PROPERTIES']['zoom']['VALUE']?>);
																zoomitem = zoomitem ? zoomitem : 14;
																var coords = '<?=$arResult['PROPERTIES']['coors']['VALUE']?>'.split(',');
																coords[0] = (Number(coords[0])) ? Number(coords[0]) : 53.964402;
																coords[1] = (Number(coords[1])) ? Number(coords[1]) : 27.625469;
																
																var myMap = new ymaps.Map("mapleft", { 
																		center: [coords[0],coords[1]],
																		zoom: zoomitem,
																		type: "yandex#map"
																	}),
																//myPlacemark = new ymaps.Placemark(coords);
																myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
																	//hintContent: 'Собственный значок метки',
																	//balloonContent: 'Это красивая метка'
																}, {
																	// Опции.
																	// Необходимо указать данный тип макета.
																	iconLayout: 'default#image',
																	// Своё изображение иконки метки.
																	iconImageHref: '<?echo(getMarkerShopsType($arResult['PROPERTIES']['type']['VALUE_ENUM_ID']))?>',
																	// Размеры метки.
																	iconImageSize: [30, 42],
																	// Смещение левого верхнего угла иконки относительно
																	// её "ножки" (точки привязки).
																	iconImageOffset: [-5, -38]
																});
																myMap.geoObjects.add(myPlacemark);
																myMap.controls/*.add('zoomControl')*/.add('smallZoomControl', { right: 5, top: 75 })/*.add('mapTools')*/;
									}		
					}); 
					</script>
<section class="main-content shop-card">
                <ol class="breadcrumb hidden-xs">
                    <li><a href="/">Главная</a></li>
                    <li><a href="/shops/">Магазины</a></li>
                    <li class="active"><?echo($arResult['listidcity'][$arResult['PROPERTIES']['city']['VALUE']]['NAME'].', '.$arResult['PROPERTIES']['address']['VALUE'].''.$arResult['PROPERTIES']['location']['VALUE'])?></li>
                </ol>
                <div class="shop-card-info">
                    <div class="row">	
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
                        <div class="col-md-5 col-sm-12">
                            <div class="shop-photo">			
								<img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
									height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
									alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
									title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
									/>
                            </div>
                        </div>
						<?endif?>
                        <div class="col-md-7 col-sm-12">
                            <div class="shop-description">
                                <h1 class="page-title"><?echo($arResult['listidcity'][$arResult['PROPERTIES']['city']['VALUE']]['NAME'].', '.$arResult['PROPERTIES']['address']['VALUE'].''.$arResult['PROPERTIES']['location']['VALUE'])?></h1>
								<?=$arResult['PROPERTIES']['phone']['~VALUE']['TEXT']?>
								<?=$arResult['PROPERTIES']['timew']['~VALUE']['TEXT']?>
                                <?if(count($arResult['PROPERTIES']['inshops']['VALUE'])>0 || count($arResult['PROPERTIES']['td']['VALUE'])>0){?>
								<table>
                                    <tr>
										<?if(count($arResult['PROPERTIES']['inshops']['VALUE'])>0){?>
                                        <td class="h-blue">
                                            <p class="desc-heading"><?=$arResult['PROPERTIES']['inshops']['NAME']?></p>
                                            <ul>
												<?foreach($arResult['PROPERTIES']['inshops']['VALUE'] as $inshop){?>
                                                <li><?=$arResult['listinshops'][$inshop]?></li>
												<?}?>
                                            </ul>
                                        </td>
										<?}?>
										<?if(count($arResult['PROPERTIES']['td']['VALUE'])>0){?>
                                        <td class="h-green">
											<p class="desc-heading"><?=$arResult['PROPERTIES']['td']['NAME']?></p>
                                            <ul>
												<?foreach($arResult['PROPERTIES']['td']['VALUE'] as $inshop){?>
                                                <li><?=$arResult['listtd'][$inshop]?></li>
												<?}?>
                                            </ul>
                                        </td>
										<?}?>
                                    </tr>
                                </table>
								<?}?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="more-info" class="card-description">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active section-title"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">Как добраться</a></li>
						<?if(strlen($arResult['PROPERTIES']['aboutdiscountcentr']['~VALUE']['TEXT'])){ $discountcentr = true;}?>
						<?if($discountcentr){?>
                        <li role="presentation" class="section-title"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab"><?=$arResult['PROPERTIES']['aboutdiscountcentr']['NAME']?></a></li>
						<?}?>
                        <li role="presentation" class="section-title"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">Обратная связь</a></li>
                    </ul>
                    <div class="tab-content ">
                        <div role="tabpanel" class="tab-pane active" id="tab1">
                            <div class="row">
                                <div class="col-md-12" >
									<div id="mapleft" style="width:1259px; height:568px"></div>
                                </div>
                            </div>
                        </div>
						<?if($discountcentr){?>
                        <div role="tabpanel" class="tab-pane" id="tab2">
                            <article class="about-disount-center">
								<?=$arResult['PROPERTIES']['aboutdiscountcentr']['~VALUE']['TEXT']?>
                            </article>
                        </div>
						<?}?>
                        <div role="tabpanel" class="tab-pane" id="tab3">
                            <div class="row">
                                <div class="col-md-9 col-xs-12">
                                    <div class="comment-form-wrap">
                                        <div class="add-comment clearfix">
                                            <h3 class="pull-left">Нам важно ваше мнение!</h3>
                                            <a href="" class="pull-right btn simple-btn btn-blue btn-white-color">Оставить отзыв о магазине</a>
                                            <p class="comment-caption">Мы всегда готовы услышать от Вас любые пожелания или претензии по работе наших магазинов</p>
                                        </div>
                                        <div class="comment-form">
                                            <form class="simple-form">
                                                <div class="row">
                                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                                        <div class="form-group">
                                                            <label class="" for="example112">Имя</label>
                                                            <input class="form-control" id="example112" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="" for="example112">Email</label>
                                                            <input class="form-control" id="example112" type="text">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="" for="example12">Телефон</label>
                                                            <input class="form-control" id="example12" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="" for="example789">Вопрос</label>
                                                    <textarea class="form-control" id="example789" rows="5"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <div class="captcha">
                                                        <iframe src="https://www.google.com/recaptcha/api2/anchor?k=6Le-wvkSAAAAAPBMRTvw0Q4Muexq9bi0DJwx_mJ-&amp;co=aHR0cHM6Ly93d3cuZ29vZ2xlLmNvbTo0NDM.&amp;hl=ru&amp;v=r20161123095123&amp;size=normal&amp;cb=ovghva8bez9p" title="виджет reCAPTCHA" width="304" height="78" role="presentation" frameborder="0" scrolling="no" name="undefined"></iframe>
                                                    </div>
                                                </div>
                                                <input class="btn btn-blue btn-white-color" value="Оставить отзыв" type="submit">
                                            </form>
                                            <a href="" class="wrap-form">Свернуть форму <img src="img/images/accordeon-arrow-black.png" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="comments-wrap">
                                        <div class="comment-block text-wrap">
                                            <div class="comment-date">
                                                <span class="date gray-color">12.09.2016</span>
                                                <span>Ольга, родитель одного ребенка</span>
                                            </div>
                                            <div class="stars">
                                                <div class="empty-stars">
                                                    <div class="full-stars" style="width: 40%;"></div>
                                                </div>
                                            </div>
                                            <p class="title">Коляска супер!</p>
                                            <p>Коляска очень удобная, достаточно легкая, вся ткань, если необходимо, отстёгивается и стирается. Прогулочный блок можно ставить как по ходу движения, так и против! Манёвренная,передние колёса поворотные,что очень удобно,но при желании их можно заблокировать,нажав одну кнопку. Шосси раскладывается одним пальцем! Всем советую именно эту коляску!</p>
                                        </div>
                                        <div class="comment-block text-wrap">
                                            <div class="comment-date">
                                                <span class="date gray-color">12.09.2016</span>
                                                <span>Ольга, родитель одного ребенка</span>
                                            </div>
                                            <div class="stars">
                                                <div class="empty-stars">
                                                    <div class="full-stars" style="width: 40%;"></div>
                                                </div>
                                            </div>
                                            <p class="title">Коляска супер!</p>
                                            <p>Коляска очень удобная, достаточно легкая, вся ткань, если необходимо, отстёгивается и стирается. Прогулочный блок можно ставить как по ходу движения, так и против! Манёвренная,передние колёса поворотные,что очень удобно,но при желании их можно заблокировать,нажав одну кнопку. Шосси раскладывается одним пальцем! Всем советую именно эту коляску!</p>
                                        </div>
                                    </div>
                                    <div class="more-btn">
                                        <a href="" class="gray-btn simple-btn">Показать еще отзывы</a>
                                    </div>
                                    <div class="paginaton-wrap">
                                        <ul class="pagination">
                                            <li class="prev">
                                                <a href="#"></a>
                                            </li>
                                            <li><a href="#">1</a></li>
                                            <li><a href="#" class="active">2</a></li>
                                            <li><a href="#">3</a></li>
                                            <li><a href="#">4</a></li>
                                            <li><a href="#">5</a></li>
                                            <li class="next">
                                                <a href="#"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?if(strlen($arResult["DETAIL_TEXT"])>0){?>
				<section class="seo-text text-wrap">
					<?echo $arResult["~DETAIL_TEXT"];?>
				</section>
				<?}?>
            </section>