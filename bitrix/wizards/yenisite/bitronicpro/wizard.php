<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");



class SelectInstallType extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("install_type");
		$this->SetTitle(GetMessage("INSTALL_TYPE")); 
		$this->SetNextStep("select_site");		
		//$this->SetPrevStep("finish");		
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));		
		//$this->SetPrevCaption(GetMessage("CANCEL"));
		
		$wizard =& $this->GetWizard();
	}
	
	function ShowStep()
	{
		
		$this->content .= '<div class="wizard-input-form">';
		
		$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="shopEmail">'.GetMessage("INSTALL_TYPE").'</label></h4>';
			
		
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("install_type", "update", array("checked" => "checked")).GetMessage("UPDATE").'</label><br>'.GetMessage("UPDATE_DESC").'</div>';		
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("install_type", "install").GetMessage("INSTALL").'</label><br>'.GetMessage("INSTALL_DESC").'</div>';
		
		$this->content .= '</div>
		</div>';
		$this->content .= '<style>.buttons{margin-top: -50px;}</style><div style="position: relative; float: left; margin-left: 260px;">
									<a class="button-prev" href="/bitrix/admin/wizard_list.php">
										<span id="prev-button-caption">'.GetMessage("CANCEL").'</span>
									</a>
								</div>';	
		
		//$this->content .= '</div>';
	}
	
	

}




class SelectSiteStep extends CSelectSiteWizardStep
{
	function InitStep()
	{
		
		parent::InitStep();
		$this->SetPrevStep("install_type");	
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "store";
		
	}
	
}


class SelectTemplateStep extends CSelectTemplateWizardStep
{
	function InitStep()
	{		
		parent::InitStep();
		$this->SetStepID("select_template");
		$this->SetNextStep("select_theme");	
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevStep("select_site");	
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
	}

}

class SelectThemeStep extends CSelectThemeWizardStep
{

	function InitStep()
	{		
		parent::InitStep();
		$this->SetStepID("select_theme");
		$wizard =& $this->GetWizard();
		$install_type = $wizard->GetVar("install_type");
		
		if($install_type == "update"){
			$this->SetNextStep("data_install");	
			$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		}
		else{
			$this->SetNextStep("site_settings");	
			$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		}
		
	}

}

