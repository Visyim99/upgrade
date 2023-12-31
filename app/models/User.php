<?php

use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     */
    public function getReminderEmail(): string
    {
        return $this->email;
    }
}
