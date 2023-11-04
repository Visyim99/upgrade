<?php


class AdminUser extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admin_users';

	protected $primaryKey = 'id';
        
        public $timestamps = false;

}