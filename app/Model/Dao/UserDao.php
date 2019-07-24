<?php declare(strict_types=1);


namespace App\Model\Dao;


use App\Model\Entity\User;
use Swoft\Redis\Redis;

class UserDao
{
    public function getLoginUserIds()
    {
        $userIds = Redis::hGetAll('rt-user-id');
        return array_values($userIds);
    }

    public function getLoginUsers($page = 1, $perPage = 20, $searchQuery = [])
    {
        $loginIds = $this->getLoginUserIds();
        $users = User::whereIn('id', $loginIds)->paginate($page, $perPage);
        return $users;
    }
}