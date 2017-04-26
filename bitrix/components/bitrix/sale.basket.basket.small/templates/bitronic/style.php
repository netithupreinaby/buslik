<?header("Content-type: text/css; charset: UTF-8");
$margin_top  = $_GET['top'] ? IntVal($_GET['top']) : 0;
$margin_side = $_GET['side'] ? IntVal($_GET['side']) : 0;
$margin_top_fly = $_GET['fly_top'] ? IntVal($_GET['fly_top']) : 0;

$position	 = htmlspecialchars($_GET['pos']);
$z_index_box = $_GET['cs'] ? 60 : 120 ;
$z_index_up = $_GET['cs'] ? 60 : 101 ;
	
if($position == 'RIGHT')
{
	echo '.yen-bs-close { left:5px !important; }';
	echo 'div.yen-bs-box{top:'.$margin_top.'px ; right:'.$margin_side.'px; z-index:'.$z_index_box.' !important;} div.yen-bs-node{float:right;} div.yen-bs-popup {right:0px !important;} div.yen-bs-rasp {right:0px !important;} div.yen-bs-up {z-index:'.$z_index_up.' !important;}';
}
else
{
	$margin_side = $margin_side ? $margin_side : '' ;
	echo '.yen-bs-close { right:5px !important; }';
	echo 'div.yen-bs-box{top:'.$margin_top.'px ; left:'.$margin_side.'px; z-index:'.$z_index_box.' !important;} div.yen-bs-popup {left:0px !important;} div.yen-bs-rasp {left:0px !important;} div.yen-bs-up {z-index:'.$z_index_up.' !important;}'; 
}
echo ' div.yen-bs-scrollBasket {top:'.$margin_top_fly.'px !important;}';
?>