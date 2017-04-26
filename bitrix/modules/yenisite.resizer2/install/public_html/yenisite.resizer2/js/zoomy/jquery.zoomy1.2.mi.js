(function(f){var j=[];jQuery.fn.zoomy=function(g,b){typeof g==="object"&&b===void 0?(b=g,g="click"):g===void 0&&(g="click");var b=f.extend({zoomSize:200,round:!0,glare:!0,zoomText:"default",clickable:!1,attr:"href"},b),y=function(a,c){j.push(0);var e=typeof a.attr(b.attr)==="string"&&b.attr!=="href"?a.attr(b.attr):a.attr("href");a.css({position:"relative",cursor:function(){return f.browser.mozilla?"-moz-zoom-in":f.browser.webkit?"-webkit-zoom-in":"cell"}}).append('<div class="zoomy zoom-obj-'+c+'" rel="'+
c+'"><img id="tmp"/></div>');var d=f(".zoom-obj-"+c);v(a,d);w(a,e,d);(function(){var f=function(a){if(b.zoomText==="default"||b.zoomText==="")b.zoomText=a;return!0},k=function(){b.clickable||a.bind("click",function(){return!1})};switch(g){case "dblclick":k();f("Double click to Zoom in");break;case "mouseover":k();f("Mouse over to Zoom in");n(a);break;default:k(),f("Click to Zoom in")}a.bind(g,function(){j[c]===0?(d.css({opacity:1}).addClass("cursorHide").show(),j[c]=1,a.find(".zoombar").html(b.zoomText),
setTimeout(function(){d.find("img").length||r(a,d,e)},100)):(d.css({opacity:0}).removeClass("cursorHide"),j[c]=0);(g==="mouseover"||g==="mouseenter")&&a.unbind(g);o(a);return!1})})();a.hover(function(){j[c]===0?n(a):r(a,d,e)},function(){j[c]===0?a.find(".zoombar").html(b.zoomText):x(a,d)})},n=function(a){var c=a.find(".zoomBar");c.length===0?a.append('<span class="zoomBar">'+b.zoomText+"</span>"):c.html(b.zoomText)},r=function(a,c){c.attr("id")!=="brokeZoomy"&&(z(a,c),o(a))},o=function(a){a.find("img");
j[a.find(".zoomy").attr("rel")]===0?a.removeClass("inactive"):a.addClass("inactive")},x=function(a,c){c.attr("id")!=="brokeZoomy"&&!c.find("img").length&&setTimeout(function(){c.hide()},100)},z=function(a,c){var e=a.offset(),d=parseInt(a.attr("x"),10),f=parseInt(a.attr("y"),10),k=a.width(),l=a.height(),g=b.zoomSize,m=g/2,n=k/d,r=l/f,h=Math.round(m-m*n),p=k-g+h,q=l-g+h,t=f-g,u=d-g;a.mousemove(function(a){if(j[c.attr("rel")]===1){var b=a.pageX-e.left-m,d=a.pageY-e.top-m,f=Math.round((a.pageX-e.left)/
n)-m,a=Math.round((a.pageY-e.top)/r)-m,g=[["-",f,"-",a,b,d],["",0,"-",a,-h,d],["",0,"",0,-h,-h],["",0,"-",t,-h,q],["-",f,"",0,b,-h],["-",u,"",0,p,-h],["-",u,"-",a,p,d],["-",u,"-",t,p,q],["-",f,"-",t,b,q]],f=-h>b,a=-h<=d,k=-h>d,l=q>d,d=q<=d,o=p>b,s=p<=b,b=-h<=b&&a&&o&&l?0:f?a&&l?1:k?2:d?3:null:k?o?4:5:s?l?6:7:d?8:null;c.show().css(function(){var a=[];for(i=0;i<g.length;i++){var b=g[i];a.push({backgroundPosition:b[0]+b[1]+"px "+b[2]+b[3]+"px",left:b[4],top:b[5]})}return a}()[b]||{})}})},w=function(a,
c,e){a.children("img").height();a.children("img").width();e.show("").css({top:"-999999px",left:"-999999px"});e.find("img").attr("src")!==c&&e.find("img").attr("src",c).load(function(){a.attr({x:e.find("img").width(),y:e.find("img").height()});b.glare?(e.html("<span/>").css({"background-image":"url("+c+")"}),setTimeout(function(){e.children("span").css({height:b.zoomSize/2,width:b.zoomSize-10}).css(s(0))},100)):e.html("").css({"background-image":"url("+c+")"})}).each(function(){(this.complete||jQuery.browser.msie&&
parseInt(jQuery.browser.version,10)===6)&&f(this).trigger("load")})},v=function(a,c){var e=a.children("img"),d=e.css("margin-left");c.css({height:b.zoomSize,width:b.zoomSize}).css(s());b.glare||c.children("span").css({height:b.zoomSize-10,width:b.zoomSize-10});if(d===void 0||d==="")d="5px";var d={left:[{margin:d,"float":"left"}],right:[{margin:d,"float":"right"}],center:[{margin:d+" auto",display:"block"}],unknown:[{margin:d,display:"block"}],none:[{margin:d,display:"block"}]},g=function(a){var b=
a.css("float");if(b)if(b==="none")if(a=a.attr("style")){b=a.split(";");for(i=0;i<=b.length;i++)if(a=b[i]?b[i].split(":"):[0,0],a[0]==="float")return a[1]}else return b;else return b;else return a.parent("*").css("text-align")==="center"?"center":"unknown"}(e);e.css("margin","0px");a.css(d[g][0]);e.one("load",function(){a.css({display:"block",height:e.height(),width:e.width(),cursor:"normal"})}).each(function(){(this.complete||jQuery.browser.msie&&parseInt(jQuery.browser.version,10)===6)&&f(this).trigger("load")})},
s=function(a){if(b.round){var c={};c["-webkit-border-radius"]=a===void 0?c["-moz-border-radius"]=c["border-radius"]=b.zoomSize/2+"px":c["-moz-border-radius"]=c["border-radius"]=b.zoomSize/2+"px "+b.zoomSize/2+"px 0px 0px";jQuery.browser.msie&&parseInt(jQuery.browser.version,10)===9&&f(".zoomy").find("span").css("margin","0");return c}else return""};f(this).each(function(){y(f(this),j.length)})}})(jQuery); 