class SiteSettingsStep extends CSiteSettingsWizardStep
{
	function InitStep()
	{
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "store";
		parent::InitStep();
		

		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetTitle(GetMessage("WIZ_STEP_SITE_SET"));

		$siteID = $wizard->GetVar("siteID");
		
		if(COption::GetOptionString("store", "wizard_installed", "N", $siteID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
			$this->SetNextStep("data_install");
		else
		{
			if(LANGUAGE_ID != "ru" && LANGUAGE_ID != "ua")
				$this->SetNextStep("pay_system");
			else
			//$this->SetNextStep("shop_settings");
			$this->SetNextStep("site_redaction");
		}
		
		$templateID = $wizard->GetVar("templateID");
		if($templateID == 'store_light')
		{
			$wizard->SetDefaultVars(Array("siteLogoSet" => true));
		}
		else
		{
			$wizard->SetDefaultVars(Array("siteNameSet" => true));
		}
		
		if($wizard->GetVar('siteLogoSet', true)){
			$themeID = $wizard->GetVar($templateID."_themeID");
			$siteLogo = $this->GetFileContentImgSrc(WIZARD_SITE_PATH."include/company_logo.php", "/bitrix/wizards/bitrix/store/site/templates/store_light/themes/".$themeID."/lang/".LANGUAGE_ID."/logo.jpg");
			if (!file_exists(WIZARD_SITE_PATH."include/logo.jpg"))
			$siteLogo = "/bitrix/wizards/bitrix/store/site/templates/store_light/themes/".$themeID."/lang/".LANGUAGE_ID."/logo.jpg";
			$wizard->SetDefaultVars(Array("siteLogo" => $siteLogo));
		}
		$wizard->SetDefaultVars(
			Array(
				"siteName" => GetMessage("WIZ_MODULES_SITENAME"), //$this->GetFileContent(WIZARD_SITE_PATH."include/company_name.php", GetMessage("WIZ_COMPANY_NAME_DEF")),
				//"siteSchedule" => $this->GetFileContent(WIZARD_SITE_PATH."include/schedule.php", GetMessage("WIZ_COMPANY_SCHEDULE_DEF")),
				"siteTelephone" => GetMessage("WIZ_MODULES_SITEPHONE"),//$this->GetFileContent(WIZARD_SITE_PATH."include/telephone.php", GetMessage("WIZ_COMPANY_TELEPHONE_DEF")),
				"siteCopy" => GetMessage("WIZ_MODULES_SITECOPY"),//$this->GetFileContent(WIZARD_SITE_PATH."include/copyright.php", GetMessage("WIZ_COMPANY_COPY_DEF")),
				"siteTime" => GetMessage("WIZ_COMPANY_SITETIME"),
				//"shopEmail" => COption::GetOptionString("store", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
				//"siteMetaDescription" => GetMessage("wiz_site_desc"),
				//"siteMetaKeywords" => GetMessage("wiz_keywords"),
			)
		);
		
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();
		
		$this->content .= '<div class="wizard-input-form">';
		if($wizard->GetVar('siteNameSet', true)){
			$this->content .= '
			<div class="wizard-input-form-block">
				<h4><label for="siteName">'.GetMessage("WIZ_COMPANY_NAME").'</label></h4>
				<div class="wizard-input-form-block-content">
					<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'siteName', array("id" => "siteName")).'</div>
				</div>
			</div>';
		}
/*		
		if($wizard->GetVar('siteLogoSet', true)){
			$siteLogo = $wizard->GetVar("siteLogo", true);
	
			$this->content .= '
			<div class="wizard-input-form-block">
				<h4><label for="siteName">'.GetMessage("WIZ_COMPANY_LOGO").'</label></h4>
				<div class="wizard-input-form-block-content">
					<div class="wizard-input-form-field wizard-input-form-field-text">'.CFile::ShowImage($siteLogo, 280, 40, "border=0 vspace=15") . '<br>' . $this->ShowFileField("siteLogo", Array("show_file_info" => "N", "id" => "siteLogo")).'</div>
				</div>
			</div>';
		}
*/		
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4><label for="siteTelephone">'.GetMessage("WIZ_COMPANY_TELEPHONE").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'siteTelephone', array("id" => "siteTelephone")).'</div>
			</div>
		</div>';
		
/*
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4><label for="siteSchedule">'.GetMessage("WIZ_COMPANY_SCHEDULE").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-textarea">'.$this->ShowInputField('textarea', 'siteSchedule', array("rows"=>"3", "id" => "siteSchedule")).'</div>
			</div>
		</div>';	
*/		
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4><label for="siteCopy">'.GetMessage("WIZ_COMPANY_COPY").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-textarea">'.$this->ShowInputField('textarea', 'siteCopy', array("rows"=>"3", "id" => "siteCopy")).'</div>
			</div>
		</div>';
		
        $this->content .= '
		<div class="wizard-input-form-block">
			<h4><label for="siteTime">'.GetMessage("WIZ_COMPANY_TIME").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-textarea">'.$this->ShowInputField('textarea', 'siteTime', array("rows"=>"3", "id" => "siteTime")).'</div>
			</div>
		</div>';
		
		$firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID")); 
		$styleMeta = 'style="display:block"';
		if($firstStep == "Y") $styleMeta = 'style="display:none"';
/*
		$this->content .= '
		<div  id="bx_metadata" '.$styleMeta.'>
			<div class="wizard-input-form-block">
				<h4 style="margin-top:0"><label for="siteMetaDescription">'.GetMessage("wiz_meta_data").'</label></h4>
				<label for="siteMetaDescription">'.GetMessage("wiz_meta_description").'</label>
				<div class="wizard-input-form-block-content" style="margin-top:7px;">
					<div class="wizard-input-form-field wizard-input-form-field-textarea">'.
						$this->ShowInputField("textarea", "siteMetaDescription", Array("id" => "siteMetaDescription", "style" => "width:100%", "rows"=>"3")).'</div>
				</div>
			</div>';
		$this->content .= '
			<div class="wizard-input-form-block">
				<label for="siteMetaKeywords">'.GetMessage("wiz_meta_keywords").'</label><br>
				<div class="wizard-input-form-block-content" style="margin-top:7px;">
					<div class="wizard-input-form-field wizard-input-form-field-text">'.
						$this->ShowInputField('text', 'siteMetaKeywords', array("id" => "siteMetaKeywords")).'</div>
				</div>
			</div>
		</div>';
*/
		/*if($firstStep == "Y")
		{
			$this->content .= '
			<div class="wizard-input-form-block">
				<div class="wizard-input-form-block-content">'.
						$this->ShowCheckboxField(
							"installDemoData", 
							"Y", 
							(array("id" => "installDemoData", "onClick" => "if(this.checked == true){document.getElementById('bx_metadata').style.display='block';}else{document.getElementById('bx_metadata').style.display='none';}"))
						).
				'
				<label for="installDemoData">'.GetMessage("wiz_structure_data").'</label>
				</div>
			</div>';
			}
		else
		{*/
			$this->content .= $this->ShowHiddenField("installDemoData","Y");
		//}
		
		if($wizard->GetVar("install_type") == "update"){
			$this->content .= $this->ShowHiddenField("demo_install","N");
		}
		else{
		    $par = array();

		    if(COption::GetOptionString("yenisite.market", "color_scheme", "N") == "N")
		        $par = array("id" => "demo_install", "checked" => "checked");
		    else
    		    $par = array("id" => "demo_install");
    		    
			$this->content .= '
				<div class="wizard-input-form-block">
					<div class="wizard-input-form-block-content">'.
							$this->ShowCheckboxField(
								"demo_install", 
								"Y", 
								$par
							).
					'
					<label for="demo_install">'.GetMessage("wiz_structure_data").'</label>
					</div>
				</div>';
			}
		
		
		
		$defaultTemplateID = COption::GetOptionString("main", "wizard_template_id", "", $wizard->GetVar("siteID")); 
		/*if(!empty($defaultTemplateID) && $defaultTemplateID != $wizard->GetVar("templateID")){
			$this->content .= '
			<div class="wizard-input-form-block">
				<h4><label for="siteSchedule">'.GetMessage("WIZ_REWRITE_INDEX_DESC").'</label></h4>
				<div class="wizard-input-form-block-content">'.
						$this->ShowCheckboxField(
							"rewriteIndex", 
							"Y", 
							(array("id" => "rewriteIndex"))
						).
				'
				<label for="installDemoData">'.GetMessage("wiz_rewrite_index").'</label>
				</div>
			</div>';	
		}*/
		
		if(LANGUAGE_ID != "ru" && LANGUAGE_ID != "ua")
		{
			if(CModule::IncludeModule("catalog")){
				$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
				if (!$db_res->Fetch())
				{
					$this->content .= '
					<div class="wizard-input-form-block">
						<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</label></h4>
						<div class="wizard-input-form-block-content">
							'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
							'. $this->ShowCheckboxField("installPriceBASE", "Y", 
							(array("id" => "install-demo-data")))
							. ' <label for="install-demo-data">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />
							
						</div>
					</div>';	
				}
			}
		}
		
		$this->content .= '</div>';
	}
	function OnPostForm()
	{
		$wizard =& $this->GetWizard();
		$res = $this->SaveFile("siteLogo", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 40, "max_width" => 280, "make_preview" => "Y"));
	}
}



