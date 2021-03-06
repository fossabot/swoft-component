<?php
/**
 * This file is part of Swoft.
 *
 * @link https://swoft.org
 * @document https://doc.swoft.org
 * @contact group@swoft.org
 * @license https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Swoft\App;
use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Console\Helper\ConsoleUtil;
use Swoft\Core\Coroutine;
use Swoft\Devtool\DevTool;
use Swoft\Http\Message\Middleware\MiddlewareInterface;
use Swoft\Http\Message\Server\Request;

/**
 * Class DevToolMiddleware - Custom middleware
 * @Bean()
 * @package Swoft\Devtool\Middleware
 */
class DevToolMiddleware implements MiddlewareInterface
{
    /**
     * @Value("{$config.devtool.logHttpRequestToConsole}")
     * @var bool
     */
    public $logHttpRequestToConsole = true;

    // public function __construct()
    // {
    //     $this->logHttpRequestToConsole = \bean('config')->get('devtool.logHttpRequestToConsole', true);
    // }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface|Request $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // before request handle
        $path = $request->getUri()->getPath();

        if ($this->logHttpRequestToConsole) {
            ConsoleUtil::log(\sprintf('%s %s', $request->getMethod(), $path), [], 'debug', [
                'HttpServer',
                'WorkerId' => App::getWorkerId(),
                'CoId' => Coroutine::tid()
            ]);
        }

        // if not is ajax, always render vue index file.
        if (0 === \strpos($path, DevTool::ROUTE_PREFIX) && !$request->isAjax()) {
            $json = $request->query('json');

            if (null === $json) {
                return \view(App::getAlias('@devtool/web/dist/index.html'), []);
            }
        }

        $response = $handler->handle($request);

        // after request handle
        return $response->withAddedHeader('Swoft-DevTool-Version', '1.0.0');
    }
}
