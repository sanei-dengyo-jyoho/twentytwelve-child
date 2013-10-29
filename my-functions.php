<?php
//JS、CSSのバージョン表記を削除する
function remove_cssjs_ver( $src ) {
	if( strpos( $src, '?ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );


/**
 * Custom Head Include
 */
function ad_custom_head() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		echo '
		<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
		<!--[if IE]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<![endif]-->
		<!--[if IE 6]>
			<meta http-equiv="Imagetoolbar" content="no">
		<![endif]-->
		<!--[if IE 8]>
			<meta http-equiv="X-Content-Type-Options" content="nosniff">
			<meta http-equiv="X-XSS-Protection" content="1;mode=block">
		<![endif]-->
		<!--[if lt IE 7]>
			<script type="text/javascript" src="'.get_bloginfo( 'url' ).'/wp-content/js-custom/IE7.js"></script>
			<script type="text/javascript" src="'.get_bloginfo( 'url' ).'/wp-content/js-custom/ie7-squish.js"></script>
			<script type="text/javascript" src="'.get_bloginfo( 'url' ).'/wp-content/js-custom/minmax.js"></script>
		<![endif]-->
		<!--[if lt IE 10]>
			<script type="text/javascript" src="'.get_bloginfo( 'url' ).'/wp-content/js-custom/enhance.js"></script>
			<script type="text/javascript">
			enhance({
				loadScripts: [
					{src: "'.get_bloginfo( 'url' ).'/wp-content/js-custom/excanvas.js", iecondition: "all"}
				]
			});
			</script>
		<![endif]-->
		' ;
	}
}
add_action( 'wp_head', 'ad_custom_head' );


/**
 * editor-style
 */
/*add_editor_style();*/


/**
 * Remove [Link rel="next"] from Header
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );


//添付ファイルの表示設定 を「なし」だけにする
/*function media_script_buffer_start() {
    ob_start();
}
add_action( 'post-upload-ui', 'media_script_buffer_start' );
  
function media_script_buffer_get() {
    $scripts = ob_get_clean();
    $scripts = preg_replace( '#<option value="post">.*?</option>#s', '', $scripts );
    $scripts = preg_replace( '#<option value="custom">.*?</option>#s', '', $scripts );
    $scripts = preg_replace( '#<option value="file" selected>.*?</option>#s', '', $scripts );
    echo $scripts;
}
add_action( 'print_media_templates', 'media_script_buffer_get' );*/


/**
 * delete << media link-url >>
 */
update_option( 'image_default_link_type', 'none' );


//attachment_id=ページに404を返す
add_action( 'template_redirect', 'gs_attachment_template_redirect' );
function gs_attachment_template_redirect() {
    if ( is_attachment() ) { // 添付ファイルの個別ページなら
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
    }
}


//画像挿入時にwidthとheight指定が入らないようにする
function remove_hwstring_from_image_tag( $html, $id, $alt, $title, $align, $size ) {
    list( $img_src, $width, $height ) = image_downsize($id, $size);
    $hwstring = image_hwstring( $width, $height );
    $html = str_replace( $hwstring, '', $html );
    return $html;
}
add_filter( 'get_image_tag', 'remove_hwstring_from_image_tag', 10, 6 );


/**
 * TinyMCE h1,h2 delete
 */
function custom_editor_settings( $initArray ){
	$initArray['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'custom_editor_settings' );


/**
 * 管理画面のロゴを非表示
 */
add_action( 'wp_before_admin_bar_render', 'hide_before_admin_bar_render' );

function hide_before_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );
	$wp_admin_bar->remove_menu( 'new-content' );
	if ( !current_user_can( 'administrator' ) )
		$wp_admin_bar->remove_menu( 'my-sites' );
}


/**
 * ダッシュボードウィジェットを（管理者以外には）非表示
 */
if ( !current_user_can( 'edit_users' ) ) {
	function remove_dashboard_widgets() {
		global $wp_meta_boxes;
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] ); // 最近のコメント
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] ); // 被リンク
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] ); // プラグイン
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] ); // クイック投稿
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] ); // 最近の下書き
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] ); // WordPressブログ
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] ); // WordPressフォーラム
	}
	add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );
}


/**
 * プロフィールから要らない項目を削除
 */
function hide_profile_fields( $contactmethods ) {
	unset( $contactmethods['aim'] );
	unset( $contactmethods['jabber'] );
	unset( $contactmethods['yim'] );
	return $contactmethods;
}
add_filter( 'user_contactmethods', 'hide_profile_fields' );


/**
 * 「投稿」、「ページ」の不要なコンテンツを非表示
 */
