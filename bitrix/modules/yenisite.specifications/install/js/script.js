ys_readLog.module = 'yenisite.specifications';

ys_spec = {
	// regular expression to get iblock element id from checkbox on list pages
	regExpElement: /^E?(\d+)/,

	// initiator of autofill to restart from captcha
	initiator: null,

	// common parameters for AJAX calls
	ajaxData: {
		iblock_id: getParameterByName('IBLOCK_ID'),
		sessid: BX.bitrix_sessid()
	},
	ajaxUrl: '/bitrix/js/yenisite.specifications/ajax.php?action=',

	/**
	 * @param {Object} config - configuration object for ajax request
	 * @param {string} config.action - value of parameter "action" in ajax request
	 * @param {Object} config.data - other parameters to be send in POST data
	 * @param {boolean} config.noAsync - true if you need to execute ajax request asynchronously
	 * @param {Array} config.successArgs - additional arguments to pass into success callback function
	 * @param {Array} config.errorArgs - additional arguments to pass into error callback function
	 * @param {Array} config.completeArgs - additional arguments to pass into complete callback function
	 * @param {function(Object, ...?)} config.success - callback function for ajax success
	 * @param {function(...?)} config.error - callback function for ajax error
	 * @param {function(...?)} config.complete - callback function for ajax complete
	 */
	ajax: function(config)
	{
		var action = config.action || "";
		var data = config.data || {};
		if (typeof action != "string" || action.length < 1) {
			console.log('Error: action is empty');
			return false;
		}
		for (var key in ys_spec.ajaxData) {
			config.data[key] = ys_spec.ajaxData[key];
		}
		config.url = ys_spec.ajaxUrl + action;
		delete config.action;

		// @see /bitrix/js/yenisite.coreparser/coreparser.js
		ys_cp.ajax(config);
	},

	ajaxGetListSuccess: function(data, elementId, imgSrc, imgObj)
	{
		if (typeof(data['SHOW_POPUP']) != 'undefined') {
			if (typeof imgSrc == "string" && typeof imgObj == "object") {
				imgObj.attr('src', imgSrc);
			}
			showPopupWindow(data, ys_spec.nextButtonElementHandler, elementId);
		} else {
			setElementProps(data['ya_id'], data['element_id']);
		}
	},

	ppOpenButtonEnable: function() {
		$('.ys_spec_pp_open').removeAttr('style');
		$('.ys_spec_pp_open_img').hide();
	},

	// add button on edit element page
	init: function ()
	{
		var button = '<input type="button" class="ys_spec_pp_open adm-btn-save" value="' + BX.message('ys_spec_pp_open') + '"/><img class="ys_spec_pp_open_img" src="/bitrix/images/yenisite.specifications/loading.gif">';

		if(!$('#edit1_edit_table').find('#tr_IBLOCK_ELEMENT_PROP_VALUE input:button').is('.ys_spec_pp_open'))
		{
			$('#edit1_edit_table').find('#tr_IBLOCK_ELEMENT_PROP_VALUE td').append(button);
		}
		if(!$('#edit1_edit_table').find('#tr_IBLOCK_ELEMENT_PROPERTY input:button').is('.ys_spec_pp_open'))
		{
			$('#edit1_edit_table').find('#tr_IBLOCK_ELEMENT_PROPERTY td').append(button);
		}

		ys_addLogHtml() ;
		ys_curId = getParameterByName('ID');
		ys_readLog();
		//var element_name = $('tr#tr_NAME .adm-detail-content-cell-r input').val();
		$('#edit1_edit_table').on('click', '.ys_spec_pp_open', function () {
			var element_id = getParameterByName('ID');
			if (element_id <= 0) {
				alert(BX.message('ys_spec_error_empty_id'));
				return;
			}
			$('.ys_spec_pp_open').css({'padding-right': '15px', 'pointer-events': 'none'});
			$('.ys_spec_pp_open_img').show();
			ys_curId = element_id ;
			ys_spec.initiator = $(this);
			ys_spec.ajax({
				action: 'getlist',
				data: {id: element_id},
				successArgs: [element_id],
				success: ys_spec.ajaxGetListSuccess,
				error: ys_spec.ppOpenButtonEnable
			});
		});
	},

	// add button on list of elements page
	init_list: function ()
	{
		try {
			if (typeof ys_spec.tableObj == "undefined") {
				ys_spec_set_params_from_PHP();
			}
			if (typeof ys_spec.tableObj != "object") {
				throw new Error("tableObj is undefined");
			}
			ys_addLogHtml() ;
			this.checkBoxClick();
			this.reInit_list(true);
			// add call this.checkBoxClick for checkboxs in table
			this.tableObj.EnableActions = this.checkBoxClick;
			this.tableObj.Init = this.reInit_list;
		} catch (e) {
			console.log(e, ys_spec_set_params_from_PHP);
			alert(BX.message("ys_spec_error_tableObjException"));
		}

		$('#ys_spec_btn_demo').addClass("adm-btn-green")
			.off('click')
			.on('click', showDemoParser);

		$('body')
		.off('click', '#ys_spec_btn_load')
		.on('click', '#ys_spec_btn_load', function (e) {
			e.preventDefault();
			showPopupWindow(false, ys_spec.nextButtonListHandler);
		});

		$('body')
		.off('click', 'img.ys_yellow, img.ys_grey, img.ys_orange')
		.on('click', 'img.ys_yellow, img.ys_grey, img.ys_orange', function () {

			var element_id = ys_spec.regExpElement.exec($(this).closest('tr').find('input[type=checkbox]').val());
			if (element_id == null) {
				alert('This is not an iblock element');
				return;
			}

			var imgSrc = $(this).attr('src');
			var imgObj = $(this);
			$(this).attr('src', '/bitrix/images/yenisite.specifications/loading.gif');

			ys_curId = element_id[1];
			ys_spec.initiator = $(this);
			ys_spec.ajax({
				action: 'getlist',
				data: {id: element_id[1]},
				successArgs: [element_id[1], imgSrc, imgObj],
				success: ys_spec.ajaxGetListSuccess,
				errorArgs: [imgObj],
				error: function(imgObj) {
					imgObj.attr('src', '/bitrix/images/yenisite.specification/orange.gif').removeClass().addClass('ys_orange').attr('title', BX.message('ys_spec_indicator_orange')).attr('alt', BX.message('ys_spec_indicator_orange'));
				}
			});
		});

	},

	checkBoxClick: function ()
	{
		try {
			if (typeof ys_spec.tableObj == "undefined") {
				ys_spec_set_params_from_PHP();
			}
			if (typeof ys_spec.tableObj != "object") {
				throw new Error("tableObj is undefined");
			}
			// executing of standart bitrix function EnableActions for this obj
			ys_spec.tableObj.EnableActions = Object.getPrototypeOf(ys_spec.tableObj).EnableActions;
			ys_spec.tableObj.EnableActions();
			ys_spec.tableObj.EnableActions = ys_spec.checkBoxClick;

			ys_spec.checkElementSelection();
		} catch (e) {
			console.log(e, ys_spec_set_params_from_PHP);
			alert(BX.message("ys_spec_error_tableObjException"));
		}
	},

	reInit_list: function (firstTime)
	{
		try {
			if (typeof ys_spec.tableObj == "undefined") {
				ys_spec_set_params_from_PHP();
			}
			if (typeof ys_spec.tableObj != "object") {
				throw new Error("tableObj is undefined");
			}

			if(!firstTime)
			{
				// executing of standart bitrix function Init for this obj
				ys_spec.tableObj.Init = Object.getPrototypeOf(ys_spec.tableObj).Init;
				ys_spec.tableObj.Init();
				ys_spec.tableObj.Init = ys_spec.reInit_list;
			}
			$('#ys_spec_btn_load').addClass("adm-btn-green");
			$('#ys_spec_btn_demo').addClass("adm-btn-green");
			ys_spec.checkElementSelection();
		} catch (e) {
			console.log(e, ys_spec_set_params_from_PHP);
			alert(BX.message("ys_spec_error_tableObjException"));
		}
	},

	checkElementSelection: function ()
	{
		try {
			if (typeof ys_spec.tableObj == "undefined") {
				ys_spec_set_params_from_PHP();
			}
			if (typeof ys_spec.tableObj != "object") {
				throw new Error("tableObj is undefined");
			}

			var element_checked = false;
			if(	Number(ys_spec.tableObj.num_checked) > 0)
			{
				for (var i = 0; i < ys_spec.tableObj.CHECKBOX.length; i++)
				{
					if(ys_spec.tableObj.CHECKBOX[i].checked == true && ys_spec.regExpElement.test(ys_spec.tableObj.CHECKBOX[i].value))
						element_checked = true;
				}
			}
			if(	element_checked || ( ys_spec.tableObj.ACTION_TARGET && ys_spec.tableObj.ACTION_TARGET.checked == true)) {
				$('#ys_spec_btn_load').show();
				$('#ys_spec_btn_demo').hide();
			} else {
				$('#ys_spec_btn_load').hide();
				$('#ys_spec_btn_demo').show();
			}
		} catch (e) {
			console.log(e, ys_spec_set_params_from_PHP);
			alert(BX.message("ys_spec_error_tableObjException"));
		}
	},

	nextButtonElementHandler: function(event)
	{
		var ya_id = $('#ys_spec_searchform input:checked').val();

		if (ya_id == 'noid') {
			var $p = $('#ys_spec_searchform input:checked').closest('p');
			setElementWithoutId(
				event.data.dialog.PARAMS.userInfo,
				$p.find('img').attr('src'),
				$p.find('span.noid_description').text()
			);
		} else {
			setElementProps(ya_id, event.data.dialog.PARAMS.userInfo);
		}

		event.data.dialog.SetHead('');
		event.data.dialog.SetContent('<p>'+BX.message('ys_spec_wait')+'</p>');
		event.data.dialog.ClearButtons();

		ys_spec.activeDialog = event.data.dialog;
	},

	nextButtonListHandler: function (event)
	{
		event.data.dialog.Close();
		var nextStep = 0;
		var elementList = [];
		ys_spec.initiator = 'list';

		try {
			if (typeof ys_spec.tableObj == "undefined") {
				ys_spec_set_params_from_PHP();
			}
			if (typeof ys_spec.tableObj != "object") {
				throw new Error("tableObj is undefined");
			}

			BX.showWait();
			window.onbeforeunload = function (){
				return BX.message('ys_spec_close_warning');
			};

			// if checked FOR ALL in footer of table
			if(ys_spec.tableObj.ACTION_TARGET.checked == true)
			{
				ys_spec.ajax({
					action: 'getiblockelements',
					noAsync: true,
					success: function(data) {
						elementList = data;
					}
				});
			}
			else
			{
				for (var i = 0; i < ys_spec.tableObj.CHECKBOX.length; i++)
				{
					if(ys_spec.tableObj.CHECKBOX[i].checked == true && ys_spec.regExpElement.test(ys_spec.tableObj.CHECKBOX[i].value))
					{
						var element_id = ys_spec.regExpElement.exec(ys_spec.tableObj.CHECKBOX[i].value);
						if(Number(element_id[1])>0)
						{
							elementList.push(element_id[1]);
						}
					}
				}
			}
			// flag of last element in cycle
			var last = false;

			for (var i = 0; i < elementList.length; i++)
			{
				// set loading img in front of processing elements
				$('input[type=checkbox][value$='+elementList[i]+']').closest('tr').find('td:last img').removeClass().addClass('loading').attr('src', '/bitrix/images/yenisite.specifications/loading.gif');
			}

			var index = 0;
			setTimeout(function ys_getandfill(elementList, index) {
				if(index == elementList.length-1) {
					last = true;
				}
				id = elementList[index];
				ys_curId = id;
				ys_spec.ajax({
					action: 'getandfill',
					data: {id: id},
					success: function(data) {
						$('input[type=checkbox][value$='+data['ID']+']').closest('tr').find('td:last img').removeClass().addClass('ys_'+data['STATUS']).attr('src', '/bitrix/images/yenisite.specifications/'+data['STATUS']+'.gif').attr('title', BX.message('ys_spec_indicator_'+data['STATUS'])).attr('alt', BX.message('ys_spec_indicator_'+data['STATUS']));
					},
					error: function() {
						$('input[type=checkbox][value$='+id+']').closest('tr').find('td:last img').removeClass().addClass('ys_orange').attr('src', '/bitrix/images/yenisite.specifications/orange.gif').attr('title', BX.message('ys_spec_indicator_orange')).attr('alt', BX.message('ys_spec_indicator_orange'));
					},
					completeArgs: [last],
					complete: function(last) {
						if(last == true)
						{
							BX.closeWait();
							window.onbeforeunload = null;
						}
						else {
							if (ys_curId == id) {
								ys_textareaLog.append(BX.message('ys_spec_log_wait') + "\r\n");
								ys_textareaLog.scrollTop(ys_textareaLog[0].scrollHeight - ys_textareaLog.height());
							}
							// timeout between processing elements
							nextStep = rand(Number(ys_spec.PARAMS.interval_min)*1000, Number(ys_spec.PARAMS.interval_max)*1000);
							setTimeout(ys_getandfill, nextStep, elementList, index+1);
						}
					}
				});
			}, nextStep, elementList, 0);
		} catch (e) {
			console.log(e, ys_spec_set_params_from_PHP);
			alert(BX.message("ys_spec_error_tableObjException"));
		}
	},
};

