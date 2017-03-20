<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {
	protected $table = 'stores';

	protected $fillable = [
		'storeNumber',
		'storeName',
		'address',
		'siteId',
		'lat',
		'lon',
		'phoneNumber',
		'cfsFlag'
	];

	public function getJsonData() {
		return [
			'storeNumber' => (int) $this->storeNumber,
			'storeName'   => $this->storeName
		];
	}
}
