<?php


class PersonalProperty extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'webitems';

	protected $primaryKey = 'VehicleID';
        
        public $timestamps = false;
        
        public function type() {
            return $this->hasOne('PpTypes', 'VehicleType', 'VehicleType');
        }

}