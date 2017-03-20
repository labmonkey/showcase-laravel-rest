<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {
	protected $table = 'stores';

	public function getJsonData() {
		return [
			'storeNumber' => (int) $this->storeNumber,
			'storeName'   => $this->storeName
		];
	}
}
