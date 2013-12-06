<?php
/********************************************************************************/
/* 投稿のリビジョン機能を無効化 */
/********************************************************************************/
define( 'WP_POST_REVISIONS', false );


/********************************************************************************/
/* 投稿の自動保存を無効化 */
/********************************************************************************/
function sed_disable_autosave() {
	wp_deregister_script( 'autosave' );
}

add_action( 'wp_print_scripts', 'sed_disable_autosave' );


/********************************************************************************/
/* flush関数を実行 */
/********************************************************************************/
function sed_force_flush() {
	/* flush関数を実行（バッファを吐かせる） */
	flush();
}

add_action( 'admin_head', 'sed_force_flush', 9999 );
add_action( 'wp_head', 'sed_force_flush', 9999 );


/********************************************************************************/
/* 「コメントは受け付けていません」 を消す */
/********************************************************************************/
function sed_remove_comments_are_closed($translated_text, $untranslated_text, $domain) {
	if ( $untranslated_text == 'Comments are closed.' ) {
		return '';
	}
	return $translated_text;
}

add_filter('gettext', 'sed_remove_comments_are_closed', 20, 3);


/********************************************************************************/
/* moreリンクの#を無効化 */
/********************************************************************************/
function custom_content_more_link( $output ) {
	$output = preg_replace( '/#more-[\d]+/i', '', $output );
	return $output;
}

add_filter( 'the_content_more_link', 'custom_content_more_link' );


/********************************************************************************/
/* imgのリンクの挿入を無効化 */
/********************************************************************************/
update_option( 'image_default_link_type', 'none' );


/********************************************************************************/
/* TinyMCE h1,h2 削除 */
/********************************************************************************/
function custom_editor_settings( $initArray ){
	$initArray['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
	return $initArray;
}

add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );


/********************************************************************************/
/* JS、CSSのバージョン表記を削除 */
/********************************************************************************/
function remove_cssjs_ver( $src ) {
	if ( strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}

add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );


/********************************************************************************/
/* ヘッダーの不要なものを削除 */
/********************************************************************************/
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
