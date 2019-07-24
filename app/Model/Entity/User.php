<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;
use Swoft\Db\Eloquent\Model;


/**
 * 
 * Class User
 *
 * @since 2.0
 *
 * @Entity(table="users")
 */
class User extends Model
{
    /**
     * 
     * @Id()
     * @Column()
     * @var int|null
     */
    private $id;

    /**
     * 
     *
     * @Column()
     * @var string
     */
    private $username;

    /**
     * 
     *
     * @Column(hidden=true)
     * @var string
     */
    private $password;


    /**
     * @Column()
     * @var int
     */
    private $online;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getOnline(): int
    {
        return $this->online;
    }

    /**
     * @param int $online
     */
    public function setOnline(int $online): void
    {
        $this->online = $online;
    }


}
