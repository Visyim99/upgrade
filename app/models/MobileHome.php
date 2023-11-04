<?php

class MobileHome extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'webmobiles';

    protected $primaryKey = 'MobileID';

    public $timestamps = false;
}
