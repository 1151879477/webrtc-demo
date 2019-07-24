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
use Swoft\Redis\Redis;
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
     * @RequestMapping("/login")
     * @throws \Throwable
     */
    public function showLoginForm()
    {
        return view('index/index');
    }


    /**
     * @RequestMapping("/user/login")
     */
    public function login()
    {
        $request = Context::mustGet()->getRequest();
        $userName = $request->post('username');
        $password = $request->post('password');

        $user = User::where('username', $userName)->first();

        if ($user && md5($password) == $user->getPassword()) {
            return [
                'result' => [
                    'code' => 0,
                    'msg' => '登录成功',
                    'data' => $user
                ]
            ];
        } else {
            return [
                'result' => [
                    'code' => 1,
                    'msg' => '登录失败'
                ]
            ];
        }

    }

    /**
     * @RequestMapping("/user/loginList")
     */
    public function getLoginUserList()
    {
        $request = Context::mustGet()->getRequest();
        $userIds = Redis::hGetAll('rt-user-id');
        $page = $request->get('page', 1);
        $users = User::whereIn('id', $userIds)->paginate(intval($page), 20);

        var_dump($userIds);
        return [
            'result' => [
                'code' => 0,
                'msg' => 'success',
                'data' => $users
            ]
        ];
    }
}