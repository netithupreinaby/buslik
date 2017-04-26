<?php
/**
 * @author Ilya Faleyev <isfaleev@gmail.com>
 * @copyright 2004—2014 (c) ROMZA
 */
//GROUPS
$MESS['COMMON_REQUIRED_PARAMS'] = 'Общие параметры (обязательные поля)';
$MESS['COMMON_OPTIONAL_PARAMS'] = 'Общие параметры (дополнительные поля)';
$MESS['LOCATION_REQUIRED_PARAMS_ALL'] = 'Местоположение (обязательно для городской и загородной недвижимости)';
$MESS['LOCATION_REQUIRED_PARAMS_CITY'] = 'Местоположение (обязательно для городской недвижимости)';
$MESS['LOCATION_OPTIONAL_PARAMS'] = 'Местоположение (дополнительные поля)';
$MESS['SALES_AGENT'] = 'Информация о продавце';
$MESS['DEAL'] = 'Информация о сделке';
$MESS['OBJECT'] = 'Информация об объекте';
$MESS['LIVING_SPACE_REQUIRED'] = 'Описание жилого помещения (обязательные поля)';
$MESS['LIVING_SPACE_OPTIONAL'] = 'Описание жилого помещения (дополнительные поля)';
$MESS['BUILDING'] = 'Описание здания';
$MESS['COUNTRYSIDE'] = 'Для загородной недвижимости';

//COMMON
$MESS['TYPE'] = 'Тип сделки';
$MESS['PROPERTY_TYPE'] = 'Тип недвижимости';
$MESS['CATEGORY'] = 'Категория объекта';
$MESS['CREATION_DATE'] = 'Дата создания объявления';
$MESS['LAST_UPDATE_DATE'] = 'Дата последнего обновления объявления';
$MESS['EXPIRE_DATE'] = 'Дата и время, до которых объявление актуально';
$MESS['PAYED_ADV'] = 'Оплаченное объявление';
$MESS['MANUALLY_ADDED'] = 'Объявление добавлено вручную';

//LOCATION
$MESS['COUNTRY'] = 'Страна (обязательно для всех объявлений)';
$MESS['REGION'] = 'Субъект РФ';
$MESS['DISTRICT'] = 'Район субъекта РФ';
$MESS['LOCALITY_NAME'] = 'Название населенного пункта';
$MESS['ADDRESS'] = 'Адрес (улица, дом)';
$MESS['SUB_LOCALITY_NAME'] = 'Район города';
$MESS['NON_ADMIN_SUB_LOCALITY_NAME'] = 'Неадминистративный район города или ориентир';
$MESS['DIRECTION'] = 'Шоссе (только для Москвы)';
$MESS['DISTANCE'] = 'Расстояние по шоссе до МКАД';
$MESS['LATITUDE'] = 'Географические координаты (широта)';
$MESS['LONGITUDE'] = 'Географические координаты (долгота)';
$MESS['RAILWAY_STATION'] = 'Ближайшая ж/д станция';
$MESS['METRO_NAME'] = 'Название ближайшей станции метро';
$MESS['METRO_TIME_ON_TRANSPORT'] = 'Время до метро в минутах на транспорте';
$MESS['METRO_TIME_ON_FOOT'] = 'Время до метро в минутах пешком';

//SALES_AGENT
$MESS['AGENT_NAME'] = 'Имя агента/продавца';
$MESS['AGENT_PHONE'] = 'Телефон агента/продавца (обязательно)';
$MESS['AGENT_CATEGORY'] = 'Тип продавца';
$MESS['AGENT_ORGANIZATION'] = 'Название агентства';
$MESS['AGENT_ID'] = 'Внутренний ID агентства';
$MESS['AGENT_URL'] = 'Сайт агентства';
$MESS['AGENT_EMAIL'] = 'Электронная почта продавца';
$MESS['AGENT_PARTNER'] = 'Название партнера, предоставившего объявление';

//PRICES
$MESS['PRICE_PERIOD'] = 'Цена за промежуток времени';
$MESS['PRICE_UNIT'] = 'Цена за единицу площади';
$MESS['PRICE_REQUIRED'] = 'Выгружать только объявления с положительной ценой';
$MESS['PRICE_FROM_IBLOCK'] = 'Брать цену из свойства инфоблока, а не из торгового каталога';
$MESS['IBLOCK_ORDER_REALTY'] = 'Игнорировать количественный учет';

