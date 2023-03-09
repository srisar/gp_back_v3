<?php

namespace App\Core\Services;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class StorageSystem
{

	private static LocalFilesystemAdapter|null $adapter = null;
	private static Filesystem|null $filesystem = null;

	public static function setAdapter( string $rootPath ): void
	{
		self::$adapter = new LocalFilesystemAdapter( $rootPath );
		self::$filesystem = new Filesystem( self::$adapter );
	}


	public static function get(): ?Filesystem
	{
		return self::$filesystem;
	}

}