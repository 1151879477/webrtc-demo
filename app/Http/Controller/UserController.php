<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Http\Middleware\TestMiddleware;
use App\Model\Dao\UserDao;
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
        return view('auth/login');
    }

    /**
     * @return \Swoft\Http\Message\Response
     * @throws \Throwable
     * @RequestMapping("/reg")
     */
    public function showRegForm()
    {
        return view('auth/reg');
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
     * @RequestMapping("/user/reg")
     */
    public function reg()
    {
        $request = Context::mustGet()->getRequest();
        $username = $request->post('username');
        $password = $request->post('password');

        //TODO: validate input params


        $exists = User::where('username', $username)->count();
        if($exists > 0){
            return [
                'result' => [
                    'code' => 1,
                    'msg' => '用户已经存在了',
                    'data' => []
                ]
            ];
        }


        $user = User::new([
            'username' => $username,
            'password' => md5($password)
        ]);

        return [
            'result' => [
                'code' => 0,
                'msg' => '用户已经存在了',
                'data' => $user
            ]
        ];
    }

    /**
     * @RequestMapping("/user/loginList")
     */
    public function getLoginUserList()
    {
        $request = Context::mustGet()->getRequest();
        $page = $request->get('page', 1);
        $userId = $request->get('user_id');

        $userDao = new UserDao();
        $userDao->setReturnQuery(true);
        $users = $userDao->getLoginUsers()->where('id', '<>', $userId)
            ->paginate(intval($page), 20);

        return [
            'result' => [
                'code' => 0,
                'msg' => 'success',
                'data' => $users
            ]
        ];
    }
}