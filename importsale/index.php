<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><br>

<?
//importSaleXmlBuslick::deleteSaleNotInImport();
importSaleXmlBuslick::importFileSaleXml();
//ImportSale::addImportSaleBitrix();
//ImportSale::getIDbyCode();
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>