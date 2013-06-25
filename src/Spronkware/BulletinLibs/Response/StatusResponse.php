<?php
namespace Spronkware\BulletinLibs\Response;

/**
 * Status and Reply response parsers
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
}