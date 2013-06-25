<?php
use Spronkware\BulletinLibs as btn;
use Spronkware\BulletinLibs\Exception as x;
use Spronkware\BulletinLibs\Response as r;

class InboundPollTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Spronkware\BulletinLibs\BulletinConnectHttp
	 */
	public $gw;
	
	function setUp()
	{
		$this->gw = new btn\BulletinConnectHttp(BULLETIN_USERID, BULLETIN_PASSWORD);
	}
	
	function test_getInboundMessage()
	{
		try {
			$replyCount = $this->gw->getQueuedInboundCount();
			$replystatus = $this->gw->pollForInbound();
			if($replyCount == 0) {
				$this->assertTrue($replystatus instanceof r\InboundResponse);
				$this->assertFalse($replystatus->hasContent());
			} else {
				$this->assertTrue($replystatus instanceof r\InboundResponse);
				$this->assertTrue($replystatus->hasContent());
				$this->assertNotEmpty($replystatus->getParam('from'));
			}
		} catch(x\BulletinLibsException $e) {
			$this->fail();
		}
	}
}