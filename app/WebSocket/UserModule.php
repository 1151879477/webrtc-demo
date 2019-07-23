<?php


namespace App\WebSocket;

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
    public function onOpen(Request $request, int $fd)
    {
        server()->push($fd, 'user module is open');
    }
}