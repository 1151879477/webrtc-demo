<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Http\Middleware\TestMiddleware;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\Middleware;
use Swoft\Http\Server\Annotation\Mapping\Middlewares;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoole\Http\Response;


/**
 * Class UserController
 * @package App\Http\Controller
 * @Controller()
 */
class UserController
{
    /**
     * @RequestMapping("index")
     * @Middlewares({
            @Middleware(TestMiddleware::class)
     })
     */
    public function index()
    {

        $response = Context::mustGet()->getResponse();
        $response->withData(['a'=>10]);

        return $response;
    }
}