<?php


namespace App\WebSocket;

use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\WebSocket\Server\Message\Request;

/**
 * Class UserModule
 * @package App\WebSocket
 * @WsModule(
 *     "/user"
 * )
 */
class UserModule
{
    /**
     * @param Request $request
     * @param int $fd
     * @OnOpen()
     */
    public function onOpen(Request $request, int $fd)
    {
        echo $fd.'is connect';
        server()->push($fd, 'user module is open');
    }
}