//DEAL
$MESS['NOT_FOR_AGENTS'] = 'Просьба агентам не звонить';
$MESS['HAGGLE'] = 'Торг';
$MESS['MORTGAGE'] = 'Ипотека';
$MESS['PREPAYMENT'] = 'Предоплата';
$MESS['RENT_PLEDGE'] = 'Залог';
$MESS['AGENT_FEE'] = 'Комиссия арендатора';
$MESS['WITH_PETS'] = 'Можно ли с животными (для аренды)';
$MESS['WITH_CHILDREN'] = 'Можно ли с детьми (для аренды)';

//OBJECT
$MESS['RENOVATION'] = 'Ремонт';
$MESS['AREA'] = 'Общая площадь';
$MESS['AREA_UNIT'] = 'Единица площади';
$MESS['SQ.M'] = 'кв.м';
$MESS['LIVING_SPACE'] = 'Жилая площадь';
$MESS['KITCHEN_SPACE'] = 'Площадь кухни';
$MESS['LOT_AREA'] = 'Площадь участка';
$MESS['LOT_TYPE'] = 'Тип участка';

//LIVING_SPACE
$MESS['ROOMS'] = 'Общее количество комнат в квартире';
$MESS['ROOMS_OFFERED'] = 'Количество комнат, участвующих в сделке';
$MESS['NEW_FLAT'] = 'Квартира продается в новостройке';
$MESS['OPEN_PLAN'] = 'Свободная планировка';
$MESS['ROOMS_TYPE'] = 'Тип комнат';
$MESS['PHONE'] = 'Наличие телефона';
$MESS['INTERNET'] = 'Наличие интернета';
$MESS['ROOM_FURNITURE'] = 'Наличие мебели';
$MESS['KITCHEN_FURNITURE'] = 'Наличие мебели на кухне';
$MESS['TELEVISION'] = 'Наличие телевизора';
$MESS['WASHING_MACHINE'] = 'Наличие стиральной машины';
$MESS['REFRIGERATOR'] = 'Наличие холодильника';
$MESS['BALCONY'] = 'Тип балкона';
$MESS['BATHROOM_UNIT'] = 'Тип санузла';
$MESS['FLOOR_COVERING'] = 'Покрытие пола';
$MESS['WINDOW_VIEW'] = 'Вид из окон';
$MESS['FLOOR'] = 'Этаж';

//BUILDING
$MESS['FLOORS_TOTAL'] = 'Общее количество этажей в доме';
$MESS['BUILDING_NAME'] = 'Название жилого комплекса';
$MESS['BUILDING_TYPE'] = 'Тип дома';
$MESS['BUILDING_SERIES'] = 'Серия дома';
$MESS['BUILDING_STATE'] = 'Стадия строительства дома';
$MESS['BUILT_YEAR'] = 'Год постройки';
$MESS['READY_QUARTER'] = 'Квартал сдачи дома';
$MESS['LIFT'] = 'Наличие лифта';
$MESS['RUBBISH_CHUTE'] = 'Наличие мусоропровода';
$MESS['IS_ELITE'] = 'Элитность';
$MESS['PARKING'] = 'Наличие парковки';
$MESS['ALARM'] = 'Наличие охраны/сигнализации';
$MESS['CEILING_HEIGHT'] = 'Высота потолков';

//COUNTRYSIDE
$MESS['PMG'] = 'Возможность ПМЖ';
$MESS['TOILET'] = 'Расположение туалета';
$MESS['SHOWER'] = 'Расположение душа';
$MESS['KITCHEN'] = 'Наличие кухни';
$MESS['POOL'] = 'Наличие бассейна';
$MESS['BILLIARD'] = 'Наличие бильярда';
$MESS['SAUNA'] = 'Наличие сауны/бани';
$MESS['HEATING_SUPPLY'] = 'Наличие отопления';
$MESS['WATER_SUPPLY'] = 'Наличие водопровода';
$MESS['SEWERAGE_SUPPLY'] = 'Канализация';
$MESS['ELECTRICITY_SUPPLY'] = 'Электроснабжение';
$MESS['GAS_SUPPLY'] = 'Подключение к газовым сетям';

