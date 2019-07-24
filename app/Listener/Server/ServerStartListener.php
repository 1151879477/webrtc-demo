<?php


namespace App\Listener\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Redis\Redis;
use Swoft\Server\SwooleEvent;

/**
 * Class ServerStartListener
 * @package App\Listener\Server
 * @Listener(SwooleEvent::START)
 */
class ServerStartListener implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {
        echo "\n\n\n";

        $keys = Redis::keys('rt-*');
        foreach($keys as $key) {
            echo 'delete: '. $key;
            list($prefix, $key) = implode($key, config('name').'-');
            Redis::del($key);
        }

        echo "\n\n\n";
    }

}