function remove_default_post_screen_metaboxes() {
	remove_meta_box( 'postcustom','post','normal' ); // カスタムフィールド
	remove_meta_box( 'postexcerpt','post','normal' ); // 抜粋
	remove_meta_box( 'commentstatusdiv','post','normal' ); // ディスカッション
	remove_meta_box( 'commentsdiv','post','normal' ); // コメント
	remove_meta_box( 'trackbacksdiv','post','normal' ); // トラックバック
	remove_meta_box( 'slugdiv','post','normal' ); // スラッグ
	remove_meta_box( 'revisionsdiv','post','normal' ); // リビジョン
	remove_meta_box( 'tagsdiv-post_tag','post','normal' ); // タグ
}
add_action( 'admin_menu', 'remove_default_post_screen_metaboxes' );

function remove_default_page_screen_metaboxes() {
	remove_meta_box( 'postcustom','page','normal' ); // カスタムフィールド
	remove_meta_box( 'postexcerpt','page','normal' ); // 抜粋
	remove_meta_box( 'commentstatusdiv','page','normal' ); // ディスカッション
	remove_meta_box( 'commentsdiv','page','normal' ); // コメント
	remove_meta_box( 'trackbacksdiv','page','normal' ); // トラックバック
	remove_meta_box( 'revisionsdiv','page','normal' ); // リビジョン
}
add_action( 'admin_menu', 'remove_default_page_screen_metaboxes' );


/**
 * 投稿画面で記事のカテゴリーを１つだけ選択
 */
function limit_checkbox_amount() {
echo '<script type="text/javascript">
	jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery("ul#categorychecklist").before("<p>1つだけ選択できます。</p>");
			var count	=	jQuery("ul#categorychecklist li input[type=checkbox]:checked").length;
			var not		=	jQuery("ul#categorychecklist li input[type=checkbox]").not(":checked");
			if(count >= 1) { not.attr("disabled",true);}else{ not.attr("disabled",false);}
		jQuery("ul#categorychecklist li input[type=checkbox]").click(function(){
			var count	=	jQuery("ul#categorychecklist li input[type=checkbox]:checked").length;
			var not		=	jQuery("ul#categorychecklist li input[type=checkbox]").not(":checked");
			if(count >= 1) { not.attr("disabled",true);}else{ not.attr("disabled",false);}
		});
	});
	</script>';
}
add_action( 'admin_footer', 'limit_checkbox_amount' );


/**
 * 「投稿」、「ページ」に「Custom CSS File」メタボックスを追加
 */
add_action( 'admin_menu', 'custom_css_file_hooks' );
add_action( 'save_post', 'save_custom_css_file' );
add_action( 'wp_head','insert_custom_css_file' );
function custom_css_file_hooks() {
	add_meta_box( 'custom_css_file', 'Custom CSS File', 'custom_css_file_input', 'post', 'normal', 'high' );
	add_meta_box( 'custom_css_file', 'Custom CSS File', 'custom_css_file_input', 'page', 'normal', 'high' );
}
function custom_css_file_input() {
	global $post;
	echo '<input type="hidden" name="custom_css_file_noncename" id="custom_css_file_noncename" value="'.wp_create_nonce( 'custom-css-file' ).'" />';
	echo '<textarea name="custom_css_file" id="custom_css_file" rows="5" cols="30" style="width:100%;">'.get_post_meta( $post->ID, '_custom_css_file', true ).'</textarea>';
}
function save_custom_css_file( $post_id ) {
	if ( !wp_verify_nonce( $_POST['custom_css_file_noncename'], 'custom-css-file' ) ) return $post_id;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
	$custom_css = $_POST['custom_css_file'];
	update_post_meta( $post_id, '_custom_css_file', $custom_css );
}
function insert_custom_css_file() {
	if ( is_page() || is_single() ) {
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			echo '<link type="text/css" href="'.get_post_meta( get_the_ID(), '_custom_css_file', true ).'" rel="stylesheet" media="all" />';
		endwhile; endif;
		rewind_posts();
	}
}
/* ... 管理者以外には表示しない */
if ( !current_user_can( 'edit_users' ) ) {
	function remove_custom_css_file_metaboxes() {
		remove_meta_box( 'custom_css_file' , 'post' , 'normal' );
		remove_meta_box( 'custom_css_file' , 'page' , 'normal' );
	}
	add_action( 'admin_menu' , 'remove_custom_css_file_metaboxes' );
}


/**
 * 「投稿」、「ページ」に「Custom CSS」メタボックスを追加
 */
