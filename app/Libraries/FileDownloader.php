<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Storage;

class FileDownloader {

	public static function downloadUrl( $url ) {
		$pathInfo = pathinfo( $url );

		$fileName = 'xml/' . md5( $url ) . '_' . $pathInfo['filename'];

		Storage::put( $fileName, isset( $pathInfo['extension'] ) && $pathInfo['extension'] === 'gz' ? gzopen( $url, 'r' ) : fopen( $url, 'r' ) );

		$storagePath = Storage::disk( 'local' )->getDriver()->getAdapter()->getPathPrefix();

		return $storagePath . '/' . $fileName;
	}

}