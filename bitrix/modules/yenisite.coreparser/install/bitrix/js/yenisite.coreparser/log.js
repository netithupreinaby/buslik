var ys_readLog_timerId = false;
var ys_lineLog = [] ;
var ys_curId = 0;
var ys_textareaLog ;

var ys_curl_errors = {
	3: "URL_MALFORMAT",
	7: "CONNECTION_FAIL",
	18: "PARTIAL_FILE",
	28: "OPERATION_TIMEOUT",
	55: "SEND_ERROR",
	56: "RECEIVE_ERROR"
};

var ys_spec_errors = {
	captcha: 1,
	other: 2
};

/* === work with log === */
function ys_addLogHtml(){
	if(!$('#ys_spec_log_popup').length){
		var log_html = '<div id="ys_spec_log_popup"><a href="#" onClick="ys_closeLog(); return false;">x</a><h3>'+BX.message('ys_spec_log_label')+'</h3><textarea id="ys_spec_log"></textarea></div>' ;
		$('body').append(log_html);
		ys_textareaLog = $('#ys_spec_log');
	}
}

function ys_closeLog(){
	$('#ys_spec_log_popup').fadeOut(300);
	ys_clearInterval() ;
}

function ys_showLog(){
	ys_textareaLog.html('');
	$('#ys_spec_log_popup').fadeIn(300);
}

var ys_log_waiting = false;
function ys_readLog(){
	//console.log('start readLog');
	if(ys_curId && !ys_log_waiting){
		ys_log_waiting = true;
		$.ajax({
			dataType: "json",
			url: "/bitrix/js/"+ys_readLog.module+"/log/log-"+ys_curId+".txt",
			data: {ysrand: rand(100000, 999999)},
			success: function( data ) {
				if (data == null) return;
				if (data.length < 1) return;
				var param ;
				var str = '';
				var n = 0;

				if(typeof(ys_lineLog[ys_curId]) === 'undefined' || data.length < ys_lineLog[ys_curId]){
					ys_lineLog[ys_curId] = 0;
				}

				$.each(data, function( key, val ) {
					if(n >= ys_lineLog[ys_curId]){
						switch(val['s']){
							case 9999:
								str = '';
							case 5:
							case 6:
							case 7:
							case 10:
							case 14:
							case 15:
							case 17:
								param = "";
							break;
							case 4:
								if(val['p'] == 0){
									param = BX.message('ys_spec_log_cURL_0');
								} else {
									param = BX.message('ys_spec_log_cURL_error')+val['p'];
									if (typeof ys_curl_errors[val['p']] != "undefined" ) {
										param += ' (' + ys_curl_errors[val['p']] + ')';
									}
								}
							break;
							case 13:
								if(val['p'] == 200){
									param = BX.message('ys_spec_log_HTTP_STATUS_200');
								} else {
									param = BX.message('ys_spec_log_HTTP_STATUS_error')+val['p'];
								}
							break;
							default:
								param = val['p'] ;
							break;
						}
						str += BX.message('ys_spec_log_status_'+val['s'])+' '+param+"\r\n";
						ys_lineLog[ys_curId] ++ ;
					}
					n++ ;
				});

				ys_textareaLog.append(str);
				ys_textareaLog.scrollTop(ys_textareaLog[0].scrollHeight - ys_textareaLog.height());
				$('#ys_spec_searchform pre').html(ys_textareaLog.html());
			},
			complete: function() {
				ys_log_waiting = false;
			}
		});
	}
	//console.log('finish readLog');
}

function ys_clearLog(){
	ys_textareaLog.html('');
	ys_lineLog = [] ;
}

function ys_setInterval(){
	//console.log('set interval');
	if(ys_readLog_timerId === false && (ys_curId > 0 || ys_curId == "demo")){
		ys_showLog();
		ys_readLog_timerId = setInterval(ys_readLog, 1000);
	}
}

function ys_clearInterval(){
	//console.log('clear interval');
	clearInterval(ys_readLog_timerId);
	ys_readLog_timerId = false ;
	ys_readLog() ;
}

if (typeof window.rand !== "function") {
	function rand( min, max ){
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
}