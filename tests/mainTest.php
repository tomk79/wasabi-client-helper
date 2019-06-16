<?php
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

/**
 * test: Main
 */
final class mainTest extends TestCase{

	private $server_process;

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
			exec('php artisan key:generate');
			exec('php artisan passport:keys');

			chdir(__DIR__.'/_wasabi/public/');
			exec('php ../artisan migrate --seed');
			exec('php ../artisan db:seed --class=DummyDataSeeder');
			chdir($current_dir);
		}

		$this->assertTrue( is_file(__DIR__.'/_wasabi/composer.json') );
		$this->assertTrue( is_file(__DIR__.'/_wasabi/composer.lock') );
		$this->assertTrue( is_dir(__DIR__.'/_wasabi/vendor/') );
		$this->assertTrue( is_file(__DIR__.'/_wasabi/database/database.sqlite') );

		$this->server_process = new Process("php -S localhost:8080 -t ./tests/_wasabi/public/");
		$this->server_process->start();
		usleep(100000); //wait for server to get going
	}

	/**
	 * WASABI Client Helper をインスタンス化
	 */
	public function testCreateInstance(){
		$wasabiClientHelper = new \pickles2\wasabiClientHelper\main();
		$this->assertTrue( is_object($wasabiClientHelper) );


		$http = new \GuzzleHttp\Client;
		try{
			$response = $http->request(
				'get',
				'http://localhost:8080/api/user',
				[
					'headers' => array(
						'Accept' => 'application/json',
					),
					'form_params' => [
					],
					'verify' => false,
				]
			);
			var_dump($response->getBody());
			// $remote_user_info = json_decode((string)$response->getBody());
		}catch(\Exception $e){
		}
		var_dump($e);

	}

}
