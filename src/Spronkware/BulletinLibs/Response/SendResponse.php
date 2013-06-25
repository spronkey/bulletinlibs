<?php
namespace Spronkware\BulletinLibs\Response;

/**
 * Bulletin Queue Depth Response parser
 * 
 * @author Keith Humm <keith.humm@school-links.co.nz>
 * @copyright 2013 Solvam Corporation Limited
 */
class SendResponse extends BulletinResponse
{
	public function successCodes() { return array(204); }
	
	protected function _parse()
	{
		if(!$this->isSuccess()) {
			switch($this->code)
			{
				case BulletinResponse::BAD_REQUEST:
					$es = false;
					if(is_array($this->headers) && count($this->headers) > 0) {
						// look for bulletin.net's error headers
						if(array_key_exists('Status-Line', $this->headers)) {
							$this->error = $this->headers['Status-Line'];
							$es = true;
						}
					}
					if(!$es) {
						// attempt to parse error from HTML, as bulletin doesn't seem to
						// return Status-Line anymore
						$h1s = array();
						if(preg_match('/.*<h1>(.*?)<\/h1>/si', $this->body, $h1s) == 2) {
							$this->error = array_pop($h1s);
						}
					}
					break;
				case BulletinResponse::UNAUTHORISED:
					$this->error = 'Unauthorised';
					break;
				case BulletinResponse::FORBIDDEN:
					$this->error = 'Forbidden';
					break;
				case BulletinResponse::INTERNAL_ERROR:
					$this->error = 'Gateway Error';
					break;
			}
		}
	}
}