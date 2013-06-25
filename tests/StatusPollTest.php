<?php
use Spronkware\BulletinLibs as btn;
use Spronkware\BulletinLibs\Exception as x;
use Spronkware\BulletinLibs\Response as r;

class StatusPollTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var \Spronkware\BulletinLibs\BulletinConnectHttp
	 */
	public $gw;
	
	function setUp()
	{
		$this->gw = new btn\BulletinConnectHttp(BULLETIN_USERID, BULLETIN_PASSWORD);
	}
	
	function test_PollForStatus()
	{
		try {
			$statusCount = $this->gw->getQueuedStatusCount();
			$status = $this->gw->pollForStatusUpdates();
			if($statusCount == 0) {
				$this->assertTrue($status instanceof r\StatusResponse);
				$this->assertFalse($status->hasContent());
			} else {
				$this->assertTrue($status instanceof r\StatusResponse);
				$this->assertTrue($status->hasContent());
				$this->assertNotEmpty($status->getParam('statusCode'));
				$this->assertTrue(
					in_array(strtolower($status->getParam('statusCode')),
						array('nur', 'snt', 'err', 'nrc', 'nrcv', 'rcv', 'exp', 'inf')
					)
				);
			}
		} catch(x\BulletinLibsException $e) {
			$this->fail();
		}
	}
}