class SiteRedaction extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("site_redaction");
		$this->SetTitle(GetMessage("WIZ_STEP_SR"));
		$this->SetNextStep("site_architect");
		$this->SetPrevStep("site_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

	}


	
	function ShowStep()
	{

		$wizard =& $this->GetWizard();
		$siteStamp = $wizard->GetVar("siteStamp", true);
		$demo_install = $wizard->GetVar("demo_install");
		
		$this->content .= '<div class="wizard-input-form">';
		
		if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale'))		
			$this->content .= '<div class="wizard-input-form-block">'.GetMessage('SALE_REDACTION').'</div>'.$this->ShowHiddenField("redaction", "bitrix_sale");
		else
			$this->content .= '<div class="wizard-input-form-block">'.GetMessage('MARKET_REDACTION').'</div>'.$this->ShowHiddenField("redaction", "yenisite_market");
		
		
		/*$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="shopEmail">'.GetMessage("WIZ_SHOP_REDACTION").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("redaction", "bitrix_sale", array("checked" => "checked")).GetMessage("BITRIX_SALE").'</label></div>
				<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("redaction", "yenisite_market").GetMessage("YENISITE_MARKET").'</label></div>
			</div>
		</div>';	
		*/
		
		$this->content .= '</div>';
	}
	
	

}


