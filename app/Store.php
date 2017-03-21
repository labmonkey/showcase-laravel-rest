<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {
	protected $table = 'stores';

	/*
	 * Allows those attributes to be set and saved
	 */
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

	/**
	 * @return array Basic store data in JSON compatible format.
	 */
	public function getJsonData() {
		return [
			'storeNumber' => (int) $this->storeNumber,
			'storeName'   => $this->storeName
		];
	}

	/**
	 * @return array Basic store data of all available stores in JSON compatible format.
	 */
	public static function allJsonData() {
		$stores = Store::all();
		if ( $stores->count() ) {
			$data = [];
			foreach ( $stores as $store ) {
				$data[] = $store->getJsonData();
			}

			return $data;
		}

		return [];
	}
}
