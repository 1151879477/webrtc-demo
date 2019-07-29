<?php


namespace App\WebSocket\User;


use App\Model\Dao\UserDao;
use Co\Context;
use Swoft\Http\Message\Request;
use Swoft\Redis\Redis;
use Swoft\Session\Session;
use Swoft\WebSocket\Server\Annotation\Mapping\MessageMapping;
use Swoft\WebSocket\Server\Annotation\Mapping\WsController;

/**
 * Class UserController
 * @package App\WebSocket\User
 * @WsController()
 */
class UserController
{

    /**
     * @param Request $request
     * @param int $fd
     * @MessageMapping()
     * @return array
     */
    public function login($data)
    {
        $requestData = json_decode($data, true);
        //TODO:: token 验证
        $userId = $requestData['user_id'];
        $fd = Session::mustGet()->getRequest()->getFd();


        Redis::hSet('rt-user-fd', 'user-id-' . $userId, $fd);
        Redis::hSet('rt-user-id', 'user-fd-' . $fd, $userId);

        $userDao = new UserDao();
        $user = $userDao->getUserById($userId);

        broadcast(23323);
        server()->broadcast(json_encode([
            'type' => 'othUser.login',
            'user' => $user
        ]), [], [], $fd);

        return [
            'result' => [
                'code' => 0,
                'msg' => "success",
                'data' => []
            ]
        ];
    }

    /**
     * @MessageMapping()
     */
    public function loginList($data)
    {
        $requestData = json_decode($data, true);
        $userId = $requestData['user_id'];

        $userDao = new UserDao();
        $userDao->setReturnQuery(true);
        $users = $userDao->getLoginUsers()
            ->where('id', '<>', $userId)
            ->paginate(intval(1), 20);

        return [
            'type' => "user.loginList",
            'result' => [
                'code' => 0,
                'msg' => "success",
                'data' => $users
            ]
        ];
    }
    
    /**
     * @MessageMapping()
     */
    public function offer($data)
    {
        $requestData = json_decode($data, true);
        $connectUserId = $requestData['connectUserId'];
        $userDao = new UserDao();

        $connectUserFd = $userDao->getUserFdByUserId($connectUserId);
        $sendData = [
            'type' => 'user.offer',
            'offer' => $requestData['offer'],
            'connectUserId' => $requestData['user_id']
        ];
        server()->sendTo($connectUserFd, json_encode($sendData));
    }


    /**
     * @param $data
     * @MessageMapping()
     */
    public function answer($data)
    {
        $requestData = json_decode($data, true);
        $connectUserId = $requestData['connectUserId'];
        $userDao = new UserDao();

        $connectUserFd = $userDao->getUserFdByUserId($connectUserId);
        $sendData = [
            'type' => 'user.answer',
            'answer' => $requestData['answer']
        ];
        server()->sendTo($connectUserFd, json_encode($sendData));
    }


    /**
     * @param $data
     * @MessageMapping()
     */
    public function candidate($data)
    {
        $requestData = json_decode($data, true);
        $connectUserId = $requestData['connectUserId'];
        $userDao = new UserDao();

        $connectUserFd = $userDao->getUserFdByUserId($connectUserId);
        $sendData = [
            'type' => 'user.candidate',
            'candidate' => $requestData['candidate'],
            'candidateType' => $requestData['candidateType']
        ];
        server()->sendTo($connectUserFd, json_encode($sendData));
    }


    /**
     * @param $data
     * @MessageMapping()
     */
    public function userToUser($data)
    {
        $requestData = json_decode($data, true);
        $toUserId = $requestData['to'];
        $fromUserId = $requestData['from'];
        $subject = $requestData['subject'];
        $data = $requestData['data'];
        $time = time();

        $userDao = new UserDao();
        $toUserFd = $userDao->getUserFdByUserId($toUserId);
        if(!$toUserFd){
            return;
        }

        $sendData = [
            'subject' => $subject,
            'fromUserId' => $fromUserId,
            'data' => $data,
            'time' => $time
        ];

        server()->sendTo($toUserFd, json_encode($sendData, 256));
    }
}