function showPopupWindow(modelList, nextButtonHandler, userInfo)
{
	var html_content = '<form method="POST" id="ys_spec_searchform">';
	var head = "<p class='ys_spec_notify'><input type='checkbox' name='notify'>"+BX.message('ys_spec_notify')+"</p>";
	if(typeof(modelList) != 'undefined' && modelList != false)
	{
		if(modelList.length > 0 || modelList.length2 > 0) // if modelList not empty
		{
			if (typeof ys_spec.PARAMS == "undefined") {
				ys_spec_set_params_from_PHP();
			}
			head += BX.message('ys_spec_pp_head') + ' <b>&laquo;' + modelList.name + '&raquo;</b>:';
			for(var i = 0; i < modelList.length; i++)
			{
				html_content += '<p><img src="' + modelList[i]['IMAGES'] + '" height="50">'
				+ '<input type="radio" name="models" value="' + modelList[i]['ID'] + '"> '
				+ '<a href="http://market.yandex.ru/model'
				+ (ys_spec.PARAMS.brands == 'Y'
				  ? '/' + modelList[i]['ID'] + '/'
				  : '-spec.xml?modelid=' + modelList[i]['ID'])
				+ '" target="_blank">' + modelList[i]['NAME'] + '</a></p>';
			}
			if (modelList.length2 > modelList.length) {
				html_content += '<div><strong>'+ BX.message('ys_spec_pp_without_id') +'</strong></div>';
				for(var j = modelList.length; j < modelList.length2; j++) {
					html_content += '<p style="clear:both"><img src="' + modelList[j]['IMAGES'] + '" height="50">'
					+ '<input type="radio" name="models" value="noid"> '
					+ '<a class="noid_name" name="noid_name">' + modelList[j]['NAME'] + '</a><br>'
					+ '<span class="noid_description">'+ modelList[j]['DESCRIPTION'] +'</span></p>';
				}
			}
		}
		else
		{
			if (typeof modelList.error != "undefined") {
				html_content += BX.message('ys_spec_pp_error');
				html_content += '<pre>';
				html_content += ys_textareaLog.html();
				html_content += '</pre>';
			} else {
				html_content += BX.message('ys_spec_pp_not_fount');
			}
		}
	}
	html_content += '</form>';
	var Dialog = new BX.CDialog({
			title: BX.message('ys_spec_pp_title'),
			head: head,
			userInfo: userInfo,
			content: html_content,
			icon: 'head-block',
			resizable: true,
			draggable: true,
			//height: '400',
			//width: '400',
			buttons: ['<input disabled type="button" value="'+BX.message('ys_spec_but_next')+'" class="next"/>']
		});

	Dialog.Show();
	var DialogDiv = $(Dialog.DIV);
	$("[name = 'notify']").click(function(){
		if(DialogDiv.find("[name = 'notify']").prop("checked")==true && (DialogDiv.find('#ys_spec_searchform input:checked').prop('checked')==true || DialogDiv.find('#ys_spec_searchform input').length ==0))
			DialogDiv.find("input.next").removeAttr("disabled");
		else
			DialogDiv.find("input.next").attr("disabled", "disabled")
	});

	ys_spec.ppOpenButtonEnable();

	$('#ys_spec_searchform p').click(function(){
		$(this).find('input').prop('checked',true);
		if(DialogDiv.find("[name = 'notify']").prop("checked")==true && DialogDiv.find('#ys_spec_searchform input:checked').prop('checked')==true)
			DialogDiv.find("input.next").removeAttr("disabled");
		else
			DialogDiv.find("input.next").attr("disabled", "disabled");
	});

	DialogDiv.on('click','.next', { dialog: Dialog} ,nextButtonHandler);
	ys_clearInterval();
}

