<?php declare(strict_types=1);


namespace App\Http\Controller;

use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoole\Http\Response;


/**
 * Class UserController
 * @package App\Http\Controller
 * @Controller()
 */
class UserController
{
    /**
     * @RequestMapping("index")
     */
    public function index()
    {


    }
}