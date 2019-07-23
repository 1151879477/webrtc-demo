<?php


namespace App\WebSocket;

use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\Http\Message\Request;

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
        server()->push($fd, 'user module is open');
    }

    /**
     * @CommandMapping("home.index")
     */
    public function test(Request $request, int $fd)
    {
        server()->push($fd, 'home.index is open');
    }
}