/* ============ DEMO CONTENT PARSER ============ */

function showDemoParser()
{
	var head = '<p class="ys_spec_head">' + BX.message('ys_spec_demo_head_URL') + '</p>';

	var html_content = '<input id="ys_spec_demo_URL" size="80">';

	var Dialog = new BX.CDialog({
			title: BX.message('ys_spec_demo_title'),
			head: head,
			content: html_content,
			icon: 'head-block',
			resizable: true,
			draggable: true,
			buttons: ['<input type="button" value="'+BX.message('ys_spec_but_next')+'" id="ys_spec_demo_next"/>']
		});

	Dialog.Show();

	$('.bx-core-adm-dialog').on('click', '#ys_spec_demo_next', {dialog: Dialog}, sendDemoURL);
}

function sendDemoURL(event)
{
	var url = $.trim($('#ys_spec_demo_URL').val());
	var index = url.indexOf('market.yandex.');
	if (url.length < 21 || index == -1 || index > 9) {
		alert("Incorrect URL");
		return;
	}

	$('#ys_spec_demo_next').attr("disabled", "disabled");

	ys_curId = 'demo';
	ys_spec.ajax({
		action: 'getlistfromurl',
		data: {url: url},
		success: function(data) {
			var dialog = event.data.dialog;
			if(typeof data.error != 'undefined')
			{
				dialog.SetContent(BX.message('ys_spec_pp_error') + '<pre>' + ys_textareaLog.html() + '</pre>');
				return;
			}
			$('.bx-core-adm-dialog').off('click', '#ys_spec_demo_next').on('click', '#ys_spec_demo_next', {dialog: dialog}, createDemoContent);;
			showDemoList(data, dialog);
			addToDemoList(data, dialog);
		},
		error: function() {
			$('#ys_spec_demo_next').removeAttr("disabled");
		}
	});
}

