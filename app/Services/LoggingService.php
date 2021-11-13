<?php

namespace App\Services;

use Illuminate\Support\Str;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggingService
{
    public static function getFileLogger()
    {
        $dateString = now()->format('m_d_Y');
        $filePath = 'web_hooks_' . $dateString . '.log';
        $dateFormat = "m/d/Y H:i:s";
        $output = "[%datetime%] %channel%.%level_name%: %message%\n";
        $formatter = new LineFormatter($output, $dateFormat);

        $stream = new StreamHandler(storage_path('logs/' . $filePath), Logger::DEBUG);
        $stream->setFormatter($formatter);

        $processId = Str::random(5);
        $logger = new Logger($processId);
        $logger->pushHandler($stream);

        return $logger;
    }
}
