<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Storage;

class FileDownloader {

	/*
	 * Downloads file from given URL.
	 *
	 * @return string File path
	 */
	public static function downloadUrl( $url ) {
		$pathInfo = pathinfo( $url );

		// generate unique file name
		$fileName = 'xml/' . md5( $url ) . '_' . $pathInfo['filename'];

		// Save file. Unpacks it if it's gzip
		Storage::put( $fileName, isset( $pathInfo['extension'] ) && $pathInfo['extension'] === 'gz' ? gzopen( $url, 'r' ) : fopen( $url, 'r' ) );

		$storagePath = Storage::disk( 'local' )->getDriver()->getAdapter()->getPathPrefix();

		return $storagePath . '/' . $fileName;
	}

	/**
	 * Downloads file uploaded via Form
	 *
	 * @return string File path
	 */
	public static function downloadFile() {

	}
}