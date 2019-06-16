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
	 * 普通にインスタンス化して実行してみるテスト
	 */
	public function testStandard(){
        $wasabiClientHelper = new \wasabiClientHelper\main();
		$this->assertTrue( is_object($wasabiClientHelper) );
	}

}
