<?php
class SendMessageTest extends PHPUnit_Framework_TestCase
{
	public $gw = $this->gw;
	
	function setUp()
	{
		$b = new BulletinConnectHttp(BULLETIN_USERID, BULLETIN_PASSWORD);
	}
	function test_badAuth()
	{
		
	}
}