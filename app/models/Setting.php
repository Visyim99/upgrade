<?php


class Setting extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'settings';

	protected $primaryKey = 'name';
        
        public $timestamps = false;

}