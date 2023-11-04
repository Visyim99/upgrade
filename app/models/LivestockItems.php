<?php


class LivestockItems extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'weblivestock';

	protected $primaryKey = 'OtherID';
        
        public $timestamps = false;

}