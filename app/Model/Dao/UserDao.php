<?php declare(strict_types=1);


namespace App\Model\Dao;


use App\Model\Entity\User;
use Co\Context;
use Swoft\Redis\Redis;

class UserDao
{
    private $returnQuery=false;

    public function getLoginUserIds()
    {
        $userIds = Redis::hGetAll('rt-user-id');
        return array_values($userIds);
    }

    public function getLoginUsers($page = 1, $perPage = 20, $searchQuery = [])
    {
        $loginIds = $this->getLoginUserIds();
        $query = User::whereIn('id', $loginIds);

        if($this->isReturnQuery()){
            return $query;
        }else{
            return $query->paginate($page, $perPage);
        }
    }


    public function getLoginUserId()
    {
        $fd= \Swoft\Context\Context::mustGet()->getFd();
        $userId = Redis::hGet('rt-user-id', 'user-fd', $fd);
        return $userId;
    }

    public function getLoginUser()
    {
        $userId = $this->getLoginUserId();
        $user = User::whereKey($userId)->first();
        return $user;
    }

    /**
     * @return bool
     */
    public function isReturnQuery(): bool
    {
        return $this->returnQuery;
    }

    /**
     * @param bool $returnQuery
     */
    public function setReturnQuery(bool $returnQuery): void
    {
        $this->returnQuery = $returnQuery;
    }

}