<script>
	$.fn.equals = function(compareTo) {
	  if (!compareTo || this.length != compareTo.length) {
		return false;
	  }
	  for (var i = 0; i < this.length; ++i) {
		if (this[i] !== compareTo[i]) {
		  return false;
		}
	  }
	  return true;
	};

	
$(document).ready(function(){


	var __this__ = $('div#basket_slider');
		__this__.speed = 200
		__this__.ajax_page = "/"
		__this__.site_id = "s1"

		
	__this__.check_nav = function(th)
	{
		var button_left = th.find('.button7')
		var button_right = th.find('.button8')
		
		if(th.find('li.showed:first').prev().length!=1)
			button_left.addClass('disabled')
		else
			button_left.removeClass('disabled')
		
		if(th.find('li.showed:last').next().length!=1)
			button_right.addClass('disabled')
		else
			button_right.removeClass('disabled')
			
	}
	
	__this__.check_count = function(th)
	{
		var slw = th.parent().width();
		var count = Math.floor(slw / 175);
		var now_count = th.find('ul li.showed').length;
		if(now_count != count)
			if(now_count > count){
				var now = th.find('ul li.showed:last')
				for(var i=0;i<now_count-count;i++){
					now.removeClass('showed');
					now = now.prev()
				}
			}else{
				var now = th.find('ul li.showed:last')
				if(now.length!=1){
					th.find('ul li:lt(' + count + ')').addClass('showed');
				}else{
					for(var i=0;i<count-now_count;i++){
						now = now.next()
						now.addClass('showed').css('left','0').css('opacity', '1');
					}
					now_count = th.find('ul li.showed').length;
					if(now_count != count){
						now = th.find('ul li.showed:first')
						for(var i=0;i<count-now_count;i++){
							now = now.prev()
							now.addClass('showed').css('left','0').css('opacity', '1');
						}
					}
				}
			}
		__this__.check_nav(th)
	}
	
	__this__.bind_li = function(th)
	{
		th.find('ul li').unbind('hover').hover(function () {
			$(this).find('.item-popup').show();
			$(this).css({'z-index': 50});
			$(this).find('.item-popup').addClass('item-hover');
		}, function () {
			var openedMenu = $(this).find('.selectBox-menuShowing')
			if (openedMenu.length != 1) {
				$(this).find('.item-popup').fadeOut();
				$(this).css({'z-index': 1});
				$(this).find('.item-popup').removeClass('item-hover');
			}
		});
	}
	
	__this__.ajax_loader_params = {
		loaderSymbols: ['0', '1', '2', '3', '4', '5', '6', '7'],
		loaderRate: 30
	}
	
	__this__.ajax_loader = function(obj)
	{
		obj.addClass("loader");
		obj.WAIT_STATUS = true;
		obj.WAIT_PARAM = __this__.ajax_loader_params;
		obj.WAIT_INDEX = 0;
		obj.WAIT_FUNC = function(){
			if(!obj) return;
			if(obj.WAIT_STATUS)
			{
				obj.html(obj.WAIT_PARAM.loaderSymbols[obj.WAIT_INDEX]);
				obj.WAIT_INDEX = obj.WAIT_INDEX < obj.WAIT_PARAM.loaderSymbols.length - 1 ? obj.WAIT_INDEX + 1 : 0;
				setTimeout(obj.WAIT_FUNC, obj.WAIT_PARAM.loaderRate);
			}
			else
				obj.removeClass("loader");
		};
		obj.WAIT_FUNC();
	}

	__this__.ajax_loader_stop = function(obj)
	{
		obj.WAIT_STATUS = false;
	}	
	
	__this__.ajax_li_check = function(th, name, now_page)
	{
		var pp = th.parent().parent()
		var count = 0
		count = parseInt(__this__.find('div[name="count"]').attr('data-count'));

		if(count/10 > now_page)
				return true;
		return false;
	}
	
	__this__.ajax_li = function(th)
	{
		var elem = th.find('li:last').not(".loader");
		
		if(!th.find('li.showed:last').equals(elem)) 
			return;

		var now_page = parseInt(elem.find('div[name="iNumPage"]').attr('data-page'));
		var name = th.attr('id').replace('block_', '');
		
		if(!__this__.ajax_li_check(th, name, now_page)) 
			return;
			
		var ajax_elem = elem
		.clone()
		.appendTo(elem.parent())
		.removeClass('showed')
		
		__this__.ajax_loader(ajax_elem)
		
		var button_left = th.find('.button7')
		var button_right = th.find('.button8')
		
		button_right.old_text = button_right.html()
		__this__.ajax_loader(button_right)

		$.post(__this__.ajax_page, {
			'ys_similar_ajax':'y',
			'ys_ms_ajax_call':'y',
			'iNumPage':now_page+1,
			'accessories_on':__this__.accessories_on,
			'red_url':__this__.red_url,
			'site_id':__this__.site_id,
		}, function(data) {
				var appends = $(data).find('.sl_wrapper ul li').appendTo(elem.parent())
				var next_li = th.find('li.showed:last').next()
				if(next_li.length==1){
					th.find('.button8').removeClass('disabled')
					if(ajax_elem.hasClass('showed')){
						next_li.addClass('showed').css('opacity', '0')
						next_li.animate(
							{
								'opacity': '1'
							}, 
							__this__.speed,
							function(){__this__.normalize($(this))}
						);
					}
				}
				__this__.ajax_loader_stop(ajax_elem)
				ajax_elem.remove();
				__this__.ajax_loader_stop(button_right)
				button_right.html(button_right.old_text)
				appends.find("select").selectBox();
				__this__.check_nav(th)
				__this__.bind_li(th)
				__this__.add2basket_events(th)
		});	
		
		__this__.check_nav(th)
		
	}

	__this__.normalize = function(li)
	{
		li.css('opacity', '1').css('left','0')
	}	
	
	__this__.bind_nav = function(th)
	{
		var button_left = th.find('.button7')
		var button_right = th.find('.button8')
	
		button_left.unbind("click").click(function(){
			th.find('li').stop(true, true)
			var next_li = th.find('li.showed:first').prev()
			if(next_li.length!=1)
				return;
			var last_li = th.find('li.showed:last')
			var left = $(last_li).width()+20
			var all_li = th.find('li.showed:not(:last)')
			next_li.addClass('showed').css('opacity', '0').css('left','-'+left+'px')
			all_li.css('left','-'+left+'px')
			last_li.css('left','-'+left+'px')
			last_li.animate(
				{
					'left': 0+'px',
					'opacity': '0'
				}, 
				__this__.speed,
				function(){
					__this__.normalize($(this)) 
					$(this).removeClass('showed');
					__this__.check_nav(th)
				}
			);
			all_li.animate(
				{
					'left': 0+'px'
				}, 
				__this__.speed, 
				function(){	__this__.normalize($(this))}
			);
			next_li.animate(
				{
					'left': 0+'px',
					'opacity': '1'
				}, 
				__this__.speed,
				function(){	__this__.normalize($(this))}
			);
		});
	
		button_right.unbind("click").click(function(){
			th.find('li').stop(true, true)
			var next_li = th.find('li.showed:last').next()
			if(next_li.length!=1)
				return;
			var all_li = th.find('li.showed:not(:first)')
			var first_li = th.find('li.showed:first')
			next_li.addClass('showed').css('opacity', '0')
			var left = $(first_li).width()+20
			first_li.animate(
				{
					'left': '-'+left+'px',
					'opacity': '0'
				}, 
				 __this__.speed,
				function(){
					__this__.normalize($(this))
					$(this).removeClass('showed');
					__this__.check_nav(th)
				}
			);
			all_li.animate(
				{
					'left': '-'+left+'px'
				}, 
				 __this__.speed, 
				function(){	__this__.normalize($(this)) }
			);
			next_li.animate(
				{
					'left': '-'+left+'px',
					'opacity': '1'
				}, 
				 __this__.speed,
				function(){	__this__.normalize($(this)) }
			);
			
			__this__.ajax_li(th)
			
		});
		
		
	}

	__this__.add2basket_events = function(th)
	{
		th.find(".add2basket").attr('onclick','ys_ms_ajax_add2basket(this); return false;');
	}
	
	__this__.init = function(th)
	{
		if(!th)
			return;
		__this__.check_count(th);
		__this__.bind_nav(th);
		__this__.bind_li(th);
		__this__.add2basket_events(th);
		th.find("select").selectBox();
		$(window).resize(function(){
			__this__.check_count(th);
		});
	}
	
	__this__.init(__this__)
	
	
});
</script>