<?php
class Fob {

	private static $instance;
	const BASEURL = 'http://api.mergado.com';
	private $url = self::BASEURL;


	public function __get($name){
		return self::appendFromProperty($this, $name);
	}

	//not in PHP! :(
	public static function __getStatic($name){
		$obj = new static();
		return self::appendFromProperty($obj, $name);
	}

	public static function __callStatic($method, $args) {

		$obj = new static();
		return self::appendFromMethod($obj, $method, $args);

	}

	public function __call($method, $args) {

		return self::appendFromMethod($this, $method, $args);

	}

	public static function getInstance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private static function appendFromMethod($obj ,$method, array $args){
		$obj->url .= '/'.urlencode($method);
		if($args){
			if(gettype($args[0]) === "integer" ){
				$obj->url .= '/'.urlencode($args[0]);
			}
		}
		return $obj;
	}

	private static function appendFromProperty($obj, $name){
		$obj->url .= '/'.urlencode($name);
		return $obj;
	}

	public function buildUrl(){
		echo $this->url;
		$this->url = self::BASEURL;
	}

	public function get(){
		echo $this->url;
		$this->url = self::BASEURL;
	}

	public static function call(){
		return new Fob();
	}


}

//$test = new Fob();
//$test->showStuff()->showChainedStuff()->get();
//echo "\n";
//$test->showMoreStuff('and me something else')->get();
//echo "\n";
//$test->showEvenMoreStuff();
//echo "\n";
//$test->thisDoesNothing('dadwa');
//$test->stats->shops(123)->get();
//echo "\n";
//
//Fob::call()->shops(123)->get();
//echo "\n";
//
//
Fob::shops(123)->blabla->asdk44()->buildUrl();
echo "\n";

//echo Fob::blabla;
//echo "\n";