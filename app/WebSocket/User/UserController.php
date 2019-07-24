<?php


namespace App\WebSocket\User;


use Swoft\Http\Message\Request;
use Swoft\Redis\Redis;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class UserController
 * @package App\WebSocket\User
 * @WsController()
 */
class UserController
{

    /**
     * @param Request $request
     * @param int $fd
     * @MessageMapping()
     * @return array
     */
    public function login($data)
    {
        $requestData = json_decode($data, true);
        //TODO:: token 验证
        $userId = $requestData['user_id'];
        $fd = Session::mustGet()->getFd();

        Redis::hSet('user-fd', 'user-id-' . $userId, $fd);
        Redis::hSet('user-id', 'user-fd-' . $fd, $userId);

        return [
            'result' => [
                'code' => 0,
                'msg' => "success",
                'data' => []
            ]
        ];
    }
}