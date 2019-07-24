<?php declare(strict_types=1);


namespace App\WebSocket;

use App\Model\Entity\User;
use App\WebSocket\User\UserController;
use Swoft\Console\Annotation\Mapping\CommandMapping;
use Swoft\Redis\Redis;
use Swoft\WebSocket\Server\Annotation\Mapping\OnClose;
use Swoft\WebSocket\Server\Annotation\Mapping\OnOpen;
use Swoft\WebSocket\Server\Annotation\Mapping\WsModule;
use Swoft\Http\Message\Request;
use Swoft\WebSocket\Server\MessageParser\TokenTextParser;
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

    /**
     * @param Request $request
     * @param int $fd
     * @throws \ReflectionException
     * @throws \Swoft\Bean\Exception\ContainerException
     * @throws \Swoft\Db\Exception\DbException
     * @OnClose()
     */
    public function onClose(Request $request, int $fd)
    {
        $userIdKey = 'user-'.$fd.'-id';
        $userId = Redis::hGet('user', $userIdKey);
        if(!$userId){
            return;
        }

        $userFdKey = 'user-'.$fd.'-fd';
        Redis::hDel('user', $userIdKey);
        Redis::hDel('user', $userFdKey);
    }

}