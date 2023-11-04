<?php

class HvyEquipTypes extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'webhvytypes';

    protected $primaryKey = 'Code';

    public $timestamps = false;
}