function showDemoList(data, dialog)
{
	$('p.ys_spec_head').html(BX.message('ys_spec_demo_head_choose'));

	var table = '<table id="ys_spec_demo_table" cellpadding="10" cellspacing="0">'
	+ '<tr><th><input type="checkbox" id="ys_spec_demo_toggle_all" checked="checked">'
	+ '</th><th>' + BX.message('ys_spec_demo_icon')
	+ '</th><th>' + BX.message('ys_spec_demo_name')
	+ '</th><th>' + BX.message('ys_spec_demo_price')
	+ '</th><th>' + BX.message('ys_spec_demo_descr')
	+ '</th></tr></table>';

	dialog.SetContent(table);
	$('#ys_spec_demo_table').on('click', 'tr:not(:first)', function(){
		$check = $(this).find('input[type="checkbox"]');
		$check.prop("checked", !$check.prop("checked"));
	}).on('click', 'input', function(e){
		e.stopPropagation();
	});
	$('#ys_spec_demo_toggle_all').on('change', function(){
		$('#ys_spec_demo_table').find('input[name="ys_spec_demo_id"]').prop("checked", $(this).prop("checked"));
	});
}

function addToDemoList(modelList, dialog)
{
	if (typeof(modelList) == 'undefined')              return;
	if (modelList == false)                            return;
	if (modelList.length < 1 && modelList.length2 < 1) return; // if modelList not empty

	var rowList = '';
	for(var i = 0; i < modelList.length; i++)
	{
		var newRow = '<tr><td><input type="checkbox" name="ys_spec_demo_id" checked="checked" value="' + modelList[i]['ID'] + '"></td>'
		+ '<td><img src="' + modelList[i]['IMAGES'] + '" height="50"></td>'
		+ '<td><input size="30" name="ys_spec_demo_name" value="' + modelList[i]['NAME'] + '"></td>'
		+ '<td><input size="10" name="ys_spec_demo_price" value="' + modelList[i]['PRICE'] + '"></td>'
		+ '<td></td></tr>';
		rowList += newRow;
	}
	if (modelList.length2 > modelList.length) {
		for(var j = modelList.length; j < modelList.length2; j++) {
			var newRow = '<tr><td><input type="checkbox" name="ys_spec_demo_id" checked="checked" value="noid"></td>'
			+ '<td><img src="' + modelList[j]['IMAGES'] + '" height="50"></td>'
			+ '<td><input size="30" name="ys_spec_demo_name" value="' + modelList[j]['NAME'] + '"></td>'
			+ '<td><input size="10" name="ys_spec_demo_price" value="' + modelList[j]['PRICE'] + '"></td>'
			+ '<td><textarea rows="5" cols="40">'+ modelList[j]['DESCRIPTION'] +'</textarea></td></tr>';
			rowList += newRow;
		}
	}
	$('#ys_spec_demo_table').append(rowList);
	$('#ys_spec_demo_table tr:odd').css('background-color', '#eee');
	$('#ys_spec_demo_next').removeAttr("disabled");
}

