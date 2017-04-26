<?php

/**
 * Класс для работы с настройками (компонент Настройки - bitronic.settings)
 * @author Eduard <ytko90@gmail.com>
 * @version 0.5
 */
class CYSBitronicSettings {

    static private $_module = "yenisite.bitronicpro";
    static private $_method = FALSE;
    static private $_asDefault = FALSE;
    
    static private $_settings = array(
                            "min"               => array('option' => 'MIN_MAX_MIN', 'default' => '780'),
                            "max"               => array('option' => 'MIN_MAX_MAX', 'default' => '1660'),
                            "color_scheme"      => array('option' => 'COLOR_SCHEME', 'default' => 'red'),
                            "basket_position"   => array('option' => 'BASKET_POSITION', 'default' => 'LEFT'),
                            "bg"                => array('option' => 'BACKGROUND_IMAGE', 'default' => ''),
                            "bgcolor"           => array('option' => 'BACKGROUND_COLOR', 'default' => '#FFFFFF'),
                            "bgrepeat"          => array('option' => 'BACKGROUND_REPEAT', 'default' => 'N'),
                            "windowcolor"       => array('option' => 'WINDOW_COLOR', 'default' => '#FFFFFF'),
                            "windowborder"      => array('option' => 'WINDOW_BORDER', 'default' => 'N'),
                            "windowopacity"     => array('option' => 'WINDOW_OPACITY', 'default' => '1'),
                            "menu_filter"       => array('option' => 'MENU_FILTER', 'default' => 'top-left'),
                            "order"             => array('option' => 'ORDER', 'default' => 'full'),
                            "sef"               => array('option' => 'SEF', 'default' => 'N'),
                            "smart_filter"      => array('option' => 'SMART_FILTER', 'default' => 'Y'),
                            "smart_filter_ajax"      => array('option' => 'SMART_FILTER_AJAX', 'default' => 'N'),
							"smart_filter_type"      => array('option' => 'SMART_FILTER_TYPE', 'default' => 'KOMBOX'),
                            "block_view_mode"   => array('option' => 'BLOCK_VIEW_MODE', 'default' => 'popup'),
							"view_photo"   		=> array('option' => 'VIEW_PHOTO', 'default' => 'popup'),
                            "tabs_index"        => array('option' => 'TABS_INDEX', 'default' => 'one_slider'),
                            "action_add2b"      => array('option' => 'ACTION_ADD2B', 'default' => 'popup_window'),
                            "show_element"      => array('option' => 'SHOW_ELEMENT', 'default' => 'N'),
                            "no_section"        => array('option' => 'NO_SECTION', 'default' => false),
							"sku_type"        => array('option' => 'SKU_TYPE', 'default' => 'N'),
    );
    
	 /**
     * Возвращает id модуля
     */
    static function getModuleId()
    {
        return self::$_module;
    }
	
    /**
     * Возвращает все настройки, указанные в массиве self::$_settings
     */
    static function getAllSettings()
    {
        $options = array();
        foreach (self::$_settings as $key => $setting)
            $options[$key] = CYSBitronicSettings::getSetting($setting['option'], $setting['default']);
        
        return $options;
    }
    
    /**
     * Возвращает все настройки, указанные в массиве self::$_settings в виде JSON
     * Отличие от предыдущей функции - ключами массива является ключ 'option' (см. массив)
     * И возвращаются дефолтные значения
     */
    static function getAllDefaultSettingsOptionsAsJSON()
    {
        $options = array();
        foreach (self::$_settings as $key => $setting)
            $options[$setting['option']] = $setting['default'];
        
        return json_encode($options);
    }

    /**
     * Возвращает дефолтное значение Опции 
     * @param string $option Название опции
     */
    static function getDefaultValue($option)
    {
        foreach (self::$_settings as $value)
            if ($value['option'] == $option)
                return $value['default'];

       return FALSE;
    }
    
    /**
     * Тянет настройки из разных движков в зависимости от $method
     * @param string $option нужная опция (настройка)
     * @return string значение опции (настройки)
     */
    static function getSetting($option, $default = "")
    {
        if (self::$_method == FALSE)
            self::$_method = $GLOBALS["USER"]->IsAuthorized() ? "Options" : "Cookies";
        
        $func = "getFrom" . self::$_method;
        return self::$func($option, $default);
    }
    
    /**
     * Метод устанавливает Значение использую разные методы (cookies или options)
     * @global type $USER Хз какая-то глобальная переменная с юзером :D
     * @param type $option Название опции
     * @param type $value Значение опции
     * @param type $asDefault
     */
    static function setSetting($option, $value, $asDefault = FALSE)
    {
        if (self::$_method == FALSE)
            self::$_method = $GLOBALS["USER"]->IsAuthorized() ? "Options" : "Cookies";
        
        $func = "setTo" . self::$_method;
        
        if ($asDefault == TRUE)
        {
            global $USER;
            if($USER->IsAdmin())
                self::$_asDefault = $asDefault;
        }
        
        self::$func($option, $value);
    }
    
    /**
     * Тянет настройки из options (БД)
     * @param string $option нужная опция (настройка)
     * @return string Значение опции (настройки)
     */
    private static function getFromOptions($option, $default = "")
    {
        //return "FROM OPTIONS. KEY: " . $option;
		if(strtolower($option) == 'sef') $option = 'sef_mode';
        $k = $option;
        $key = $option . "_UID_".$GLOBALS["USER"]->GetID();
        
		if(strtolower($option) == 'sef_mode') $key = $k;
        //Неправильная логика - $value всегда равна какому-либо значению (из-за $default)
        //$value = COption::GetOptionString(self::$_module, $key, $default, SITE_ID);
        
        $value = COption::GetOptionString(self::$_module, $key, FALSE, SITE_ID);
        if (!$value)
            $value = COption::GetOptionString(self::$_module, $k, $default, SITE_ID);
        
        //HACK: Для BACKGROUND_IMAGE
        if ($option == "BACKGROUND_IMAGE" && $value != "")
            $value = "/backgrounds/" . $value;
        
        return $value;
    }
    
    /**
     * Тянет настройки из Cookies. Если в Cookie ничего не найдено - лезем в Options
     * @param string $option нужная опция (настройка)
     * @return string Значение опции (настройки)
     */
    private static function getFromCookies($option, $default = "")
    {
        //return "FROM COOKIES. KEY: " . $option;
        $key = $option;
        $value = $GLOBALS["APPLICATION"]->get_cookie($key);

		if ($option == "BACKGROUND_IMAGE" && $value != "")
			$value = "/backgrounds/" . $value;

        if (!$value)
            $value = self::getFromOptions($option, $default);
        
        return $value;
    }
    
    
    /**
     * Метод устанавливает Значение для Опции в Options (БД)
     * @param type $option Название опции
     * @param type $value Значение опции
     */
    private static function setToOptions($option, $value)
    {
        $key = "";
		if(strtolower($option) == 'sef') $option = 'sef_mode';
        if (self::$_asDefault == FALSE && $option != 'sef_mode')
            $key = $option . "_UID_" . $GLOBALS["USER"]->GetID();
        else
            $key = $option;
        
        COption::SetOptionString(self::$_module, $key, $value, false, SITE_ID);
    }
    
    /**
     * Метод устанавливает Значение для Опции в Cookies
     * @param type $option Название опции
     * @param type $value Значение опции
     */
    private static function setToCookies($option, $value)
    {
        $key = $option;
        $GLOBALS["APPLICATION"]->set_cookie($key, $value);
    }
}

?>