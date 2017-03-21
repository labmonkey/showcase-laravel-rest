<?php

namespace App\Http\Controllers;

use App\Libraries\FileDownloader;
use App\Libraries\StoreXMLParser;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller {

	/**
	 * Returns all stores.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index() {
		$stores = Store::allJsonData();

		if ( count( $stores ) ) {
			$json = [
				'success' => true,
				'data'    => $stores
			];
		} else {
			$json = [
				'success' => false,
				'message' => "Database Error. Maybe it's empty?"
			];
		}

		return response()->json( $json, 200, [], JSON_PRETTY_PRINT );
	}

	/**
	 * Returns random store or finds one in database with given $id.
	 * In case of error adds Message.
	 *
	 * @param string $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function storenumber( $id = null ) {

		if ( ! $id ) {
			// get random store
			$store = Store::inRandomOrder()->first();
			if ( $store ) {
				$json = [
					'success' => true,
					'data'    => [
						$store->getJsonData()
					]
				];
			} else {
				$json = [
					'success' => false,
					'message' => "Database Error. Maybe it's empty?"
				];
			}
		} else {
			// get store by id
			$store = Store::where( [ 'storeNumber' => $id ] )->first();
			if ( $store ) {
				$json = [
					'success' => true,
					'data'    => [
						$store->getJsonData()
					]
				];
			} else {
				$json = [
					'success' => false,
					'message' => "Store with given ID doesn't exist."
				];
			}
		}

		return response()->json( $json, 200, [], JSON_PRETTY_PRINT );
	}

	/**
	 * Removes all data from the database.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function clear() {
		DB::table( 'stores' )->delete();

		return response()->redirectTo( '/' );
	}

	/**
	 * Populates database with data. Requires url param to be provided.
	 * Returns errors in case of invalid data in file.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function download( Request $request ) {
		$url = $request->get( 'url' );

		$json = [
			'success' => false
		];

		if ( ! isset( $url ) ) {
			$json['message'] = "Missing file Url.";
		} else {
			$filePath = FileDownloader::downloadUrl( $url );
			if ( $filePath ) {
				$parser = new StoreXMLParser();
				$parser->readXml( $filePath );
				$parser->save();
				$json['success'] = true;
				if ( $parser->getErrors() ) {
					$json['message'] = "Successfully parsed XML but with errors.";
					$json['errors']  = $parser->getErrors();
					$json['count']   = [
						'updated' => $parser->getUpdatedCount(),
						'error'   => $parser->getErrorCount(),
						'success' => $parser->getInsertedCount()
					];
				} else {
					$json['message'] = "Successfully parsed XML without errors.";
				}
			} else {
				$json['message'] = "Missing file Url";
			}
		}

		return response()->json( $json, 200, [], JSON_PRETTY_PRINT );
	}
}
