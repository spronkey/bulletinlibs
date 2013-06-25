<?php
namespace Spronkware\BulletinLibs\Response;

/**
 * Reply response
 * 
 * @author Keith Humm <keith.humm@school-links.co.nz>
 * @copyright 2013 Solvam Corporation Limited
 */
class InboundResponse extends StatusResponse
{
	public function getStatusCode()
	{
		return 'RPL';
	}
	
	public function getMessageBody()
	{
		return $this->getParam('body');
	}
}