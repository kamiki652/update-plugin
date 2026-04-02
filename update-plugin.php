<?php
/**
 * Plugin Name:       Update Plugin (最小構成)
 * Description:       WordPressプラグインの最小構成テンプレートです。
 * Version:           1.0.0
 * Author:            kamiki652
 * Text Domain:       update-plugin
 */

// 直接アクセスされた場合は処理を中断する（セキュリティ対策）
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 動作確認用：管理画面にメッセージを表示する
 */
add_action( 'admin_notices', function() {
    echo '<div class="notice notice-success is-dismissible"><p>Update Plugin が有効化されました！</p></div>';
} );
