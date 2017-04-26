$(function(){
    $("nav a.item-selected").attr("href", "javascript:void(0);");
    //$("nav a.item-selected").css("font-weight", "bold");
    $("nav a.item-selected").css("color", "#000");
    $("nav a.item-selected").css("border-bottom", "none");
    $("nav a.item-selected").css("cursor", "default");
    $("nav li.active").prev().attr("class", "clean");
    $("nav li.active a").hover(function(){}, function(){ $("nav li.active").prev().css("background", "none");});
});
