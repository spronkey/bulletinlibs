<?php
use Spronkware\BulletinLibs\Response as r;
use Spronkware\BulletinLibs\Exception as e;

class QueueDepthResponseTest extends PHPUnit_Framework_TestCase
{
	function test_Response_Errors()
	{
		$rstr = 'response missing delimeter';
		foreach(array(401,500) as $code) {
			$r = r\QueueDepthResponse::fromHttp($code, null, null);
			$this->assertFalse($r->isSuccess());
			$this->assertNotEmpty($r->getError());
		}
	}
	
	function test_Response_Missing_Delimiter()
	{
		$rstr = 'missing delimeter';
		$r = r\QueueDepthResponse::fromHttp(200, null, $rstr);
		$this->assertFalse($r->isSuccess());
		$this->assertTrue($r->hasError());
		$this->assertNotEmpty($r->getError());
	}
	
	function test_Response_Missing_Param()
	{
		$rstr = 'inCount=4&missingparam=1';
		$r = r\QueueDepthResponse::fromHttp(200, null, $rstr);
		$this->assertFalse($r->isSuccess());
		$this->assertTrue($r->hasError());
		$this->assertNotEmpty($r->getError());
	}
	
	function test_GoodResponse()
	{
		$rstr = 'statusCount=3&inCount=5';
		$r = r\QueueDepthResponse::fromHttp(200, null, $rstr);
		$this->assertTrue($r->isSuccess());
	}
}