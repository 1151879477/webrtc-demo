<?php declare(strict_types=1);


namespace App\WebSocket;

use App\WebSocket\User\UserController;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\Http\Message\Request;

/**
 * Class UserModule
 * @package App\WebSocket
 * @WsModule(
 *     "/user",
 *     messageParser=TokenTextParser::class,
 *     controllers={UserController::class}
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

}