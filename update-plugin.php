<?php
/**
 * Plugin Name:       Update Plugin (最小構成)
 * Description:       WordPressプラグインの最小構成テンプレートです。
 * Version:           1.2.0
 * Author:            kamiki652
 * Text Domain:       update-plugin
 */


// 直接アクセスされた場合は処理を中断する（セキュリティ対策）
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 定数の定義
if ( ! defined( 'UPDATE_PLUGIN_VERSION' ) ) {
	define( 'UPDATE_PLUGIN_VERSION', '1.2.0' );
}

// 自動更新ライブラリの読み込みと初期化
if ( file_exists( __DIR__ . '/libs/plugin-update-checker/plugin-update-checker.php' ) ) {
	require_once __DIR__ . '/libs/plugin-update-checker/plugin-update-checker.php';

	$myUpdateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/kamiki652/update-plugin/',
		__FILE__,
		'update-plugin'
	);
}

/**
 * 管理画面にメニューを追加
 */
add_action( 'admin_menu', function() {
	add_menu_page(
		'Update Plugin',              // ページタイトル
		'Update Plugin',              // メニュータイトル
		'manage_options',             // 権限
		'update-plugin-settings',     // メニュースラグ
		'update_plugin_render_page',  // 表示用関数
		'dashicons-cloud-upload',     // アイコン
		100                           // 位置
	);
} );

/**
 * バージョン表示ページの内容を描画
 */
function update_plugin_render_page() {
	?>
	<div class="wrap">
		<h1>Update Plugin 管理</h1>
		<div class="card" style="max-width: 400px; padding: 20px; margin-top: 20px;">
			<h2 style="margin-top: 0;">ステータス</h2>
			<p>バージョン情報を確認できます。</p>
			<hr>
			<p style="font-size: 1.2em;">
				<strong>現在のバージョン:</strong> 
				<span style="color: #0073aa; font-weight: bold;"><?php echo esc_html( UPDATE_PLUGIN_VERSION ); ?></span>
			</p>
		</div>
		<p>GitHubのリポジトリで新しいリリースが公開されると、自動的に更新通知が表示されます。</p>
	</div>
	<?php
}

