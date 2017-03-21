<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller {
	public function index() {
		$count = DB::table( 'stores' )->count();

		return view( 'index', [
			'defaultServerXMLUrl' => Config::get( 'custom.defaultServerXMLUrl' ),
			'localFileUrl'        => asset( 'storage/stores.xml.gz' ),
			'databaseCount'       => $count
		] );
	}
}
