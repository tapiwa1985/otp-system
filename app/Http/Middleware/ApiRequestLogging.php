<?php

namespace App\Http\Middleware;

use App\Services\LoggingService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Monolog\Logger;

class ApiRequestLogging
{
    /**
     * @var Logger
     */
    private $logger;

    public function __construct()
    {
        $this->logger = LoggingService::getFileLogger();
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->logger->info('Incoming request:');
        $this->logger->info($request);
        return $next($request);
    }

    /**
     * @param Request $request
     * @param JsonResponse $response
     */
    public function terminate(Request $request, JsonResponse $response)
    {
        $this->logger->info('Outgoing response:');
        $this->logger->info($response);
    }
}
