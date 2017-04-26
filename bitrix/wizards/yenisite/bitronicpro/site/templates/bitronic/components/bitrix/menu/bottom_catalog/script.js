function resizeMenu(){
		
	var wb = $(".block_container").width();
	var count = Math.floor(wb/220);
	var name_len = 0;
	$(".item_menu").each(
		function()
		{
			//len = $(this).html();
			name_len = name_len + 1; //len.length;
		}
	
	);
	
		
	//alert(name_len);
		
	block_len = Math.floor(name_len/count);
	//alert(block_len);
	
	//alert(name_len);
	html ="";
	//for(i = 0; i < count; i ++)
	//{
		var i = 0;
		var paste_head = true;
		var paste_foot = false;
		$(".item_menu").each(
		function()
		{				
			
			if(paste_foot)
			{
				html += '</ul></div>';
				name_len2 = 0;
				paste_foot = false;
			}
			
			if(paste_head)
			{
				html += '<div class="block_inner" ><ul>';						
				name_len2 = 0;
				paste_head = false;
			}
			
			len = $(this).html();
			name_len2 = name_len2 + 1; //len.length;
			if(name_len2 <= block_len)
			{
				if( $(this).attr("rel") == 'span' )
					html += '<li class="iblocktype"><span rel="span" class="item_menu">'+$(this).html()+"</span></li> ";
				else
					html += '<li class="iblock"><a class="item_menu" href="'+$(this).attr("href")+'">'+$(this).html()+"</a></li> ";
			}
			else
			{
				//if(paste_foot)
				html += '</ul></div>';
				html += '<div class="block_inner" ><ul>';	
				
				if( $(this).attr("rel") == 'span' )
					html += '<li class="iblocktype"><span rel="span" class="item_menu">'+$(this).html()+"</span></li> ";
				else
					html += '<li class="iblock"><a class="item_menu" href="'+$(this).attr("href")+'">'+$(this).html()+"</a></li> ";
				
				name_len2 = 0;
				paste_head = false;
				paste_foot = false;
			}					
			
			i ++;
		}
	
	);				
		html += ' </ul><div style="clear: both;"></div>';
	//}
	$(".block_container").html(html);
}

$(document).ready(function(){
		resizeMenu();
	}
);
$(window).resize(function(){
		resizeMenu();
	}
);