<?php


namespace App\Listener\Server;


use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Redis\Redis;

/**
 * Class ServerStartListener
 * @package App\Listener\Server
 * @Bean()
 */
class ServerStartListener implements EventHandlerInterface
{
    public function handle(EventInterface $event): void
    {
        echo "listener is run";
    }

}