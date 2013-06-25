<?php
use Spronkware\BulletinLibs as btn;
use Spronkware\BulletinLibs\Exception as x;

class SendMessageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Spronkware\BulletinLibs\BulletinConnectHttp
	 */
	public $gw;
	
	function setUp()
	{
		$this->gw = new btn\BulletinConnectHttp(BULLETIN_USERID, BULLETIN_PASSWORD);
		$this->gw->setSendEndpoint('https://service.bulletinconnect.net/api/1/sms/test');
	}
	
	function test_sendWithBadNumber()
	{
		$id = microtime(true);
		$message = new btn\Message();
		$message->to(NUMBER_TOOSHORT)->body('Hello!')->id($id);
		try {
			$this->gw->send($message);
			// doesn't fail
		} catch(x\BulletinLibsException $e) {
			$data = $e->getMessage();
			$this->fail('BTN shouldnt have thrown an exception');
		}
	}
	
	function test_configureBadResponse()
	{
		$id = microtime(true);
		$message = new btn\Message();
		$message->body('Hello!')->id($id);
		try {
			$this->gw->send($message);
			$this->fail('Should have thrown 400 exception with missing parameter');
		} catch(x\BulletinLibsException $e) {
		}
	}
}