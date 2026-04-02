<?php
/**
 * Plugin Name:       Update Plugin (最小構成)
 * Description:       WordPressプラグインの最小構成テンプレートです。
 * Version:           1.4.0
 * Author:            kamiki652
 * Text Domain:       update-plugin
 */

// セキュリティ対策：直接アクセスを禁止
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// プラグインのバージョン定義
if ( ! defined( 'UP_MINIMAL_VERSION' ) ) {
	define( 'UP_MINIMAL_VERSION', '1.4.0' );
}

/**
 * 自動更新機能（plugin-update-checker）の初期化
 */
add_action( 'plugins_loaded', function() {
	$library_path = __DIR__ . '/libs/plugin-update-checker/plugin-update-checker.php';

	if ( file_exists( $library_path ) ) {
		require_once $library_path;

		$update_checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
			'https://github.com/kamiki652/update-plugin',
			__FILE__,
			'update-plugin'
		);

		// 【追加】Privateリポジトリ用の認証設定
		// wp-config.php などで define( 'UP_GITHUB_TOKEN', 'ghp_...' ); と定義されている場合に使用
		if ( defined( 'UP_GITHUB_TOKEN' ) ) {
			$update_checker->setAuthentication( UP_GITHUB_TOKEN );
		}
	}
} );

/**
 * 管理画面へのメニュー追加
 */
add_action( 'admin_menu', 'up_min_add_admin_menu' );
function up_min_add_admin_menu() {
	add_menu_page( 'Update Plugin', 'Update Plugin', 'manage_options', 'update-plugin-admin', 'up_min_render_admin_page', 'dashicons-cloud-upload', 100 );
}

/**
 * バージョン管理ページの描画（デバッグ機能付き）
 */
function up_min_render_admin_page() {
	?>
	<div class="wrap">
		<h1>Update Plugin 管理</h1>
		<div class="card" style="max-width: 600px; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
			<h2 style="margin-top: 0; color: #1d2327;">現在のステータス</h2>
			<p>バージョン: <strong><?php echo esc_html( UP_MINIMAL_VERSION ); ?></strong></p>
			
			<hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
			
			<h3 style="color: <?php echo defined('UP_GITHUB_TOKEN') ? '#00a32a' : '#d63638'; ?>; margin-top: 0;">
				GitHub 通信テスト (Private 認証対応)
			</h3>
			
			<?php if ( ! defined( 'UP_GITHUB_TOKEN' ) ) : ?>
				<div style="background: #fff8e5; padding: 15px; border-left: 4px solid #ffb900; margin-bottom: 20px;">
					<strong>注意:</strong> 認証トークン（UP_GITHUB_TOKEN）が設定されていません。<br>
					リポジトリが Private の場合、このままでは 404 エラーになります。
				</div>
			<?php endif; ?>

			<?php
			$test_url = 'https://api.github.com/repos/kamiki652/update-plugin/releases/latest';
			$args = array(
				'user-agent' => 'WordPress/UpdatePluginTest',
				'timeout'    => 10,
				'headers'    => array()
			);

			// トークンがあればヘッダーに追加
			if ( defined( 'UP_GITHUB_TOKEN' ) ) {
				$args['headers']['Authorization'] = 'token ' . UP_GITHUB_TOKEN;
			}

			$response = wp_remote_get( $test_url, $args );
			$code = wp_remote_retrieve_response_code( $response );
			
			if ( is_wp_error( $response ) ) {
				echo '<div style="background: #fbe9eb; padding: 10px; border-left: 4px solid #d63638;">接続エラー: ' . esc_html( $response->get_error_message() ) . '</div>';
			} else {
				if ( $code === 200 ) {
					echo '<div style="background: #e7f5ed; padding: 10px; border-left: 4px solid #00a32a;">通信成功 (200 OK): Private リポジトリの認識に成功しました！</div>';
				} else {
					echo '<div style="background: #fbe9eb; padding: 10px; border-left: 4px solid #d63638;">通信失敗 (' . esc_html( $code ) . '): 指定されたリポジトリが見つかりません。</div>';
					echo '<p style="font-size: 0.9em; margin-top: 10px;">URL: <code>' . esc_html( $test_url ) . '</code></p>';
					if ( $code === 404 && ! defined( 'UP_GITHUB_TOKEN' ) ) {
						echo '<p><strong>原因:</strong> リポジトリが Private なのに、トークン（合鍵）がセットされていません。</p>';
					}
				}
			}
			?>
		</div>
	</div>
	<?php
}
