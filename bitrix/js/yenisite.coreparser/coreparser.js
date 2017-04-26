ys_cp = window.ys_cp || {};

/**
 * @param {Object} config - configuration object for ajax request
 * @param {string} config.url - request url
 * @param {Object} config.data - other parameters to be send in POST data
 * @param {boolean} config.noAsync - true if you need to execute ajax request asynchronously
 * @param {Array} config.successArgs - additional arguments to pass into success callback function
 * @param {Array} config.errorArgs - additional arguments to pass into error callback function
 * @param {Array} config.completeArgs - additional arguments to pass into complete callback function
 * @param {function(Object, ...?)} config.success - callback function for ajax success
 * @param {function(...?)} config.error - callback function for ajax error
 * @param {function(...?)} config.complete - callback function for ajax complete
 */
ys_cp.ajax = function(config) {
	ys_setInterval();
	var isCaptcha = false;
	$.ajax({
		url: config.url,
		data: config.data,
		dataType: "json",
		method: "POST",
		async: !config.noAsync,
		success: function(data) {
			if (typeof data == "object" && data.error == ys_spec_errors.captcha) {
				ys_cp.showCaptchaWindow(config);
				isCaptcha = true;
				return;
			}
			if (typeof config.success != "function") return;
			if (!Array.isArray(config.successArgs)) {
				config.successArgs = [];
			}
			config.successArgs.unshift(data);
			config.success.apply(this, config.successArgs);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			ys_textareaLog.append(textStatus + ': ' + errorThrown + '\r\n');
			ys_textareaLog.scrollTop(ys_textareaLog[0].scrollHeight - ys_textareaLog.height());
			if (typeof config.error != "function") return;
			if (Array.isArray(config.errorArgs)) {
				config.error.apply(this, config.errorArgs);
				return;
			}
			config.error();
		},
		complete: function() {
			ys_clearInterval();
			if (typeof config.complete != "function" || isCaptcha) return;
			if (Array.isArray(config.completeArgs)) {
				config.complete.apply(this, config.completeArgs);
				return;
			}
			config.complete();
		}
	});
};

ys_cp.nextButtonCaptchaHandler = function(event)
{
	var captcha_rep = $.trim($(event.data.dialog.DIV).find('.captcha_rep').val());
	if (captcha_rep.length < 1) {
		alert('captcha is empty');
		return;
	}

	var config = event.data.dialog.PARAMS.userInfo;
	config.data = config.data || {};
	config.data.captcha = captcha_rep;

	ys_cp.ajax(config);
	event.data.dialog.Close();
};

ys_cp.showCaptchaWindow = function(userInfo)
{
	var head = "<p>"+BX.message("ys_cp_notify_captcha")+"</p>";
	var html_content = '<br/><br/><img src="/upload/tmp/captcha.gif?' + rand(100000, 999999) + '"/>'
	                 + '<br/><br/><input type="text" name="rep" class="captcha_rep" /><form>';

	var Dialog = new BX.CDialog({
			title: BX.message('ys_cp_pp_title'),
			head: head,
			userInfo: userInfo,
			content: html_content,
			icon: 'head-block',
			resizable: true,
			draggable: true,
			//height: '400',
			//width: '400',
			buttons: ['<input disabled type="button" value="'+BX.message('ys_cp_but_next')+'" class="next"/>']
		});

	Dialog.Show();

	var DialogDiv = $(Dialog.DIV);
	DialogDiv.find('.captcha_rep').on('change keypress', function(e){
		DialogDiv.find("input.next").removeAttr("disabled");
		if (e.which == 13) {
			DialogDiv.find('.next').trigger('click');
		}
	}).focus();

	DialogDiv.on('click','.next', { dialog: Dialog}, ys_cp.nextButtonCaptchaHandler);
}