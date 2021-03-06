<?php
/********************************************************************************/
/* ロゴのリンク先をホームページに変更する */
/********************************************************************************/
function custom_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'custom_login_logo_url' );


/********************************************************************************/
/* ヘッダーの追加設定 */
/********************************************************************************/
function ad_custom_head() {
	if ( !is_admin() ) {
		$ret  = "";

		$ret .= "\n";
		$ret .= "<meta http-equiv='X-Frame-Options' content='SAMEORIGIN'>" . "\n";
		$ret .= "<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>" . "\n";
		$ret .= "<!--[if lt IE 10]>" . "\n";
		$ret .= "<script type='text/javascript' src='";
		$ret .= get_bloginfo("url");
		$ret .= "/wp-content/js-custom/enhance.js'></script>" . "\n";
		$ret .= "<script type='text/javascript'>" . "\n";
		$ret .= "enhance({" . "\n";
		$ret .= "loadScripts: [{src: '";
		$ret .= get_bloginfo("url");
		$ret .= "/wp-content/js-custom/excanvas.js'";
		$ret .= ", iecondition: 'all'}]" . "\n";
		$ret .= "})" . "\n";
		$ret .= "</script>" . "\n";
		$ret .= "<![endif]-->";

		$ret .= "\n";
		$ret .= "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";

		echo $ret, PHP_EOL;
	}
}

add_action( 'wp_head', 'ad_custom_head' );


/********************************************************************************/
/* editor-style */
/********************************************************************************/
/*add_editor_style();*/


/********************************************************************************/
/* attachment_id=ページに404を返す */
/********************************************************************************/
function gs_attachment_template_redirect() {
	if ( is_attachment() ) { // 添付ファイルの個別ページなら
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
	}
}

add_action( 'template_redirect', 'gs_attachment_template_redirect' );


/********************************************************************************/
/* ダッシュボードウィジェットを管理者以外には非表示 */
/********************************************************************************/
if ( !current_user_can( 'edit_users' ) ) {
	function remove_dashboard_widgets() {
		global $wp_meta_boxes;
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );	// 最近のコメント
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );		// 被リンク
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );			// プラグイン
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );			// クイック投稿
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );		// 最近の下書き
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );				// WordPressブログ
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );			// WordPressフォーラム
	}

	add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );
}
