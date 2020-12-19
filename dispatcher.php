<?php
/**
 * 各処理に必要な読み込み、設定を行います
 */

require "./vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('ROOT', dirname(__FILE__));
