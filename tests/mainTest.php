<?php
use PHPUnit\Framework\TestCase;

/**
 * test: Main
 */
final class mainTest extends TestCase{

	public function setup(){
		mb_internal_encoding('UTF-8');
	}

	/**
	 * WASABIをセットアップ
	 */
	public function testSetup(){
		$current_dir = realpath('.');
		if( !is_file(__DIR__.'/_wasabi/composer.json') ){
			if(!is_dir(__DIR__.'/_wasabi/')){
				mkdir(__DIR__.'/_wasabi/');
			}
			chdir(__DIR__.'/_wasabi/');
			exec('git clone https://github.com/pickles2/app-wasabi.git ./');
			exec('composer install');
			touch('./database/database.sqlite');
			copy(__DIR__.'/testdata/setup_files/dotenv.txt', '.env');
			chdir(__DIR__.'/_wasabi/public/');
			exec('php ../artisan migrate --seed');
			exec('php ../artisan db:seed --class=DummyDataSeeder');
			chdir($current_dir);
		}

		$this->assertTrue( is_file(__DIR__.'/_wasabi/composer.json') );
		$this->assertTrue( is_file(__DIR__.'/_wasabi/composer.lock') );
		$this->assertTrue( is_dir(__DIR__.'/_wasabi/vendor/') );
		$this->assertTrue( is_file(__DIR__.'/_wasabi/database/database.sqlite') );
	}

	/**
	 * WASABI Client Helper をインスタンス化
	 */
	public function testCreateInstance(){
		$wasabiClientHelper = new \pickles2\wasabiClientHelper\main();
		$this->assertTrue( is_object($wasabiClientHelper) );
	}

}
