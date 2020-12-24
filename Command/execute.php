<?php
/**
 * コマンド実行
 *
 * php ./Command/execute.php コマンド名 引数1 引数2 ...
 */

require dirname(__FILE__) . '/../dispatcher.php';

$class = $argv[1];
$class = "\Command\\{$class}";
$command = new $class($argv);

$command->execute();