class SiteArchitect extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("site_architect");
		$this->SetTitle(GetMessage("WIZ_STEP_SA"));
		if(CModule::IncludeModule('catalog') && CModule::IncludeModule('sale'))
			$this->SetNextStep("shop_settings");
		else
			$this->SetNextStep("data_install");
			//$this->SetNextStep("finish");
		
		$this->SetPrevStep("site_redaction");		
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();
		$redaction = $wizard->GetVar("redaction", true);
		
		$this->content .= '<div class="wizard-input-form">';
		
		$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="shopEmail">'.GetMessage("WIZ_SHOP_ARCHITECT").'</label></h4><div><a href="https://dev.1c-bitrix.ru/community/webdev/user/3308/blog/5478/">'.GetMessage("WIZ_SHOP_ARCHITECT_ABOUT").'</a></div>';
			
		if($redaction != 'yenisite_market')
			$this->content .= '<div class="wizard-input-form-block-content"><div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("architect", "multi"/*, array("checked" => "checked")*/).GetMessage("MULT_IBLOCK").'</label></div>';				
		
		//if($redaction == 'yenisite_market')
			$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("architect", "one", array("checked" => "checked")).GetMessage("ONE_IBLOCK").'</label></div>';
		//else
		//	$this->content .= '<div class="wizard-input-form-field wizard-input-form-field-radio"><label>'.$this->ShowRadioField("architect", "one").GetMessage("ONE_IBLOCK").'</label></div>';
		
		$this->content .= '</div>
		</div>';	
		
		$this->content .= '</div>';
	}
}

class ShopSettings extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("shop_settings");
		$this->SetTitle(GetMessage("WIZ_STEP_SS"));
		$this->SetNextStep("person_type");
		$this->SetPrevStep("site_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
		
		$wizard =& $this->GetWizard();

		$siteStamp =$wizard->GetPath()."/site/templates/minimal/images/pechat.gif";
		
		$siteID = $wizard->GetVar("siteID");
		
		$wizard->SetDefaultVars(
			Array(
				"shopEmail" => COption::GetOptionString("store", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
				"shopOfName" => COption::GetOptionString("store", "shopOfName", GetMessage("WIZ_SHOP_OF_NAME_DEF"), $siteID),
				"shopLocation" => COption::GetOptionString("store", "shopLocation", GetMessage("WIZ_SHOP_LOCATION_DEF"), $siteID),
				//"shopZip" => 101000,
				"shopAdr" => COption::GetOptionString("store", "shopAdr", GetMessage("WIZ_SHOP_ADR_DEF"), $siteID),
				"shopINN" => COption::GetOptionString("store", "shopINN", "1234567890", $siteID),
				"shopKPP" => COption::GetOptionString("store", "shopKPP", "123456789", $siteID),
				"shopNS" => COption::GetOptionString("store", "shopNS", "0000 0000 0000 0000 0000", $siteID),
				"shopBANK" => COption::GetOptionString("store", "shopBANK", GetMessage("WIZ_SHOP_BANK_DEF"), $siteID),
				"shopBANKREKV" => COption::GetOptionString("store", "shopBANKREKV", GetMessage("WIZ_SHOP_BANKREKV_DEF"), $siteID),
				"shopKS" => COption::GetOptionString("store", "shopKS", "30101 810 4 0000 0000225", $siteID),
				"siteStamp" => COption::GetOptionString("store", "siteStamp", $siteStamp, $siteID),
			)
		);
	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();
		$siteStamp = $wizard->GetVar("siteStamp", true);
	
		$this->content .= '<div class="wizard-input-form">';
		
		$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="shopEmail">'.GetMessage("WIZ_SHOP_EMAIL").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail")).'</div>
			</div>
		</div>';	
		$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="shopOfName">'.GetMessage("WIZ_SHOP_OF_NAME").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopOfName', array("id" => "shopOfName")).'</div>
			</div>
		</div>';			

		$this->content .= '<div class="wizard-input-form-block">
			<h4><label for="shopLocation">'.GetMessage("WIZ_SHOP_LOCATION").'</label></h4>';
			
		$this->content .= '
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopLocation', array("id" => "shopLocation")).'</div>
			</div>';
		$this->content .= '</div>';			

		$this->content .= '
		<div class="wizard-input-form-block">
			<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_ADR").'</label></h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-textarea">'.$this->ShowInputField('textarea', 'shopAdr', array("rows"=>"3", "id" => "shopAdr")).'</div>
			</div>
		</div>';			

		$this->content .= '
		<div class="wizard-input-form-block">
			<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_BANK_TITLE").'</label></h4>
		</div>
				<table class="data-table-no-border">
				<tr>
					<th width="35%">'.GetMessage("WIZ_SHOP_INN").':</th>
					<td width="65%"><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopINN').'</div></td>
				</tr>
				<tr>
					<th>'.GetMessage("WIZ_SHOP_KPP").':</th>
					<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopKPP').'</div></td>
				</tr>				
				<tr>
					<th>'.GetMessage("WIZ_SHOP_NS").':</th>
					<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopNS').'</div></td>
				</tr>				
				<tr>
					<th>'.GetMessage("WIZ_SHOP_BANK").':</th>
					<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopBANK').'</div></td>
				</tr>				
				<tr>
					<th>'.GetMessage("WIZ_SHOP_BANKREKV").':</th>
					<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopBANKREKV').'</div></td>
				</tr>				
				<tr>
					<th>'.GetMessage("WIZ_SHOP_KS").':</th>
					<td><div class="wizard-input-form-field wizard-input-form-field-text">'.$this->ShowInputField('text', 'shopKS').'</div></td>
				</tr>				
				</table>
		';	
		
		if(CModule::IncludeModule("catalog")){
			$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
			if (!$db_res->Fetch())
			{
				$this->content .= '
				<div class="wizard-input-form-block">
					<h4><label for="shopAdr">'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</label></h4>
					<div class="wizard-input-form-block-content">
						'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
						'. $this->ShowCheckboxField("installPriceBASE", "Y", 
						(array("id" => "install-demo-data")))
						. ' <label for="install-demo-data">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />
						
					</div>
				</div>';	
			}
		}
		
		$this->content .= '</div>';
	}
	
	function OnPostForm()
	{
		$wizard =& $this->GetWizard();
		$res = $this->SaveFile("siteStamp", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 70, "max_width" => 190, "make_preview" => "Y"));
	}

}

