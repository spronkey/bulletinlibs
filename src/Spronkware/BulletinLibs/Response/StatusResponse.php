<?php
namespace Spronkware\BulletinLibs\Response;

/**
 * Status and Reply response parsers
 * 
 * Bulletin.net inbound statuses have the following fields:
 * - messageId		Unique ID for message
 * - from			Source MSISDN (sender number)
 * - to				Destination MSISDN (sent to number)
 * - statusCode		Status code - one of NUR, SNT, ERR, NRCV/NRC, RCV, EXP, INF
 * - inReplyToID	Correlation ID if message is a reply
 * - error			Descriptive text
 * 
 * @author Keith Humm <keith.humm@school-links.co.nz>
 * @copyright 2013 Solvam Corporation Limited
 */
class StatusResponse extends BulletinResponse
{
	public function successCodes() { return array(200, 204); }
	
	protected $bodyparams;
	
	protected function _parse()
	{
		if(!$this->isSuccess()) {
			switch($this->code)
			{
				case BulletinResponse::UNAUTHORISED:
					$this->error = 'Unauthorised';
					break;
				case BulletinResponse::INCORRECT_METHOD:
					$this->error = 'Incorrect HTTP Method';
					break;
				case BulletinResponse::INTERNAL_ERROR:
					$this->error = 'Gateway Error';
					break;
			}
		} else {
			$bodydata = array();
			$params = parse_str($this->body, $bodydata);
			$this->bodyparams = $bodydata;
		}
	}
	
	public function hasContent()
	{
		return ($this->code == 200);
	}
	
	public function getParam($param)
	{
		if(isset($this->bodyparams) && is_array($this->bodyparams) && array_key_exists($param, $this->bodyparams)) {
			return $this->bodyparams[$param];
		}
		return null;
	}
	
	/**
	 * Shortcut methods
	 */
	public function getInReplyToId()
	{
		return $this->getParam('inReplyToId');
	}
	
	public function getStatusCode()
	{
		return $this->getParam('statusCode');
	}
	
	public function getMessageBody()
	{
		return $this->getParam('error');
	}
	
	public function getFrom()
	{
		return $this->getParam('from');
	}
	
	public function getTo()
	{
		return $this->getParam('to');
	}
}