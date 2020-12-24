<?php
declare(strict_types=1);

namespace Command;

abstract class BaseCommand
{
    /**
     * @var float
     */
    protected $startTime;

    /**
     * @var array
     */
    protected $params;

    /**
     * construct
     */
    public function __construct(array $params = [])
    {
        $this->startTime = microtime(true);

        // execute.php での引数のうち、必要なものだけをセット
        if (isset($params)) {
            unset($params[0], $params[1]);
            // 添字をリセット
            $this->params = array_merge($params);
        }
    }

    /**
     * 標準出力させる
     *
     * @param string $message
     */
    public function out(string $message = null): void
    {
        $message = 'time: ' . (microtime(true) - $this->startTime) . ': ' . $message;
        fwrite(STDOUT, $message . PHP_EOL);
    }
}
