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
 */
add_action( 'plugins_loaded', function() {
	$library_path = __DIR__ . '/libs/plugin-update-checker/plugin-update-checker.php';

	if ( file_exists( $library_path ) ) {
		require_once $library_path;

		// GitHubリポジトリとの連携設定
		// バージョン 5.x の標準的な初期化方法
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
 * バージョン管理ページの描画（デバッグ機能付き）
 */
function up_min_render_admin_page() {
	?>
	<div class="wrap">
		<h1>Update Plugin 管理</h1>
		<div class="card" style="max-width: 550px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
			<h2 style="margin-top: 0; color: #1d2327;">現在のステータス</h2>
			<p>バージョン情報を確認できます。</p>
			<hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
			<p style="font-size: 1.1em;">
				<strong>現在のバージョン:</strong> 
				<span style="color: #2271b1; font-weight: bold; background: #f0f6fb; padding: 2px 8px; border-radius: 4px;">
					<?php echo esc_html( UP_MINIMAL_VERSION ); ?>
				</span>
			</p>

			<hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
			
			<h3 style="color: #d63638; margin-top: 0;">GitHub 通信テスト</h3>
			<p>404エラーの原因を切り分けるためのテスト結果です：</p>
			<?php
			$test_url = 'https://api.github.com/repos/kamiki652/update-plugin/releases/latest';
			$response = wp_remote_get( $test_url, array(
				'user-agent' => 'WordPress/UpdatePluginTest',
				'timeout'    => 10
			) );
			$code = wp_remote_retrieve_response_code( $response );
			
			if ( is_wp_error( $response ) ) {
				echo '<div style="background: #fbe9eb; padding: 10px; border-left: 4px solid #d63638;">接続エラー: ' . esc_html( $response->get_error_message() ) . '</div>';
			} else {
				if ( $code === 200 ) {
					echo '<div style="background: #e7f5ed; padding: 10px; border-left: 4px solid #00a32a;">通信成功 (200 OK): リポジトリとリリースを正常に認識しています。</div>';
				} else {
					echo '<div style="background: #fbe9eb; padding: 10px; border-left: 4px solid #d63638;">通信失敗 (' . esc_html( $code ) . '): GitHub から 404 エラーが返されました。</div>';
					echo '<p style="font-size: 0.9em; margin-top: 10px;">URL: <code>' . esc_html( $test_url ) . '</code></p>';
					echo '<p style="color: #646970;">原因のヒント: リポジトリが Public であるか、リポジトリ名に間違いがないか確認してください。</p>';
				}
			}
			?>
		</div>
		<p style="color: #646970; margin-top: 15px;">
			※ このページで「通信成功」が出れば、自動更新の検知も正常に動くはずです。
		</p>
	</div>
	<?php
}
