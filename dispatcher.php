<?php
/**
 * 各処理に必要な読み込み、設定を行います
 */

require "./vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('ROOT', dirname(__FILE__));

// 共通関数
// FIXME basic.php など作成してまとめる
if (!function_exists('breakpoint')) {
    /**
     * ブレークポイント
     *
     * @link http://psysh.org/
     * ```
     * breakpoint();
     * ```
     * @return string|null
     */
    function breakpoint()
    {
        eval(\Psy\sh());
    }
}
// breakpoint のエイリアス
if (!function_exists('bp')) {
    function bp()
    {
        breakpoint();
    }
}
