<?if(CModule::IncludeModule("yenisite.bitronic")||CModule::IncludeModule("yenisite.bitronicpro")||CModule::IncludeModule("yenisite.bitroniclite")):
if(!function_exists('Redirect404')){
AddEventHandler("main", "OnEpilog", array("CYSBitronicInit","Redirect404"));
}?><?if(!function_exists('yenisite_SaveNumberSales')){
AddEventHandler("sale", "OnSaleStatusOrder", array("CYSBitronicInit","yenisite_SaveNumberSales")) ;
}?><?if(!function_exists('bitronic_OnAfterUserRegister')){
AddEventHandler("main", "OnAfterUserRegister", array("CYSBitronicInit","bitronic_OnAfterUserRegister"));
}
endif;?>