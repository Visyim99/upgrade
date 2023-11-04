<?php

class Change extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'webchanges';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