//TOOLTIPS
$dateTip = 'Формат даты YYYY-MM-DDTHH:mm:ss+00:00<br /><br />Например, 2010-10-05T16:36:00+04:00';
$strictBoolTip = 'В поле должны быть строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«&#727;».';
$strictYesTip = 'В поле должны быть строго ограниченные значения — «да», «true», «1», «+».';
$percentTip = 'В поле должно быть числовое значение в процентах без знака %.';
$numberTip = 'В поле должно быть числовое значение.';
$areaUnitTip = 'Рекомендуемые значения — «кв.м», «sq.m».';
$locationTip = 'Возможные значения поля — «в доме», «на улице».';

//COMMON
$MESS['TYPE_TIP'] = 'В поле должны храниться значения: «продажа», «аренда»';
$MESS['PROPERTY_TYPE_TIP'] = 'Рекомендуемое значение — «жилая»';
$MESS['CATEGORY_TIP'] = 'В поле должны храниться значения: «комната», «квартира», «дом», «участок», «flat», «room», «house», «cottage», «townhouse», «таунхаус», «часть дома», «house with lot», «дом с участком», «дача», «lot», «земельный участок».<br /><br />Сейчас принимаются объявления только о продаже и аренде жилой недвижимости: квартир, комнат, домов и участков.';
$MESS['CREATION_DATE_TIP']    = $dateTip . '<br/><br/><span style="color:red">Обязательное поле.</span><br/>Если оставить пустым, будет использована дата создания элемента инфоблока.';
$MESS['LAST_UPDATE_DATE_TIP'] = &$dateTip;
$MESS['EXPIRE_DATE_TIP']      = &$dateTip;
$MESS['PAYED_ADV_TIP']      = &$strictBoolTip;
$MESS['MANUALLY_ADDED_TIP'] = &$strictBoolTip;
//LOCATION
$MESS['REGION_TIP'] = 'Содержимое поля: для России — название субъекта РФ, для Белорусии и Казахстана — название области, для Украины — название области или автономной республики, если объект находится в Крыму.<br/><br/><span style="color:red">Не нужно для Москвы и Санкт-Петербурга.</span>';
$MESS['DISTRICT_TIP'] = 'Содержимое поля: для России — название района субъекта РФ, для Белорусии, Казахстана, Украины — название района области.<br/><br/><span style="color:red">Обязателен для городов, находящихся в областях субъектов РФ.</span>';
$MESS['LOCALITY_NAME_TIP'] = 'Содержимое поля: название города, деревни, поселка и т.д.';
$MESS['ADDRESS_TIP'] = 'Содержимое поля: улица или улица и дом.';
$MESS['NON_ADMIN_SUB_LOCALITY_NAME_TIP'] = 'Список городов, для которых поддерживается этот параметр, уточняйте по адресу <a href="mailto:info@realty.yandex.ru">info@realty.yandex.ru</a>';
$MESS['DISTANCE_TIP'] = 'Указывается в км. Поле должно содержать только число.';
$MESS['RAILWAY_STATION_TIP'] = 'Для загородной недвижимости';
//AGENT
$MESS['AGENT_PHONE_TIP'] = '<span style="color:red">Обязательное поле</span><br/><br/>Может быть множественным.';
$MESS['AGENT_CATEGORY_TIP'] = 'В поле должны быть строго ограниченные значения — «владелец», «агентство», «owner», «agency».';
//PRICES
$MESS['PRICE_PERIOD_TIP'] = '<strong>В случае сдачи недвижимости в аренду.</strong><br/><br/>Рекомендуемые значения — «день», «месяц», «day», «month».';
$MESS['PRICE_UNIT_TIP'] = 'Рекомендуемые значения — «кв.м», «гектар», «cотка», «sq.m», «hectare».';
//DEAL
$MESS['NOT_FOR_AGENTS_TIP'] = &$strictBoolTip;
$MESS['HAGGLE_TIP'] = &$strictBoolTip;
$MESS['MORTGAGE_TIP'] = 'В поле должны быть строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0».';
$MESS['PREPAYMENT_TIP'] = &$percentTip;
$MESS['RENT_PLEDGE_TIP'] = &$strictBoolTip;
$MESS['AGENT_FEE_TIP'] = &$percentTip;
$MESS['WITH_PETS_TIP'] = &$strictBoolTip;
$MESS['WITH_CHILDREN_TIP'] = &$strictBoolTip;
//OBJECT
$MESS['RENOVATION_TIP'] = 'Рекомендуемые значения поля — «евро», «дизайнерский».';
$MESS['AREA_TIP'] = &$numberTip;
$MESS['LIVING_SPACE_TIP'] = 'В поле должно быть числовое значение (при продаже комнаты — площадь комнаты).';
$MESS['KITCHEN_SPACE_TIP'] = &$numberTip;
$MESS['LOT_AREA_TIP'] = &$numberTip;
$MESS['LOT_TYPE_TIP'] = 'Рекомендуемые значения поля — «ИЖC», «садоводство».';
$MESS['AREA_UNIT_TIP'] = &$areaUnitTip;
$MESS['LIVING_SPACE_UNIT_TIP'] = &$areaUnitTip;
$MESS['KITCHEN_SPACE_UNIT_TIP'] = &$areaUnitTip;
$MESS['LOT_AREA_UNIT_TIP'] = &$areaUnitTip;
//LIVING_SPACE
$MESS['ROOMS_OFFERED_TIP'] = 'Для продажи и аренды комнат';
$MESS['NEW_FLAT_TIP'] = &$strictYesTip;
$MESS['OPEN_PLAN_TIP'] = &$strictYesTip;
$MESS['ROOMS_TYPE_TIP'] = 'Рекомендуемые значения поля — «смежные», «раздельные».';
$MESS['PHONE_TIP'] = &$strictBoolTip;
$MESS['INTERNET_TIP'] = &$strictBoolTip;
$MESS['ROOM_FURNITURE_TIP'] = &$strictBoolTip;
$MESS['KITCHEN_FURNITURE_TIP'] = &$strictBoolTip;
$MESS['TELEVISION_TIP'] = &$strictBoolTip;
$MESS['WASHING_MACHINE_TIP'] = &$strictBoolTip;
$MESS['REFRIGERATOR_TIP'] = &$strictBoolTip;
$MESS['BALCONY_TIP'] = 'Рекомендуемые значения поля — «балкон», «лоджия», «2 балкона», «2 лоджии».';
$MESS['BATHROOM_UNIT_TIP'] = 'Рекомендуемые значения поля — «совмещенный», «раздельный», «2».';
$MESS['FLOOR_COVERING_TIP'] = 'Рекомендуемые значения поля — «паркет», «ламинат», «ковролин», «линолеум».';
$MESS['WINDOW_VIEW_TIP'] = 'Рекомендуемые значения поля — «во двор», «на улицу».';
//BUILDING
$MESS['BUILDING_NAME_TIP'] = 'Для новостроек.';
$MESS['BUILDING_TYPE_TIP'] = 'Рекомендуемые значения поля — «кирпичный», «монолит», «панельный».';
$MESS['BUILDING_STATE_TIP'] = 'Для новостроек. В поле должны быть строго ограниченные значения: «unfinished» — строится.';
$MESS['BUILT_YEAR_TIP'] = 'Для новостроек — год сдачи (год необходимо указывать полностью, например, 1996, а не 96).';
$MESS['READY_QUARTER_TIP'] = 'Для новостроек — квартал сдачи дома (строго ограниченные значения — «1», «2», «3», «4»).';
$MESS['LIFT_TIP'] = &$strictBoolTip;
$MESS['RUBBISH_CHUTE_TIP'] = &$strictBoolTip;
$MESS['IS_ELITE_TIP'] = &$strictBoolTip;
$MESS['PARKING_TIP'] = &$strictBoolTip;
$MESS['ALARM_TIP'] = &$strictBoolTip;
//COUNTRYSIDE
$MESS['PMG_TIP'] = &$strictBoolTip;
$MESS['TOILET_TIP'] = &$locationTip;
$MESS['SHOWER_TIP'] = &$locationTip;
$MESS['KITCHEN_TIP'] = &$strictBoolTip;
$MESS['POOL_TIP'] = &$strictBoolTip;
$MESS['BILLIARD_TIP'] = &$strictBoolTip;
$MESS['SAUNA_TIP'] = &$strictBoolTip;
$MESS['HEATING_SUPPLY_TIP'] = &$strictBoolTip;
$MESS['WATER_SUPPLY_TIP'] = &$strictBoolTip;
$MESS['SEWERAGE_SUPPLY_TIP'] = &$strictBoolTip;
$MESS['ELECTRICITY_SUPPLY_TIP'] = &$strictBoolTip;
$MESS['GAS_SUPPLY_TIP'] = &$strictBoolTip;
?>