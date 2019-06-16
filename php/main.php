<?php
/**
 * WASABI Client Helper
 */
namespace pickles2\wasabiClientHelper;

/**
 * Main
 */
class main{

	/** Guzzle HTTP Client */
	private $guzzle;

	/**
	 * Constructor
	 */
	public function __construct(){
		$this->guzzle = new \GuzzleHttp\Client;
	}

}
