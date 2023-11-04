<?php


class PublicUser extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'webowners';

	protected $primaryKey = 'OwnerID';
        
        public $timestamps = false;
        
        public function ppItems() {
            return $this->hasMany('PersonalProperty', 'OwnerID');
        }
        
        public function ppMobiles() {
            return $this->hasMany('MobileHome', 'OwnerID');
        }
        
        public function ppHvyEquip() {
            return $this->hasMany('HeavyEquip', 'OwnerID');
        }
        
        public function ppLivestock() {
            return $this->hasMany('LivestockItems', 'OwnerID');
        }

}