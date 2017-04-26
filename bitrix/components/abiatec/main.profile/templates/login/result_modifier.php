<?php
if ($_REQUEST['page']){
    $arResult['TEMPLATE_PAGE'] = strip_tags($_REQUEST['page']) . '.php';
}