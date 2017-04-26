function OnYenisiteAccessoriesSettingEdit(arParams)
{
	if (null != window.jsYenisiteAccessoriesOpener)
	{
		try { window.jsYenisiteAccessoriesOpener.Close(); } catch (e) {}
		window.jsYenisiteAccessoriesOpener = null ;
	}
	window.jsYenisiteAccessoriesOpener = new JCEditorOpener(arParams) ;
}

function JCEditorOpener(arParams)
{
	console.log('1 -----');
	console.log(this) ;
	console.log('-----');
	this.jsOptions = arParams.data.split('||'); // 0 - LANGUAGE_ID , 1 - Button text , 2 - IBLOCK_ID
	this.arParams = arParams;
	// create button
	var obButton = document.createElement('input');
	obButton.setAttribute("type", "button");
	obButton.setAttribute("value", this.jsOptions[1]);
	if(!(parseInt(this.jsOptions[2]) > 0))
		obButton.setAttribute("disabled", "disabled");
	// put button to form
	this.arParams.oCont.appendChild(obButton);

	obButton.onclick = BX.delegate(this.btnClick, this);
	this.saveData = BX.delegate(this.__saveData, this);
}

JCEditorOpener.prototype.Close = function(e)
{
	console.log('close') ;
	if (false !== e)
		BX.PreventDefault(e);

	if (null != window.jsPopup_yenisite_accessories)
	{
		window.jsPopup_yenisite_accessories.Close();
	}
}

JCEditorOpener.prototype.btnClick = function ()
{
	this.arElements = this.arParams.getElements();
	if (!this.arElements)
		return false;

	if (null == window.jsPopup_yenisite_accessories)
	{
		var strUrl = '/bitrix/components/yenisite/catalog.accessories/settings/settings.php'
			+ '?lang=' + this.jsOptions[0] + '&iblock_id=' + this.jsOptions[2]
		
		strUrlPost = 'ACCESSORIES_PROPS=' + BX.util.urlencode(this.arParams.oInput.value);

		window.jsPopup_yenisite_accessories = new BX.CDialog({
			'content_url': strUrl,
			'content_post': strUrlPost,
			'width':800, 'height':500, 
			'resizable':false
		});
	}
	
	window.jsPopup_yenisite_accessories.Show();
	window.jsPopup_yenisite_accessories.PARAMS.content_url = '';
	return false;
}

JCEditorOpener.prototype.__saveData = function()
{
	var url = $('#path2tools').val() ;
	var bxsessid = $('#bxsessid').val() ;
	var send_data = $('form[name="bx_popup_form_yenisite_accessories"]').serialize() + '&action=GetSerialize' + '&bxsessid=' + bxsessid ;

	var rthis = this ;
	$.post(url, send_data,
	function(data) {
		rthis.arParams.oInput.value = $.trim(data);
		if (null != rthis.arParams.oInput.onchange)
			rthis.arParams.oInput.onchange();
		rthis.Close(false);
	});
}