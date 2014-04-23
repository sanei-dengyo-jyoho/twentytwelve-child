<?php
/********************************************************************************/
/* 管理画面のロゴを非表示 */
/********************************************************************************/
function sed_hide_before_admin_bar_render() {
	global $wp_admin_bar;

	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );
	$wp_admin_bar->remove_menu( 'new-content' );

	if ( !current_user_can( 'administrator' ) )
		$wp_admin_bar->remove_menu( 'my-sites' );
}

add_action( 'wp_before_admin_bar_render', 'sed_hide_before_admin_bar_render' );


/********************************************************************************/
/* プロフィールから要らない項目を削除 */
/********************************************************************************/
function sed_hide_profile_fields( $contactmethods ) {
	unset( $contactmethods['aim'] );
	unset( $contactmethods['jabber'] );
	unset( $contactmethods['yim'] );
	return $contactmethods;
}

add_filter( 'user_contactmethods', 'sed_hide_profile_fields' );


/********************************************************************************/
/* 投稿／ページ画面の不要なコンテンツを非表示 */
/********************************************************************************/
function sed_remove_default_post_screen_metaboxes() {
	remove_meta_box( 'postcustom', 'post', 'normal' );				// カスタムフィールド
	remove_meta_box( 'postexcerpt', 'post', 'normal' );				// 抜粋
	remove_meta_box( 'commentstatusdiv', 'post', 'normal' );		// ディスカッション
	remove_meta_box( 'commentsdiv', 'post', 'normal' );				// コメント
	remove_meta_box( 'trackbacksdiv', 'post', 'normal' );			// トラックバック
	remove_meta_box( 'slugdiv', 'post', 'normal' );					// スラッグ
	remove_meta_box( 'revisionsdiv', 'post', 'normal' );			// リビジョン
	if ( !current_user_can( 'administrator' ) )
		remove_meta_box( 'tagsdiv-post_tag', 'post', 'normal' );	// タグ
}

add_action( 'admin_menu', 'sed_remove_default_post_screen_metaboxes' );


function sed_remove_default_page_screen_metaboxes() {
	remove_meta_box( 'postcustom', 'page', 'normal' );				// カスタムフィールド
	remove_meta_box( 'postexcerpt', 'page', 'normal' );				// 抜粋
	remove_meta_box( 'commentstatusdiv', 'page', 'normal' );		// ディスカッション
	remove_meta_box( 'commentsdiv', 'page', 'normal' );				// コメント
	remove_meta_box( 'trackbacksdiv', 'page', 'normal' );			// トラックバック
	remove_meta_box( 'revisionsdiv', 'page', 'normal' );			// リビジョン
}

add_action( 'admin_menu', 'sed_remove_default_page_screen_metaboxes' );


/********************************************************************************/
/* 投稿画面で記事のカテゴリーを１つだけ選択 */
/********************************************************************************/
function sed_limit_checkbox_amount() {
	$ret  = "";

	$ret .= "\n";
	$ret .= "<script type='text/javascript'>" . "\n";
	$ret .= "jQuery.noConflict();" . "\n";
	$ret .= "jQuery(document).ready(function(){" . "\n";
	$ret .= "jQuery('ul#categorychecklist').before('<p>1つだけ選択できます。</p>');" . "\n";
	$ret .= "var count = jQuery('ul#categorychecklist li input[type=checkbox]:checked').length;" . "\n";
	$ret .= "var not = jQuery('ul#categorychecklist li input[type=checkbox]').not(':checked');" . "\n";
	$ret .= "if(count >= 1){not.attr('disabled',true);}else{not.attr('disabled',false);}" . "\n";
	$ret .= "jQuery('ul#categorychecklist li input[type=checkbox]').click(function(){" . "\n";
	$ret .= "var count = jQuery('ul#categorychecklist li input[type=checkbox]:checked').length;" . "\n";
	$ret .= "var not = jQuery('ul#categorychecklist li input[type=checkbox]').not(':checked');" . "\n";
	$ret .= "if(count >= 1){not.attr('disabled',true);}else{not.attr('disabled',false);}" . "\n";
	$ret .= "});" . "\n";
	$ret .= "});" . "\n";
	$ret .= "</script>";

	echo $ret, PHP_EOL;
}

add_action( 'admin_footer', 'sed_limit_checkbox_amount' );
