<?php
namespace Spronkware\BulletinLibs\Response;

/**
 * Bulletin Queue Depth Response parser
 * 
 * @author Keith Humm <keith.humm@school-links.co.nz>
 * @copyright 2013 Solvam Corporation Limited
 */
class QueueDepthResponse extends BulletinResponse
{
	const IN_TAG = 'inCount';
	const STATUS_TAG = 'statusCount';
	
	protected $inboundCount = 0;
	protected $statusCount = 0;
	
	public function successCodes() { return array(200); }
	
	protected function _parse()
	{
		if(!$this->isSuccess()) {
			switch($this->code)
			{
				case BulletinResponse::UNAUTHORISED:
					$this->error = 'Unauthorised';
					break;
				case BulletinResponse::INTERNAL_ERROR:
					$this->error = 'Gateway Error';
					break;
			}
		} else {
			$string = $this->body;
			
			if(strpos($string, '&') === FALSE) {
				$this->error = 'Gateway returned invalid response ('.$string.')';
				return;
			}
			
			$encoded = explode('&', $string);
			if(count($encoded) !== 2) {
				$this->error = 'Gateway returned invalid response ('.$string.')';
				return;
			}
			
			$data = array();
			foreach($encoded as $e) {
				if(strpos($string, '=') === FALSE) {
					$this->error = 'Gateway returned invalid response ('.$string.')';
					return;
				}
				list($key, $val) = explode('=', $e);
				$data[$key] = (int) $val;
			}
			
			if(!array_key_exists(static::IN_TAG, $data) || !array_key_exists(static::STATUS_TAG, $data)) {
				$this->error = 'Gateway did not return necessary data ('.$string.')';
				return;
			}
			
			$this->inboundCount = $data[static::IN_TAG];
			$this->statusCount = $data[static::STATUS_TAG];
		}
	}
	
	/**
	 * @return integer Inbound message count part of response
	 */
	public function getInboundCount()
	{
		return $this->inboundCount;
	}
	
	/**
	 * @return integer Status count part of response
	 */
	public function getStatusCount()
	{
		return $this->statusCount;
	}
}