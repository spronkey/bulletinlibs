<?php
namespace Spronkware\BulletinLibs;

class BulletinConnectHttp
{
	const ENDPOINT_SEND = 'https://service.bulletinconnect.net/api/1/sms/out';
	const ENDPOINT_REPLY = 'https://service.bulletinconnect.net/api/1/sms/in';
	const ENDPOINT_STATUS = 'https://service.bulletinconnect.net/api/1/sms/status';
	
	protected $userid;
	protected $password;
	
	protected $endpoint_send;
	protected $endpoint_reply;
	protected $endpoint_status;
	
	protected $verify_certs;
	
	protected $last_code = null;
	
	/**
	 * Construct the API endpoint object
	 * 
	 * @param unknown_type $userid
	 * @param unknown_type $password
	 */
	public function __construct($userid, $password)
	{
		$this->userid = $userid;
		$this->password = $password;
		$this->endpoint_send = static::ENDPOINT_SEND;
		$this->endpoint_reply = static::ENDPOINT_REPLY;
		$this->endpoint_status = static::ENDPOINT_STATUS;
		$this->verify_certs = false;
	}

	/**
	 * Creates a post params array from the Message, along with the user account data 
	 * 
	 * @param array $postdata
	 */
	protected function _addCredentialsToPostdata(array $postdata)
	{
		$postdata['userId'] = $this->userId;
		$postdata['password'] = $this->password;
		
		return $postdata;
	}
	
	/**
	 * Send the specified Message via the gateway
	 * 
	 * @param Message $message
	 */
	public function send(Message $message)
	{
		$postdata = $this->_addCredentialsToPostdata($message);
		
		// perform curl request
		$ch = curl_init();
		
		$options = array(
				CURLOPT_URL				=>	$this->getURL(),
				CURLOPT_RETURNTRANSFER	=>	1,
				CURLOPT_CONNECTTIMEOUT	=>	10,
				CURLOPT_TIMEOUT			=>	10,
				CURLOPT_POST			=>	1,
				CURLOPT_POSTFIELDS		=>	$postdata,
		);
		
		// If we are using an HTTPS url, and we are set to verify certificates,
		// enable verify host and peer settings
		if(strpos(strtolower($this->auth_url), 'https') === 0 && $this->verifyCerts()) {
			$options[CURLOPT_SSL_VERIFYHOST] = true;
			$options[CURLOPT_SSL_VERIFYPEER] = true;
		}
		
		// Setup CURL and exec request
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		
		// Test response
		if($response !== false) {
			$code = curl_getinfo($c, CURLINFO_HTTP_CODE);
			$this->last_code = $code;
			if($code == '200' || $code == '204') {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Polls the server for replies
	 */
	public function pollForReplies()
	{
		$ch = curl_init();
	}
	
	/**
	 * Polls the server for status updates
	 */
	public function pollForStatusUpdates()
	{
		
	}
	
	public function sendEndpoint() {
		return $this->endpoint_send;
	}
	
	/**
	 * Sets the SEND endpoint URL to something other than default
	 */
	public function setSendEndpoint($url) {
		$this->endpoint_send = $url;
	}
	
	public function replyEndpoint() {
		return $this->endpoint_reply;
	}
	
	/**
	 * Sets the REPLY endpoint URL to something other than default
	 */
	public function setReplyEndpoint($url) {
		$this->endpoint_reply = $url;
	}
	
	public function statusEndpoint() {
		return $this->endpoint_status;
	}
	
	/**
	 * Sets the STATUS endpoint URL to something other than default
	 */
	public function setstatusEndpoint($url) {
		$this->endpoint_status = $url;
	}
	
	public function verifyCerts()
	{
		return $this->verify_certs;
	}
	
	/**
	 * Set whether or not 
	 * @param unknown_type $verify
	 */
	public function setVerifyCerts($verify = true)
	{
		$this->verify_certs = $verify;
	}
}