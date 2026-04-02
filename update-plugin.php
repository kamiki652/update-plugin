<?php
/**
 * Plugin Name:       Update Plugin (最小構成)
 * Description:       WordPressプラグインの最小構成テンプレートです。
 * Version:           1.5.0
 * Author:            kamiki652
 * Text Domain:       update-plugin
 */

// セキュリティ対策：直接アクセスを禁止
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// プラグインのバージョン定義
if ( ! defined( 'UP_MINIMAL_VERSION' ) ) {
	define( 'UP_MINIMAL_VERSION', '1.5.0' );
}

/**
 * 自動更新機能（plugin-update-checker）の初期化
 */
add_action( 'plugins_loaded', function() {
	$library_path = __DIR__ . '/libs/plugin-update-checker/plugin-update-checker.php';

	if ( file_exists( $library_path ) ) {
		require_once $library_path;

		// GitHubリポジトリとの連携設定（Publicリポジトリ用）
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
		'Update Plugin',
		'Update Plugin',
		'manage_options',
		'update-plugin-admin',
		'up_min_render_admin_page',
		'dashicons-cloud-upload',
		100
	);
}

/**
 * バージョン管理ページの描画
 */
function up_min_render_admin_page() {
	?>
	<div class="wrap">
		<h1>Update Plugin 管理</h1>
		<div class="card" style="max-width: 450px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: #fff;">
			<h2 style="margin-top: 0; color: #1d2327;">現在のステータス</h2>
			<p>GitHub と連携して自動更新を確認しています。</p>
			<hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
			<p style="font-size: 1.2em;">
				<strong>バージョン:</strong> 
				<span style="color: #2271b1; font-weight: bold; background: #f0f6fb; padding: 2px 8px; border-radius: 4px;">
					<?php echo esc_html( UP_MINIMAL_VERSION ); ?>
				</span>
			</p>
		</div>
		<p style="color: #646970; margin-top: 20px;">
			※ 公開リポジトリからの自動更新が有効になっています。
		</p>
	</div>
	<?php
}
