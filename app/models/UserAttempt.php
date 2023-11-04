<?php

class UserAttempt extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_attempts';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
