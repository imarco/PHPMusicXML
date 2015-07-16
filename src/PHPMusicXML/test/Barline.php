<?php
require_once 'PHPUnit/Autoload.php';
require_once 'apitest.php';

class BarlineTest extends PHPMusicXMLTest
{
	
	protected function setUp(){
	}
	
 	/**
     * @dataProvider provider
     */
	public function testAction($action, $params, $expected){
	}

	public function provider(){
		return array(
			array(
				'options' => array(),
				'xml' => ''
			)
		);
	}

}