add_action( 'admin_menu', 'custom_css_hooks' );
add_action( 'save_post', 'save_custom_css' );
add_action( 'wp_head','insert_custom_css' );
function custom_css_hooks() {
	add_meta_box( 'custom_css', 'Custom CSS', 'custom_css_input', 'post', 'normal', 'high' );
	add_meta_box( 'custom_css', 'Custom CSS', 'custom_css_input', 'page', 'normal', 'high' );
}
function custom_css_input() {
	global $post;
	echo '<input type="hidden" name="custom_css_noncename" id="custom_css_noncename" value="'.wp_create_nonce( 'custom-css' ).'" />';
	echo '<textarea name="custom_css" id="custom_css" rows="5" cols="30" style="width:100%;">'.get_post_meta( $post->ID, '_custom_css', true ).'</textarea>';
}
function save_custom_css( $post_id ) {
	if ( !wp_verify_nonce( $_POST['custom_css_noncename'], 'custom-css' ) ) return $post_id;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
	$custom_css = $_POST['custom_css'];
	update_post_meta( $post_id, '_custom_css', $custom_css );
}
function insert_custom_css() {
	if ( is_page() || is_single() ) {
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			echo '<style type="text/css">'.get_post_meta( get_the_ID(), '_custom_css', true ).'</style>';
		endwhile; endif;
		rewind_posts();
	}
}
/* ... 管理者以外には表示しない */
if ( !current_user_can( 'edit_users' ) ) {
	function remove_custom_css_metaboxes() {
		remove_meta_box( 'custom_css' , 'post' , 'normal' );
		remove_meta_box( 'custom_css' , 'page' , 'normal' );
	}
	add_action( 'admin_menu' , 'remove_custom_css_metaboxes' );
}


/**
 * 「投稿」、「ページ」に「Custom JS File」メタボックスを追加
 */
add_action( 'admin_menu', 'custom_js_file_hooks' );
add_action( 'save_post', 'save_custom_js_file' );
add_action( 'wp_head','insert_custom_js_file' );
function custom_js_file_hooks() {
	add_meta_box( 'custom_js_file', 'Custom JS File', 'custom_js_file_input', 'post', 'normal', 'high' );
	add_meta_box( 'custom_js_file', 'Custom JS File', 'custom_js_file_input', 'page', 'normal', 'high' );
}
function custom_js_file_input() {
	global $post;
	echo '<input type="hidden" name="custom_js_file_noncename" id="custom_js_file_noncename" value="'.wp_create_nonce( 'custom-js-file' ).'" />';
	echo '<textarea name="custom_js_file" id="custom_js_file" rows="5" cols="30" style="width:100%;">'.get_post_meta( $post->ID, '_custom_js_file', true ).'</textarea>';
}
function save_custom_js_file( $post_id ) {
	if ( !wp_verify_nonce( $_POST['custom_js_file_noncename'], 'custom-js-file' ) ) return $post_id;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
	$custom_js = $_POST['custom_js_file'];
	update_post_meta( $post_id, '_custom_js_file', $custom_js );
}
function insert_custom_js_file() {
	if ( is_page() || is_single() ) {
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			echo '<script type="text/javascript" src="'.get_post_meta( get_the_ID(), '_custom_js_file', true ).'"></script>';
		endwhile; endif;
		rewind_posts();
	}
}
/* ... 管理者以外には表示しない */
if ( !current_user_can( 'edit_users' ) ) {
	function remove_custom_js_file_metaboxes() {
		remove_meta_box( 'custom_js_file' , 'post' , 'normal' );
		remove_meta_box( 'custom_js_file' , 'page' , 'normal' );
	}
	add_action( 'admin_menu' , 'remove_custom_js_file_metaboxes' );
}


/**
 * 「投稿」、「ページ」に「Custom JS」メタボックスを追加
 */
add_action( 'admin_menu', 'custom_js_hooks' );
add_action( 'save_post', 'save_custom_js' );
add_action( 'wp_head','insert_custom_js' );
function custom_js_hooks() {
	add_meta_box( 'custom_js', 'Custom JS', 'custom_js_input', 'post', 'normal', 'high' );
	add_meta_box( 'custom_js', 'Custom JS', 'custom_js_input', 'page', 'normal', 'high' );
}
function custom_js_input() {
	global $post;
	echo '<input type="hidden" name="custom_js_noncename" id="custom_js_noncename" value="'.wp_create_nonce( 'custom-js' ).'" />';
	echo '<textarea name="custom_js" id="custom_js" rows="5" cols="30" style="width:100%;">'.get_post_meta( $post->ID, '_custom_js', true ).'</textarea>';
}
function save_custom_js( $post_id ) {
	if ( !wp_verify_nonce( $_POST['custom_js_noncename'], 'custom-js' ) ) return $post_id;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
	$custom_js = $_POST['custom_js'];
	update_post_meta( $post_id, '_custom_js', $custom_js );
}
function insert_custom_js() {
	if ( is_page() || is_single() ) {
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			echo '<script type="text/javascript">'.get_post_meta( get_the_ID(), '_custom_js', true ).'</script>';
		endwhile; endif;
		rewind_posts();
	}
}
/* ... 管理者以外には表示しない */
if ( !current_user_can( 'edit_users' ) ) {
	function remove_custom_js_metaboxes() {
		remove_meta_box( 'custom_js' , 'post' , 'normal' );
		remove_meta_box( 'custom_js' , 'page' , 'normal' );
	}
	add_action( 'admin_menu' , 'remove_custom_js_metaboxes' );
}


/**
 * moreリンクの#を無効化
 */
function custom_content_more_link( $output ) {
	$output = preg_replace( '/#more-[\d]+/i', '', $output );
	return $output;
}
add_filter( 'the_content_more_link', 'custom_content_more_link' );
?>
