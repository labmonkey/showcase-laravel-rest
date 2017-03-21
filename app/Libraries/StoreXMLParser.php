<?php
namespace App\Libraries;

use App\Store;

class StoreXMLParser {

	/*
	 * Error messages if any.
	 */
	protected $errors = [];

	/*
	 * All the raw data that was parsed from given XML file.
	 */
	protected $data = [];

	/*
	 * Count of records that were updated
	 */
	protected $updatedCount = 0;

	/*
	 * Count of records that were inserted
	 */
	protected $insertedCount = 0;

	/**
	 * Parse the XML file.
	 *
	 * @param $filePath
	 *
	 * @return bool Was the operation successful
	 */
	public function readXml( $filePath ) {
		if ( ! file_exists( $filePath ) ) {
			return false;
		}

		$doc = new \DOMDocument();
		$doc->load( $filePath );

		$xpath     = new \DOMXPath( $doc );
		$storeList = $xpath->query( "//store" );

		/*
		 * attribute => XPath
		 */
		$paths = [
			'storeNumber' => "number",
			'storeName'   => "name",
			'address'     => "address",
			'siteId'      => "siteid",
			'lat'         => "coordinates//lat",
			'lon'         => "coordinates//lon",
			'phoneNumber' => "phone_number",
			'cfsFlag'     => "cfs_flag"
		];

		/*
		 * This loops through all the <store> nodes and makes XPath queries on them
		 */
		foreach ( $storeList as $index => $store ) {
			$data = [];
			foreach ( $paths as $attribute => $path ) {
				$value              = $xpath->query( $path, $store )->item( 0 )->nodeValue;
				$value              = implode( ', ', explode( "\n", trim( $value ) ) );
				$data[ $attribute ] = $value;
			}

			// If some data was missing then we consider the node to be corrupt and add error
			$validData = array_filter( $data );
			if ( count( $data ) !== count( $validData ) ) {
				$this->errors[ $index ] = array_keys( array_diff_key( $data, $validData ) );
			} else {
				$this->data[] = $data;
			}
		}

		return true;
	}

	/**
	 * Converts data into Models and saves them into database
	 */
	public function save() {
		foreach ( $this->data as $data ) {
			$store  = Store::firstOrNew( [ 'storeNumber' => (int) $data['storeNumber'] ] );
			$exists = $store->exists;

			$store->storeNumber = (int) $data['storeNumber'];
			$store->storeName   = $data['storeName'];
			$store->address     = $data['address'];
			$store->siteId      = (int) $data['siteId'];
			$store->lat         = (float) $data['lat'];
			$store->lon         = (float) $data['lon'];
			$store->phoneNumber = $data['phoneNumber'];
			$store->cfsFlag     = $data['cfsFlag'] === 'Y';

			$store->save();

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