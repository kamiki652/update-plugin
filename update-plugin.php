<?php
/**
 * Plugin Name:       Update Plugin (最小構成)
 * Description:       WordPressプラグインの最小構成テンプレートです。
 * Version:           1.0.0
 * Author:            kamiki652
 * Text Domain:       update-plugin
 */

// セキュリティ対策：直接アクセスを禁止
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// プラグインのバージョン定義
if ( ! defined( 'UP_MINIMAL_VERSION' ) ) {
	define( 'UP_MINIMAL_VERSION', '1.0.0' );
}

/**
 * 自動更新機能（plugin-update-checker）の初期化
 * 
 * プラグインが読み込まれた後に実行されるように hooks を利用します。
 */
add_action( 'plugins_loaded', function() {
	$library_path = __DIR__ . '/libs/plugin-update-checker/plugin-update-checker.php';

	if ( file_exists( $library_path ) ) {
		require_once $library_path;

		// GitHubリポジトリとの連携設定
		// 正確な名前空間を使用し、URL末尾のスラッシュを除去して解析エラーを防止します。
		$update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
			'https://github.com/kamiki652/update-plugin',
			__FILE__,
			'update-plugin'
		);
	}
} );

/**
 * 管理画面へのメニュー追加
 */
add_action( 'admin_menu', 'up_min_add_admin_menu' );
function up_min_add_admin_menu() {
	add_menu_page(
		'Update Plugin',              // ページタイトル
		'Update Plugin',              // メニュータイトル
		'manage_options',             // 権限許可
		'update-plugin-admin',        // メニュースラグ
		'up_min_render_admin_page',   // 表示用関数
		'dashicons-cloud-upload',     // アイコン
		100                           // 表示順
	);
}

/**
 * バージョン管理ページの描画
 */
function up_min_render_admin_page() {
	?>
	<div class="wrap">
		<h1>Update Plugin 管理</h1>
		<div class="card" style="max-width: 450px; padding: 20px; margin-top: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
			<h2 style="margin-top: 0; color: #1d2327;">現在のステータス</h2>
			<p>GitHub リポジトリ（kamiki652/update-plugin）と連携して自動更新をチェックしています。</p>
			<hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
			<p style="font-size: 1.1em;">
				<strong>プラグインバージョン:</strong> 
				<span style="color: #2271b1; font-weight: bold; background: #f0f6fb; padding: 2px 8px; border-radius: 4px;">
					<?php echo esc_html( UP_MINIMAL_VERSION ); ?>
				</span>
			</p>
		</div>
		<p style="color: #646970; margin-top: 15px;">
			※ 新しいリリースがGitHubで公開されると、WordPressの更新画面に通知が表示されます。
		</p>
	</div>
	<?php
}
