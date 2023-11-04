<?php


class Livestock extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'weblivestocktypes';

	protected $primaryKey = 'Code';
        
        public $timestamps = false;

}