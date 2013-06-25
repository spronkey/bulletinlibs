<?php
use Spronkware\BulletinLibs as btn;
use Spronkware\BulletinLibs\Exception as x;

class GatewayConnectTest extends PHPUnit_Framework_TestCase
{
	function test_badAuth()
	{
		$gw = new btn\BulletinConnectHttp('DUMMY_DOES_NOT_WORK', 'DUMMY_DOES_NOT_WORK');
		try {
			$gw->getQueuedStatusCount();
			$this->fail('Should throw Auth Exception when incorrect authentication provided');
		} catch(x\BulletinLibsException $e) {
		}
	}
	
	function test_goodAuth()
	{
		$gw = new btn\BulletinConnectHttp(BULLETIN_USERID, BULLETIN_PASSWORD);
		try {
			$gw->getQueuedStatusCount();
		} catch(x\BulletinLibsException $e) {
			$this->fail('Should not throw exception with correct authentication');
		}
	}
}