class PersonType extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("person_type");
		$this->SetTitle(GetMessage("WIZ_STEP_PT"));
		$this->SetNextStep("pay_system");
		$this->SetPrevStep("shop_settings");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$wizard->SetDefaultVars(
			Array(
				"personType" => Array(
					"fiz" => "Y",	
					"ur" => "Y",
				)
			)
		);
	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();
		
		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4>'.GetMessage("WIZ_PERSON_TYPE_TITLE").'</h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					'.$this->ShowCheckboxField('personType[fiz]', 'Y', (array("id" => "personTypeF"))).' <label for="personTypeF">'.GetMessage("WIZ_PERSON_TYPE_FIZ").'</label><br />
					'.$this->ShowCheckboxField('personType[ur]', 'Y', (array("id" => "personTypeU"))).' <label for="personTypeU">'.GetMessage("WIZ_PERSON_TYPE_UR").'</label><br />
					
				</div>
			</div>
			'.GetMessage("WIZ_PERSON_TYPE").'
		</div>';
		$this->content .= '</div>';
	}
	
	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$personType = $wizard->GetVar("personType");

		if (empty($personType["fiz"]) && empty($personType["ur"]))
			$this->SetError(GetMessage('WIZ_NO_PT'));
	}

}

class PaySystem extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("pay_system");
		$this->SetTitle(GetMessage("WIZ_STEP_PS"));
		$this->SetNextStep("data_install");
		if(LANGUAGE_ID != "ru" && LANGUAGE_ID != "ua")
			$this->SetPrevStep("site_settings");
		else
		$this->SetPrevStep("person_type");
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();
		if(LANGUAGE_ID == "ru")
		{
		$wizard->SetDefaultVars(
			Array(
				"paysystem" => Array(
					"cash" => "Y",	
					"sber" => "Y",
					"bill" => "Y",
				),			
				"delivery" => Array(
					"courier" => "Y",	
					"self" => "Y",
					"russianpost" => "N",
				)
			)
		);
	}
		else
		{
			$wizard->SetDefaultVars(
				Array(
					"paysystem" => Array(
						"cash" => "Y",	
						"paypal" => "Y",
					),			
					"delivery" => Array(
						"courier" => "Y",	
						"self" => "Y",
						"dhl" => "Y",
						"ups" => "Y",
					)
				)
			);
		}
	}
	
	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$paysystem = $wizard->GetVar("paysystem");

		if (empty($paysystem["cash"]) && empty($paysystem["sber"]) && empty($paysystem["bill"]) && empty($paysystem["paypal"]))
			$this->SetError(GetMessage('WIZ_NO_PS'));
	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();
		$personType = $wizard->GetVar("personType");
		
		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4>'.GetMessage("WIZ_PAY_SYSTEM_TITLE").'</h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					'.$this->ShowCheckboxField('paysystem[cash]', 'Y', (array("id" => "paysystemC"))).' <label for="paysystemC">'.GetMessage("WIZ_PAY_SYSTEM_C").'</label><br />';
				if(LANGUAGE_ID == "ru" || LANGUAGE_ID == "ua")
				{
				if($personType["fiz"] == "Y")
					$this->content .= $this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemS"))).' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_S").'</label><br />';
				if($personType["ur"] == "Y")
					$this->content .= $this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemB"))).' <label for="paysystemB">'.GetMessage("WIZ_PAY_SYSTEM_B").'</label><br />';
				}
				else
				{
					$this->content .= $this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemP"))).' <label for="paysystemP">PayPal</label><br />';
				}
				$this->content .= '</div>
			</div>
			'.GetMessage("WIZ_PAY_SYSTEM").'
		</div>';
		$this->content .= '
		<div class="wizard-input-form-block">
			<h4>'.GetMessage("WIZ_DELIVERY_TITLE").'</h4>
			<div class="wizard-input-form-block-content">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					'.$this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryC"))).' <label for="deliveryC">'.GetMessage("WIZ_DELIVERY_C").'</label><br />
					'.$this->ShowCheckboxField('delivery[self]', 'Y', (array("id" => "deliveryS"))).' <label for="deliveryS">'.GetMessage("WIZ_DELIVERY_S").'</label><br />';
					if(LANGUAGE_ID == "ru")
					{
						$this->content .= $this->ShowCheckboxField('delivery[russianpost]', 'Y', (array("id" => "deliveryR"))).' <label for="deliveryR">'.GetMessage("WIZ_DELIVERY_R").'</label><br />';
					}
					else
					{
						$this->content .= $this->ShowCheckboxField('delivery[dhl]', 'Y', (array("id" => "deliveryD"))).' <label for="deliveryD">DHL</label><br />';
						$this->content .= $this->ShowCheckboxField('delivery[ups]', 'Y', (array("id" => "deliveryU"))).' <label for="deliveryU">UPS</label><br />';
					}
					$this->content .= '
				</div>
			</div>
			'.GetMessage("WIZ_DELIVERY").'
		</div>';
		$this->content .= '<div class="wizard-input-form-block">'.GetMessage("WIZ_DELIVERY_HINT").'</div>';

		$this->content .= '</div>';
	}
}
class DataInstallStep extends CDataInstallWizardStep
{
	function CorrectServices(&$arServices)
	{
		$wizard =& $this->GetWizard();
		if($wizard->GetVar("installDemoData") != "Y")
		{
		}
	}
}

