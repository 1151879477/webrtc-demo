<?php

use App\Common\DbSelector;
use App\Process\MonitorProcess;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Task\Swoole\SyncTaskListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoft\Server\SwooleEvent;
use Swoft\Db\Database;
use Swoft\Redis\RedisDb;


echo "\n\n";
echo 'mysql:dbname='.env('DB_DATABASENAME').';host='.env('DB_HOST');
echo "\n\n";

return [
    'logger'           => [
        'flushRequest' => false,
        'enable'       => false,
        'json'         => false,
    ],
    'httpServer'       => [
        'class'    => HttpServer::class,
        'port'     => 9000,
        'listener' => [
            'rpc' => bean('rpcServer')
        ],
        'process' => [
//            'monitor' => bean(MonitorProcess::class)
        ],
        'on'       => [
//            SwooleEvent::TASK   => bean(SyncTaskListener::class),  // Enable sync task
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        /* @see HttpServer::$setting */
        'setting'  => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true,

        ]
    ],
    'httpDispatcher'   => [
        // Add global http middleware
        'middlewares' => [
            // Allow use @View tag
            \Swoft\View\Middleware\ViewMiddleware::class,
        ],
    ],
    'db'               => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname='.env('DB_DATABASENAME').';host='.env('DB_HOST'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
    ],
    'db2'              => [
        'class'      => Database::class,
        'dsn'        => 'mysql:dbname=test2;host=172.17.0.2',
        'username'   => 'root',
        'password'   => 'swoft123456',
        'dbSelector' => bean(DbSelector::class)
    ],
    'db2.pool'         => [
        'class'    => Pool::class,
        'database' => bean('db2')
    ],
    'db3'              => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=test2;host=172.17.0.2',
        'username' => 'root',
        'password' => 'swoft123456'
    ],
    'db3.pool'         => [
        'class'    => Pool::class,
        'database' => bean('db3')
    ],
    'migrationManager' => [
        'migrationPath' => '@app/Migration',
    ],
    'redis'            => [
        'class'    => RedisDb::class,
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
        'option' => [
            'prefix' => env('APP_NAME', 'swoft').'-'
        ]
    ],
    'user'             => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',
        'port'    => 18307,
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => bean('rpcClientPacket')
    ],
    'user.pool'        => [
        'class'  => ServicePool::class,
        'client' => bean('user')
    ],
    'rpcServer'        => [
        'class' => ServiceServer::class,
    ],
    'wsServer'         => [
        'class'   => WebSocketServer::class,
        'port'    => 9000,
        'on'      => [
            // Enable http handle
            SwooleEvent::REQUEST => bean(RequestListener::class),
        ],
        'debug'   => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'log_file' => alias('@runtime/swoole.log'),
            'enable_static_handler'    => true,
            // swoole v4.4.0以下版本, 此处必须为绝对路径
            'document_root'            => dirname(__DIR__) . '/public',
        ],
    ],
    'tcpServer'         => [
        'port'  => 18309,
        'debug' => 1,
    ],
    /** @see \Swoft\Tcp\Protocol */
    'tcpServerProtocol' => [
        'type'            => \Swoft\Tcp\Packer\SimpleTokenPacker::TYPE,
        // 'openLengthCheck' => true,
    ],
    'cliRouter'         => [
        // 'disabledGroups' => ['demo', 'test'],
    ],
    'view' => [
        // class 配置是可以省略的, 因为 view 组件里已经配置了它
//         'class' => \Swoft\View\Base\View::class,
        'viewsPath' => dirname(__DIR__) . '/resource/views/',
    ],
];