function createDemoContent(event)
{
	$('.bx-core-adm-dialog').off('click', '#ys_spec_demo_next');
	$('#ys_spec_demo_next').attr("disabled", "disabled");

	var dialog = event.data.dialog;
	var arDemo = [];
	$('#ys_spec_demo_table input[name="ys_spec_demo_id"]').each(function(){
		var $checkbox = $(this);
		if (!$checkbox.is(':checked')) return;

		var item = {id: $checkbox.val()};
		var $tr = $checkbox.closest('tr');
		item.image = $tr.find('img').attr('src');
		item.name  = $tr.find('input[name="ys_spec_demo_name"]').val();
		item.price = $tr.find('input[name="ys_spec_demo_price"]').val();
		$descr = $tr.find('textarea');
		if ($descr.length > 0) {
			item.descr = $descr.val();
		}
		arDemo.push(item);
	});

	var table = '<table id="ys_spec_demo_table" cellpadding="10" cellspacing="0">'
	+  '<tr><th>' + BX.message('ys_spec_demo_icon')
	+ '</th><th>' + BX.message('ys_spec_demo_name')
	+ '</th><th>' + BX.message('ys_spec_demo_creation')
	+ '</th><th>' + BX.message('ys_spec_demo_filling')
	+ '</th></tr>';

	for (var i = 0; i < arDemo.length; i++) {
		table += '<tr id="ys_demo_item_' + i + '"><td align="center">'
		+ '<img src="' + arDemo[i].image + '" height="50"></td><td>'
		+ arDemo[i].name + '</td><td align="center">'
		+ '<img src="/bitrix/images/yenisite.specifications/loading.gif"></td><td align="center">'
		+ '<img src="/bitrix/images/yenisite.specifications/loading.gif"></td></tr>'
	}

	table += '</table>';

	dialog.SetContent(table);
	dialog.SetHead('<p>' + BX.message('ys_spec_demo_head_creation') + '</p>');

	$('#ys_spec_demo_table tr:odd').css('background-color', '#eee');

	var section_id = getParameterByName('find_section_section');
	createDemoNextStep(arDemo, 0, section_id);
}