class FinishStep extends CFinishWizardStep
{
	function InitStep()
	{
		$this->SetStepID("finish");
		$this->SetNextStep("finish");
		$this->SetTitle(GetMessage("FINISH_STEP_TITLE"));
		$this->SetNextCaption(GetMessage("wiz_go"));
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();
		
		$siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));
		$rsSites = CSite::GetByID($siteID);
		$siteDir = "/"; 
		if ($arSite = $rsSites->Fetch())
			$siteDir = $arSite["DIR"]; 

		$wizard->SetFormActionScript(str_replace("//", "/", $siteDir."/?finish"));

		$this->CreateNewIndex();
		
		COption::SetOptionString("main", "wizard_solution", $wizard->solutionName, false, $siteID); 
		
		$this->content .= GetMessage("FINISH_STEP_CONTENT");
		//$this->content .= "<br clear=\"all\"><a href=\"/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&site_id=".$siteID."&wizardName=bitrix:store.mobile&".bitrix_sessid_get()."\" class=\"button-next\"><span id=\"next-button-caption\">".GetMessage("wizard_store_mobile")."</span></a>";
		
		if ($wizard->GetVar("installDemoData") == "Y")
			$this->content .= GetMessage("FINISH_STEP_REINDEX");		
			
		
	}

}
?>