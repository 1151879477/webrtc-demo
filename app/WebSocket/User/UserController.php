<?php


namespace App\WebSocket\User;


use Swoft\Http\Message\Request;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;

class UserController
{

    /**
     * @param Request $request
     * @param int $fd
     * @MessageMapping()
     */
    public function login(Request $request, int $fd)
    {
        server()->push($fd, "user.index");
    }
}