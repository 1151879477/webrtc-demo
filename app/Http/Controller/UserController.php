<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Http\Middleware\TestMiddleware;
use App\Model\Entity\User;
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
     *     @Middleware(TestMiddleware::class)
     * })
     * @return array
     * @throws ContainerException
     * @throws \ReflectionException
     * @throws \Swoft\Db\Exception\DbException
     */
    public function index()
    {
        $user = User::new([
            'username' => 'daiyu',
            'password' => md5('123456')
        ]);

        $user->save();
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => []
        ];
    }


    /**
     * @RequestMapping("create")
     * @throws \Throwable
     */
    public function create()
    {
        return view('index/index');

    }

}