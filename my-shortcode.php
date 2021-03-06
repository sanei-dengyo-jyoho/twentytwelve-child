<?php
//******************************************************************************
// div class="@"
//******************************************************************************
function sc_block( $atts, $content = null ) {
	extract(shortcode_atts(array(
			'class'	=>	'',
			'style'	=>	'',
	), $atts));

	$classdata = '';
	if ( $class != '' ) {
		$classdata = ' class="' . $class . '"';
	}
	$styledata = '';
	if ( $style != '' ) {
		$styledata = ' style="' . $style . '"';
	}

	$content = do_shortcode( $content );
	$ret  = '';
	$ret .= '<div'. $classdata . $styledata . '>';
	$ret .= $content;
	$ret .= '</div>';
	return $ret;
}

add_shortcode( 'block', 'sc_block' );


//******************************************************************************
// ol Title
//******************************************************************************
function sc_title( $atts, $content = null ) {
	extract(shortcode_atts(array(
			'start'		=>	'1',
			'tagname'	=>	'h2',
	), $atts));

	$content = do_shortcode( $content );
	$ret  = '';
	$ret .= '<' . $tagname . '>';
	$ret .= '<ol start="' . $start . '"><li>' . $content . '</li></ol>';
	$ret .= '</' . $tagname . '>';
	return $ret;
}

add_shortcode( 'title', 'sc_title' );


//******************************************************************************
// Google Charts
//******************************************************************************
function sc_gchart( $atts ) {
	extract(shortcode_atts(array(
			'id'	=>	'gchart_div',
			'style'	=>	'',
	), $atts));

	$styledata = '';
	if ( $style != '' ) {
		$styledata = ' style="' . $style . '"';
	}
	return '<div id="' . $id . '"' . $styledata . '></div>';
}

add_shortcode( 'gchart', 'sc_gchart' );


//******************************************************************************
//  Java Scripts source Link
//******************************************************************************
function sc_js_include( $atts ) {
	extract(shortcode_atts(array(
			'js'	=>	'',
			'delim'	=>	'::::',
	), $atts));

	$str = $js;
	$ret = '';

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
				$ret .= '<script type="text/javascript" src="' . $array[$i] . '"></script>';
			}
		}
	}
	return $ret;
}

add_shortcode( 'js_include', 'sc_js_include' );


//******************************************************************************
//  Java Scripts Embed
//******************************************************************************
function sc_js_embed( $atts, $content = null ) {
	$str = $content;
	$ret = '';

	if ( $str != '' ) {
		$ret .= "<script type='text/javascript'>" . "\n";
		$str  = str_replace( "&#8216;", "'", $str );
		$str  = str_replace( "&#8217;", "'", $str );
		$str  = str_replace( "&#8242;", "'", $str );
		$str  = str_replace( "&gt;", ">", $str );
		$str  = str_replace( "&lt;", "<", $str );
		$str  = str_replace( "<br />", "", $str );
		$ret .= $str . "\n";
		$ret .= "</script>" . "\n";
	}
	return $ret;
}

add_shortcode( 'js_embed', 'sc_js_embed' );
