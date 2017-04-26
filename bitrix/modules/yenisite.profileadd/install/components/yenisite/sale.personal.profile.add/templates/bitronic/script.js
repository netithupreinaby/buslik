function ys_st_JCTitleSearch(arParams)
{
	var _this = this;

	this.arParams = {
		'AJAX_PAGE': arParams.AJAX_PAGE,
		'CONTAINER_ID': arParams.CONTAINER_ID,
		'INPUT_ID': arParams.INPUT_ID,
		'MIN_QUERY_LEN': parseInt(arParams.MIN_QUERY_LEN),
		'SITE_ID': arParams.SITE_ID,
		'CLEAR_CACHE': arParams.CLEAR_CACHE
	};
	if(arParams.WAIT_IMAGE)
		this.arParams.WAIT_IMAGE = arParams.WAIT_IMAGE;
	if(arParams.MIN_QUERY_LEN <= 0)
		arParams.MIN_QUERY_LEN = 1;

	this.cache = [];
	this.cache_key = null;

	this.startText = '';
	this.currentRow = -1;
	this.RESULT = null;
	this.CONTAINER = null;
	this.INPUT = null;
	this.WAIT = null;

	
		/* Placeholder for IE */
	if($.browser.msie) { // Условие для вызова только в IE
		$("#ys-title-search form").find("input[type='text']").each(function() {
			var tp = $(this).attr("placeholder");
			_this.startText = tp;
			$(this).attr('value',tp).css('color','#ccc');
		}).focusin(function() {
			var val = $(this).attr('placeholder');
			if($(this).val() == val) {
				$(this).attr('value','').css('color','#303030');
			}
		}).focusout(function() {
			var val = $(this).attr('placeholder');
			if($(this).val() == "") {
				$(this).attr('value', val).css('color','#ccc');
			}
		});

		/* Protected send form */
		$("#ys-title-search form").submit(function() {
			$(this).find("input[type='text']").each(function() {
				var val = $(this).attr('placeholder');
				if($(this).val() == val) {
					$(this).attr('value','');
				}
			})
		});
	}
	
	this.ShowResult = function(result)
	{
		var pos = BX.pos(_this.INPUT);
		pos.width = pos.right - pos.left;
		_this.RESULT.style.position = 'absolute';
		_this.RESULT.style.top = (pos.bottom) + 'px';
		
		if(result != null){
			result = $.trim(result);
			if(result.length > 0){
				_this.RESULT.innerHTML = result;
				$(_this.RESULT).slideDown(300,this.checkHeight);
				_this.add2basket_events();
			}
			else{
				$(_this.RESULT).hide();
			}
		}
		else
			$(_this.RESULT).slideUp(300);

		if(pos.left + $(_this.RESULT).width() >= $(window).width())	{
			_this.RESULT.style.right =  $(window).width() - pos.right + 'px';
			_this.RESULT.style.left =  null;
		}else{
			_this.RESULT.style.left =  pos.left + 'px';
			_this.RESULT.style.right =  null;
		}
		
		

		
		
		//ajust left column to be an outline
		var th;
		var tbl = BX.findChild(_this.RESULT, {'tag':'table','class':'ys-title-search-result'}, true);
		if(tbl) th = BX.findChild(tbl, {'tag':'th'}, true);
		if(th)
		{
			var tbl_pos = BX.pos(tbl);
			tbl_pos.width = tbl_pos.right - tbl_pos.left;

			var th_pos = BX.pos(th);
			th_pos.width = th_pos.right - th_pos.left;
			th.style.width = th_pos.width + 'px';

			_this.RESULT.style.width = (pos.width + th_pos.width) + 'px';

			//Move table to left by width of the first column
			_this.RESULT.style.left = (pos.left - th_pos.width - 1)+ 'px';

			//Shrink table when it's too wide
			if((tbl_pos.width - th_pos.width) > pos.width)
				_this.RESULT.style.width = (pos.width + th_pos.width -1) + 'px';

			//Check if table is too wide and shrink result div to it's width
			tbl_pos = BX.pos(tbl);
			var res_pos = BX.pos(_this.RESULT);
			if(res_pos.right > tbl_pos.right)
			{
				_this.RESULT.style.width = (tbl_pos.right - tbl_pos.left) + 'px';
			}
		}

		var fade;
		if(tbl) fade = BX.findChild(_this.RESULT, {'class':'title-search-fader'}, true);
		if(fade && th)
		{
			res_pos = BX.pos(_this.RESULT);
			fade.style.left = (res_pos.right - res_pos.left - 18) + 'px';
			fade.style.width = 18 + 'px';
			fade.style.top = 0 + 'px';
			fade.style.height = (res_pos.bottom - res_pos.top) + 'px';
			fade.style.display = 'block';
		}
		

	}

	this.checkHeight = function(){
		var p = $(_this.RESULT);
		p.css('height', 'auto').removeClass('ys-scroll');
		var hw = $(window).height();
		var ht = p.height();
		if(ht > (hw-200)) {
			p.css('height', hw - 200).addClass('ys-scroll');
		}
	}
	

	this.add2basket_events = function()
	{
		var buttons = $(_this.RESULT).find(".add2basket");
		buttons.unbind("mouseover");
		buttons.unbind("mouseout");
		buttons.unbind("click");
		buttons.mouseover(function(){_this.onYsSearchResult=true;});
		buttons.mouseout(function(){_this.onYsSearchResult=false;});
		buttons.click(function(){
			if(!$(this).hasClass('in_basket')){
				var button = $(this)
				var id = button.attr('id').replace('ys-st-','');
				var splitData = id.split('-');
				var iblock_id = splitData[0];
				var element_id = splitData[1];
				if($('.yen-bs-box').length > 0 &&  typeof SITE_TEMPLATE_PATH != "undefined" && typeof yenisite_bs_flyObjectTo != "undefined"){
					var href = _this.arParams.AJAX_PAGE ;
					var action_add2b = $('#action_add2b').attr('value') ;
					var ob_post_params = JSON.parse('{"action":"ADD2BASKET", "id":"'+element_id+'", "iblock_id":"'+iblock_id+'","sessid":"'+BX.message.bitrix_sessid+'", "action_add2b":"'+action_add2b+'", "main_page":"Y"}');
					var url = SITE_TEMPLATE_PATH+'/ajax/add2basket.php';
					$.post(url, ob_post_params, function(data) {
						button.addClass("in_basket")
						var pic_src = $('#product_photo_'+element_id).attr('src');
						if($('#action_add2b').attr('value') == 'popup_window')
						{
							var arData = data.split('<!-- add2basket -->');
							$('.yen-bs-box').html(arData[0]);
							$('#add_2b_popup').html(arData[1]);
							$('#add_2b_popup').fadeIn('300');
							$('#mask').fadeIn('300');
						}
						else
						{
							var what = '#ys-st-'+id+'-photo';
							var to = '.yen-bs-box';
							yenisite_bs_flyObjectTo(what, to);
							$('.yen-bs-box').html(data);
						}
					});
				}else{
					$.post(
						_this.arParams.AJAX_PAGE, 
						{
							'ys_st_ajax_call':'y',
							'search_add2basket':'y',
							'site_id':_this.arParams.SITE_ID,
							'id':element_id
						},
						function(result)
						{
							if (result != "error"){
								button.addClass("in_basket")
								button.html(result)
							}
						}
					);
				}
			}
		});
	}

	this.onKeyPress = function(keyCode)
	{
		var tbl = BX.findChild(_this.RESULT, {'tag':'table','class':'ys-title-search-result'}, true);
		if(!tbl)
			return false;

		var cnt = tbl.rows.length;

		switch (keyCode)
		{
		case 27: // escape key - close search div
			_this.RESULT.style.display = 'none';
			_this.currentRow = -1;
			_this.UnSelectAll();
		return true;

		case 40: // down key - navigate down on search results
			if(_this.RESULT.style.display == 'none')
				_this.RESULT.style.display = 'block';

			var first = -1;
			for(var i = 0; i < cnt; i++)
			{
				if(!BX.findChild(tbl.rows[i], {'class':'title-search-separator'}, true))
				{
					if(first == -1)
						first = i;

					if(_this.currentRow < i)
					{
						_this.currentRow = i;
						break;
					}
					else if(tbl.rows[i].className == 'title-search-selected')
					{
						tbl.rows[i].className = '';
					}
				}
			}

			if(i == cnt && _this.currentRow != i)
				_this.currentRow = first;

			tbl.rows[_this.currentRow].className = 'title-search-selected';
		return true;

		case 38: // up key - navigate up on search results
			if(_this.RESULT.style.display == 'none')
				_this.RESULT.style.display = 'block';

			var last = -1;
			for(var i = cnt-1; i >= 0; i--)
			{
				if(!BX.findChild(tbl.rows[i], {'class':'title-search-separator'}, true))
				{
					if(last == -1)
						last = i;

					if(_this.currentRow > i)
					{
						_this.currentRow = i;
						break;
					}
					else if(tbl.rows[i].className == 'title-search-selected')
					{
						tbl.rows[i].className = '';
					}
				}
			}

			if(i < 0 && _this.currentRow != i)
				_this.currentRow = last;

			tbl.rows[_this.currentRow].className = 'title-search-selected';
		return true;

		case 13: // enter key - choose current search result
			if(_this.RESULT.style.display == 'block')
			{
				for(var i = 0; i < cnt; i++)
				{
					if(_this.currentRow == i)
					{
						if(!BX.findChild(tbl.rows[i], {'class':'title-search-separator'}, true))
						{
							var a = BX.findChild(tbl.rows[i], {'tag':'a'}, true);
							if(a)
							{
								window.location = a.href;
								return true;
							}
						}
					}
				}
			}
		return false;
		}

		return false;
	}

	this.WAIT_STATUS = false;
	this.loader_obj = $("#ys-title-search .s_submit");
	
	this.WAIT_START = function()
	{
		this.WAIT_STATUS = true;
		
		_this.loader_obj.addClass("loader");
		var loaderSymbols = ['0', '1', '2', '3', '4', '5', '6', '7'], loaderRate = 30, loaderIndex = 0
		var loader = function(){
			_this.loader_obj.html(loaderSymbols[loaderIndex]);
			loaderIndex = loaderIndex < loaderSymbols.length - 1 ? loaderIndex + 1 : 0;
			if(_this.WAIT_STATUS)
				setTimeout(loader, loaderRate);
			else{
				_this.loader_obj.html('&#0035;');
				_this.loader_obj.removeClass("loader");
			}
		};
		loader();
	}
	this.WAIT_STOP = function()
	{
		_this.WAIT_STATUS = false;
	}
	
	this.cat = $('#ys-title-search select#search_select');
	this.cat.ys_st_selectBox();//convert
	this.cat.change(function() {
		_this.RESULT.innerHTML = null;
		
	});

	this.onTimeout = function()
	{
		if(_this.INPUT.value != _this.oldValue)
		{
			_this.oldValue = _this.INPUT.value;
			if(_this.INPUT.value.length >= _this.arParams.MIN_QUERY_LEN)
			{
				_this.cache_key = _this.cat.val()+_this.INPUT.value;
				if(_this.cache[_this.cache_key] == null)
				{
					
					_this.WAIT_START();
					$.post(
						_this.arParams.AJAX_PAGE,
						{
							'ys_st_ajax_call':'y',
							'cat':_this.cat.val(),
							'site_id':_this.arParams.SITE_ID,
							'clear_cache':_this.arParams.CLEAR_CACHE,
							'q':_this.INPUT.value
						},
						function(result)
						{
							_this.RESULT.innerHTML = null;
							_this.cache[_this.cache_key] = result;
							_this.ShowResult(result);
							_this.currentRow = -1;
							_this.EnableMouseEvents();
							_this.WAIT_STOP();
							_this.onTimeout()
						}
					);
				}
				else
				{
					_this.ShowResult(_this.cache[_this.cache_key]);
					_this.currentRow = -1;
					_this.EnableMouseEvents();
					setTimeout(_this.onTimeout, 500);
				}
			}
			else
			{
				$(_this.RESULT).slideUp(300, function(){_this.RESULT.innerHTML = null;});
				_this.currentRow = -1;
				_this.UnSelectAll();
				setTimeout(_this.onTimeout, 500);
			}
		}
		else
		{
			if(_this.INPUT.value == _this.startText)
				$(_this.RESULT).slideUp(300, function(){_this.RESULT.innerHTML = null;});
			setTimeout(_this.onTimeout, 500);
		}
	}

	this.UnSelectAll = function()
	{
		var tbl = BX.findChild(_this.RESULT, {'tag':'table','class':'ys-title-search-result'}, true);
		if(tbl)
		{
			var cnt = tbl.rows.length;
			for(var i = 0; i < cnt; i++)
				tbl.rows[i].className = '';
		}
	}

	this.EnableMouseEvents = function()
	{
		var tbl = BX.findChild(_this.RESULT, {'tag':'table','class':'ys-title-search-result'}, true);
		if(tbl)
		{
			var cnt = tbl.rows.length;
			for(var i = 0; i < cnt; i++)
				if(!BX.findChild(tbl.rows[i], {'class':'title-search-separator'}, true))
				{
					tbl.rows[i].id = 'row_' + i;
					tbl.rows[i].onmouseover = function (e) {
						if(_this.currentRow != this.id.substr(4))
						{
							_this.UnSelectAll();
							this.className = 'title-search-selected';
							_this.currentRow = this.id.substr(4);
						}
					};
					tbl.rows[i].onmouseout = function (e) {
						this.className = '';
						_this.currentRow = -1;
					};
				}
		}
	}

	this.onFocusGain = function()
	{
		if(_this.INPUT.value.length > 0)
			if($.trim(_this.RESULT.innerHTML).length > 0)
				$(_this.RESULT).slideDown(300, this.checkHeight);
	}

	this.onKeyDown = function(e)
	{
		if(!e)
			e = window.event;

		if (_this.RESULT.style.display == 'block')
		{
			if(_this.onKeyPress(e.keyCode))
				return BX.PreventDefault(e);
		}
	}
	
	this.Init = function()
	{
		this.CONTAINER = document.getElementById(this.arParams.CONTAINER_ID);
		this.RESULT = document.body.appendChild(document.createElement("UL"));
		this.RESULT.className = 'ys-title-search-result';
		this.INPUT = document.getElementById(this.arParams.INPUT_ID);
		this.startText = this.oldValue = this.INPUT.value;
		BX.bind(this.INPUT, 'focus', function() {_this.onFocusGain()});

		if(BX.browser.IsSafari() || BX.browser.IsIE())
			this.INPUT.onkeydown = this.onKeyDown;
		else
			this.INPUT.onkeypress = this.onKeyDown;

		if(this.arParams.WAIT_IMAGE)
		{
			this.WAIT = document.body.appendChild(document.createElement("DIV"));
			this.WAIT.style.backgroundImage = "url('" + this.arParams.WAIT_IMAGE + "')";
			if(!BX.browser.IsIE())
				this.WAIT.style.backgroundRepeat = 'none';
			this.WAIT.style.display = 'none';
			this.WAIT.style.position = 'absolute';
			this.WAIT.style.zIndex = '1100';
		}

		setTimeout(this.onTimeout, 500);
		
		
		
		
		
		
		/*---START LOST FOCUS---*/	
		function yenisite_st_close(){
			$(_this.RESULT).slideUp(300);
		}
		$(document).keyup(function(e) {
		  if (e.keyCode == 27) // esc
			yenisite_st_close();  
		});
		this.onYsSearchResult=false;
		$('#ys-title-search').mouseover(function(){_this.onYsSearchResult=true;});
		$('#ys-title-search').mouseout(function(){_this.onYsSearchResult=false;});
		$(_this.RESULT).mouseover(function(){_this.onYsSearchResult=true;});
		$(_this.RESULT).mouseout(function(){_this.onYsSearchResult=false;});
		$('#fixbody a:not(.ys-title-search-result a)').on('click',function(){
			if(_this.onYsSearchResult==false)	
				yenisite_st_close();
		});
		$(document).on('click',function(){
			if(_this.onYsSearchResult==false)
				yenisite_st_close();
		});
		/*----END LOST FOCUS-----*/
			
			
		$(window).resize(function(){
			var p = $(_this.RESULT);
			p.css('height', 'auto').removeClass('ys-scroll');
			var hw = $(window).height();
			var ht = p.height();
			if(ht > (hw-200)) {
				p.css('height', hw - 200).addClass('ys-scroll');
			}
		});
		$('#ys-title-search .example a').click(function() {
			$('#ys-title-search-input').attr('value', $(this).html()).focus();
		});
		$("#ys-title-search .s_submit").click(function(){
			$("#ys-title-search #search_form").submit();
		});

	}

	BX.ready(function (){_this.Init(arParams)});
	
}