function createDemoNextStep(arDemo, step, section_id)
{
	if (arDemo.length <= step) {
		window.location.reload(false);
		return;
	}
	var toNextStep = function(status){
		$('#ys_demo_item_'+step+' td').eq(3).html(status);
		createDemoNextStep(arDemo, step+1, section_id);
	};

	var ajaxSet = {
		action: 'createelement',
		data: {
			name: arDemo[step].name,
			section_id: section_id,
			price: arDemo[step].price
		},
		success: function(elementId) {
			elementId = parseInt(elementId);
			if (elementId <= 0) {
				return ajaxSet.error();
			}
			$('#ys_demo_item_'+step+' td').eq(2).html('OK');
			if (arDemo[step].id == 'noid') {
				setElementWithoutId(elementId, arDemo[step].image, arDemo[step].descr, toNextStep);
			} else {
				setElementProps(arDemo[step].id, elementId, toNextStep);
			}
		},
		error: function() {
			$('#ys_demo_item_'+step+' td').eq(2).html('ERROR');
			toNextStep('ERROR');
		}
	};
	ys_spec.ajax(ajaxSet);
}

/* ===========END DEMO CONTENT PARSER=========== */

function setElementProps(ya_id, element_id, callback)
{
	ys_curId = element_id;

	ys_spec.ajax({
		action: 'fill',
		data: {
			ya_id: ya_id,
			id: element_id
		},
		success: function(data) {
			if (data.status == "error") {
				if (data.error != ys_spec_errors.captcha) {
					if (typeof callback == "function") callback("ERROR");
					else alert(BX.message('ys_spec_fill_error_' + data.stage));
				}
				return;
			}
			if (typeof callback == "function") callback("OK");
			else window.location.reload(false);
		},
		errorArgs: ["ERROR"],
		error: callback,
		complete: function() {
			if (typeof callback != "function") {
				ys_spec.ppOpenButtonEnable();
				var $listcheck = $('input[type=checkbox][value$='+element_id+']');
				if ($listcheck.length > 0) {
					$listcheck.closest('tr').find('td:last img').removeClass().addClass('ys_orange').attr('src', '/bitrix/images/yenisite.specifications/orange.gif').attr('title', BX.message('ys_spec_indicator_orange')).attr('alt', BX.message('ys_spec_indicator_orange'));
				}
			}
		}
	});
}

function setElementWithoutId(element_id, image, description, callback)
{
	ys_curId = element_id;

	ys_spec.ajax({
		action: 'fillnoid',
		data: {
			id: element_id,
			image: image,
			descr: description
		},
		success: function(data) {
			if (data.status == "error") {
				if (typeof callback == "function") callback("ERROR");
				else alert(BX.message('ys_spec_fill_error_' + data.stage));
				return;
			}
			if (typeof callback == "function") callback("OK");
			else window.location.reload(false);
		},
		errorArgs: ["ERROR"],
		error: callback,
		complete: (typeof callback != "function") ? ys_spec.ppOpenButtonEnable : undefined
	});
}

function getParameterByName(name)
{
	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.search);
	if(results == null)
		return "";
	else
		return decodeURIComponent(results[1].replace(/\+/g, " "));
}
if (typeof window.rand !== "function") {
	function rand( min, max ){
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
}
if (!Array.isArray) {
	Array.isArray = function(arg) {
		return Object.prototype.toString.call(arg) === '[object Array]';
	};
}