<?php
/**
 * Created by PhpStorm.
 * User: presleylupon
 * Date: 26/07/2018
 * Time: 12:33
 */

namespace App\Event;



use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    const NAME = 'user.registered';

    protected $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}