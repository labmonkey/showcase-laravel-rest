<?php

namespace App\Http\Controllers;

use App\Libraries\FileDownloader;
use App\Libraries\StoreXMLParser;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller {
	public function index() {
		$stores = Store::all();
		if ( $stores->count() ) {
			$data = [];
			foreach ( $stores as $store ) {
				$data[] = $store->getJsonData();
			}
			$json = [
				'success' => true,
				'data'    => $data
			];
		} else {
			$json = [
				'success' => false,
				'message' => "Database Error. Maybe it's empty?"
			];
		}

		return response()->json( $json, 200, [], JSON_PRETTY_PRINT );
	}

	public function storenumber( $id = null ) {

		if ( ! $id ) {
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

	public function clear() {
		DB::table( 'stores' )->delete();

		return response()->redirectTo( '/' );
	}

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
