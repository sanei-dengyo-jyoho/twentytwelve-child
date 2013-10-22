<?php
/**
 * Additional Shortcodes
 */
//******************************************************************************
// div class="@"
//******************************************************************************
function sc_block($atts, $content = null) {
	extract(shortcode_atts(array(
			'class' => '',
			'style' => '',
	), $atts));
	return '<div class="'.$class.'" style="'.$style.'">'.do_shortcode($content).'</div>';
}
add_shortcode('block', 'sc_block');


//******************************************************************************
// ol Title
//******************************************************************************
function sc_title($atts, $content = null) {
	extract(shortcode_atts(array(
			'start' => '1',
			'tagname' => 'h2',
	), $atts));
	return '<'.$tagname.'><ol start="'.$start.'"><li>'.do_shortcode($content).'</li></ol></'.$tagname.'>';
}
add_shortcode('title', 'sc_title');


//******************************************************************************
// Google Charts
//******************************************************************************
function sc_gchart($atts) {
	extract(shortcode_atts(array(
			'id' => 'gchart_div',
			'style' => '',
	), $atts));
	return '<div id="'.$id.'" style="'.$style.'"></div>';
}
add_shortcode('gchart', 'sc_gchart');


//******************************************************************************
// Java Scripts Include
//******************************************************************************
function sc_jsInclude($atts) {
	extract(shortcode_atts(array(
			'js' => '',
			'delim' => '::::',
	), $atts));
	$jsTag = '';
	if ($js != '') {
		$array = explode($delim, $js);
		$count = count($array);
		for ($i = 0; $i < $count; $i++) {
			$jsTag = $jsTag.'<script type="text/javascript" src="'.$array[$i].'"></script>';
		}
	}
	return $jsTag;

	unset($jsTag);
	unset($i);
	unset($array);
}
add_shortcode('jsInclude', 'sc_jsInclude');

function sc_jsScript($atts, $content = null) {
	extract(shortcode_atts(array(
	), $atts));
	$rt = "";
	
	$rt .= "<script type='text/javascript'>"."\n";
	$content = str_replace("&#8216;","'",$content);
	$content = str_replace("&#8217;","'",$content);
	$content = str_replace("&#8242;","'",$content);
	$content = str_replace("&gt;",">",$content);
	$content = str_replace("&lt;","<",$content);
	$content = str_replace("<br />","",$content);
	$rt .= $content."\n";
	$rt .= "</script>"."\n";
	
	return $rt;
}
add_shortcode('jsScript', 'sc_jsScript');
?>
