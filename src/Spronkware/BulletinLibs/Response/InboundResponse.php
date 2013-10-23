<?php
namespace Spronkware\BulletinLibs\Response;

/**
 * Reply response
 * 
 * Replies have the additional parameters available vs delivery receipts:
 * - rateCode	If applicable, the rate code for shortcode
 * - body		Message body
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