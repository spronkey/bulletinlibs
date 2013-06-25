<?php
use Spronkware\BulletinLibs as btn;
use Spronkware\BulletinLibs\Exception as x;

class QueuePollTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Spronkware\BulletinLibs\BulletinConnectHttp
	 */
	public $gw;
	
	function setUp()
	{
		$this->gw = new btn\BulletinConnectHttp(BULLETIN_USERID, BULLETIN_PASSWORD);
	}
	
	function test_getNumInbound()
	{
		try {
			$replyCount = $this->gw->getQueuedInboundCount();
			$this->assertTrue(is_int($replyCount));
		} catch(x\BulletinLibsException $e) {
			$this->fail();
		}
	}
	
	function test_getNumStatuses()
	{
		try {
			$statusCount = $this->gw->getQueuedStatusCount();
			$this->assertTrue(is_int($statusCount));
		} catch(x\BulletinLibsException $e) {
			$this->fail();
		}
	}
}