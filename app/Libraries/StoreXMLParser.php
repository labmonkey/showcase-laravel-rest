<?php
namespace App\Libraries;

use App\Store;

class StoreXMLParser {

	protected $errors = [];

	protected $data;

	protected $models;

	protected $updatedCount = 0;

	protected $insertedCount = 0;

	public function readXml( $filePath ) {
		$doc = new \DOMDocument();
		$doc->load( $filePath );

		$xpath     = new \DOMXPath( $doc );
		$storeList = $xpath->query( "//store" );
		$paths     = [
			'storeNumber' => "number",
			'storeName'   => "name",
			'address'     => "address",
			'siteId'      => "siteid",
			'lat'         => "coordinates//lat",
			'lon'         => "coordinates//lon",
			'phoneNumber' => "phone_number",
			'cfsFlag'     => "cfs_flag"
		];

		foreach ( $storeList as $index => $store ) {
			$data = [];
			foreach ( $paths as $attribute => $path ) {
				$value              = $xpath->query( $path, $store )->item( 0 )->nodeValue;
				$value              = implode( ', ', explode( "\n", trim( $value ) ) );
				$data[ $attribute ] = $value;
			}
			$validData = array_filter( $data );
			if ( count( $data ) !== count( $validData ) ) {
				$this->errors[ $index ] = array_keys( array_diff_key( $data, $validData ) );
			} else {
				$this->data[] = $data;
			}
		}
	}

	public function save() {
		foreach ( $this->data as $data ) {
			$store  = Store::firstOrNew( [ 'storeNumber' => (int) $data['storeNumber'] ] );
			$exists = $store->exists;

			$store->storeNumber = (int) $data['storeNumber'];
			$store->storeName   = $data['storeName'];
			$store->address     = $data['storeNumber'];
			$store->siteId      = (int) $data['storeNumber'];
			$store->lat         = (float) $data['storeNumber'];
			$store->lon         = (float) $data['storeNumber'];
			$store->phoneNumber = $data['storeNumber'];
			$store->cfsFlag     = $data['storeNumber'] === 'Y';

			$store->save();

			$this->models[] = $store;

			if ( $exists ) {
				$this->updatedCount ++;
			} else {
				$this->insertedCount ++;
			}
		}
	}

	public function getErrors() {
		return $this->errors;
	}

	public function getErrorCount() {
		return count( $this->getErrors() );
	}

	public function getInsertedCount() {
		return $this->insertedCount;
	}

	public function getUpdatedCount() {
		return $this->updatedCount;
	}
}