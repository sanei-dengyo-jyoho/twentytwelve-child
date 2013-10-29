<?php
/**
 * Additional Shortcodes
 */
//******************************************************************************
// div class="@"
//******************************************************************************
function sc_block( $atts, $content = null ) {
	extract( shortcode_atts( array(
			'class' => '',
			'style' => '',
	), $atts ) );

	$ret  = '';
	$ret .= '<div class="'.$class.'" style="'.$style.'">';
	$ret .= do_shortcode( $content );
	$ret .= '</div>';
	return $ret;
}

add_shortcode( 'block', 'sc_block' );


//******************************************************************************
// ol Title
//******************************************************************************
function sc_title( $atts, $content = null ) {
	extract( shortcode_atts( array(
			'start' => '1',
			'tagname' => 'h2',
	), $atts ) );

	$ret  = '';
	$ret .= '<'.$tagname.'>';
	$ret .= '<ol start="'.$start.'"><li>';
	$ret .= do_shortcode( $content );
	$ret .= '</li></ol>';
	$ret .= '</'.$tagname.'>';
	return $ret;
}

add_shortcode( 'title', 'sc_title' );


//******************************************************************************
// Google Charts
//******************************************************************************
function sc_gchart( $atts ) {
	extract( shortcode_atts( array(
			'id' => 'gchart_div',
			'style' => '',
	), $atts ) );
	$styledata = '';
	if ( $style != '' ) {
		$styledata = ' style="'.$style.'"';
	}
	return '<div id="'.$id.'"'.$styledata.'></div>';
}
add_shortcode( 'gchart', 'sc_gchart' );


//******************************************************************************
//  Java Scripts Include
//******************************************************************************
// Java Scripts Source Link
function sc_jsInclude( $atts ) {
	extract( shortcode_atts( array(
			'js' => '',
			'delim' => '::::',
	), $atts ) );

	$ret = '';
	$str = $js;

	if ( $str != '' ) {
		if ( $delim != '' ) {
			// 区切り文字の有無？
			if ( strstr( $str, $delim ) ) {
				// 文字列中に区切り文字が存在する場合
				// 文字列を区切り文字を除いて分割する
				$array = explode( $delim, $str );
			} else {
				// 文字列をそのまま代入する
				$array = array( $str );
			}
			// タグを組み立てる
			$count = count( $array );
			for ( $i = 0; $i < $count; $i++ ) {
				$ret .= '<script type="text/javascript" src="'.$array[$i].'"></script>';
			}
		}
		return $ret;
	}
}

add_shortcode( 'jsInclude', 'sc_jsInclude' );


// Java Scripts Source Embed
function sc_jsScript( $atts, $content = null ) {
	extract( shortcode_atts( array(
	), $atts ) );

	$ret = '';
	$str = $content;

	if ( $str != '' ) {
		$ret .= "<script type='text/javascript'>"."\n";
		$str  = str_replace( "&#8216;", "'", $str );
		$str  = str_replace( "&#8217;", "'", $str );
		$str  = str_replace( "&#8242;", "'", $str );
		$str  = str_replace( "&gt;", ">", $str );
		$str  = str_replace( "&lt;", "<", $str );
		$str  = str_replace( "<br />", "", $str );
		$ret .= $str."\n";
		$ret .= "</script>"."\n";
		
		return $ret;
	}
}

add_shortcode( 'jsScript', 'sc_jsScript' );
?>
