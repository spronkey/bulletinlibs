<?php
namespace Spronkware\BulletinLibs\Response;

abstract class BulletinResponse
{
	const BAD_REQUEST = 400;
	const UNAUTHORISED = 401;
	const FORBIDDEN = 403;
	const INCORRECT_METHOD = 405;
	const INTERNAL_ERROR = 500;
	
	public $code, $headers, $body, $error;
	
	/**
	 * Parses the response based on code, body, and headers
	 */
	protected abstract function _parse();
	
	/**
	 * @return boolean True if the response has any associated errors
	 */
	public function hasError()
	{
		return (isset($this->error) && !empty($this->error));
	}
	
	/**
	 * @return string Error string if exists
	 */
	public function getError()
	{
		return $this->error;
	}
	
	/**
	 * @return boolean True if request successful, false if failure
	 */
	public function isSuccess()
	{
		return (in_array($this->code, $this->successCodes()) && !$this->hasError());
	}
	
	/**
	 * Override function to return the success code for the overridden response type
	 * @return int[] HTTP codes indicating success
	 */
	public function successCodes()
	{
		return array(200);
	}
	
	/**
	 * Create a new response from HTTP data
	 * 
	 * @param int $code HTTP response code
	 * @param array $headers Headers key=>val array
	 * @param string $body Body string
	 */
	public static function fromHttp($code, $headers, $body)
	{
		$t = new static();
		$t->code = $code;
		$t->headers = $headers;
		$t->body = $body;
		$t->_parse();
		return $t;
	}
}