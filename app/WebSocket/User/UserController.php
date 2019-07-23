<?php


namespace App\WebSocket\User;


use Swoft\Http\Message\Request;
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
     */
    public function login($data)
    {
//        Session::mustGet()->push($data);
        return "789";
    }
}