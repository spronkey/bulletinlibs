<?php
namespace Spronkware\BulletinLibs;

class BulletinConnectHttp
{
	const ENDPOINT_SEND = 'https://service.bulletinconnect.net/api/1/sms/out';
	const ENDPOINT_INBOUND = 'https://service.bulletinconnect.net/api/1/sms/in';
	const ENDPOINT_STATUS = 'https://service.bulletinconnect.net/api/1/sms/status';
	const ENDPOINT_QUEUE = 'https://service.bulletinconnect.net/api/1/sms/queue';
	
	protected $userid;
	protected $password;
	
	protected $endpoint_send;
	protected $endpoint_inbound;
	protected $endpoint_status;
	protected $endpoint_queue;
	
	protected $verify_certs;
	
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
		$this->endpoint_inbound = static::ENDPOINT_INBOUND;
		$this->endpoint_status = static::ENDPOINT_STATUS;
		$this->endpoint_queue = static::ENDPOINT_QUEUE;
		$this->verify_certs = false;
	}

	/**
	 * Creates a post params array from the Message, along with the user account data 
	 * 
	 * @param array $postdata
	 */
	protected function _addCredentialsToPostdata(array $postdata)
	{
		$postdata['userId'] = $this->userid;
		$postdata['password'] = $this->password;
		
		return $postdata;
	}
	
	protected function _addCredentialsToURL($url)
	{
		if(strpos($url, '?') !== false) {
			$url .= '&';
		} else {
			$url .= '?';
		}
		$url .= 'userId='.urlencode($this->userid).'&password='.urlencode($this->password);
		return $url;
	}
	
	/**
	 * Returns whether or not the endpoint specified is an HTTPS endpoint
	 * @param boolean $endpoint True if endpoint should use SSL/TLS
	 */
	protected function isSSL($endpoint)
	{
		return (strpos(strtolower($endpoint), 'https') === 0);
	}
	
	/**
	 * Send the specified Message via the gateway
	 * 
	 * @param Message $message
	 */
	public function send(Message $message)
	{
		// render message to postdata array and add user credentials
		$postdata = \Spronkware\BulletinLibs\Protocol\MessageProtocol::renderToProperties($message);
		$postdata = $this->_addCredentialsToPostdata($postdata);
		
		// configure http request
		$request = \Httpful\Request::post(
			$this->sendEndpoint(),
			$postdata,
			\Httpful\Mime::FORM
		);
		if($this->isSSL($this->sendEndpoint()) && $this->verifyCerts()) {
			$request->strictSSL(true);
		}
		
		// send request
		$response = $request->autoParse(false)->send();
		$sr = Response\SendResponse::fromHttp($response->code, $response->headers, $response->body);
		if($sr->isSuccess()) {
			return $sr;
		} else {
			throw new \Spronkware\BulletinLibs\Exception\BulletinLibsException($sr->getError());
		}
	}
	
	/**
	 * Polls bulletin.net for the account's queue depth information
	 * @return Spronkware\BulletinLibs\Response\QueueDepthResponse
	 */
	public function getQueueDepthData()
	{
		$uri = $this->_addCredentialsToURL($this->queueEndpoint());
		$request = \Httpful\Request::get($uri);
		
		if($this->isSSL($this->queueEndpoint()) && $this->verifyCerts()) {
			$request->strictSSL(true);
		}
		
		$response = $request->autoParse(false)->send();
		$qdr = Response\QueueDepthResponse::fromHttp($response->code, $response->headers, $response->body);
		if($qdr->isSuccess()) {
			return $qdr;
		} else {
			throw new \Spronkware\BulletinLibs\Exception\BulletinLibsException($qdr->getError());
		}
	}
	
	/**
	 * Polls bulletin.net for queue depth and returns the number of queued replies (or inbound messages)
	 * @return int
	 */
	public function getQueuedInboundCount()
	{
		$qd = $this->getQueueDepthData();
		return $qd->getInboundCount();
	}
	
	/**
	 * Polls bulletin.net for the queue depth and returns the number of queued status reports
	 * @return int
	 */
	public function getQueuedStatusCount()
	{
		$qd = $this->getQueueDepthData();
		return $qd->getStatusCount();
	}
	
	/**
	 * Polls the server for replies
	 * @return \Spronkware\BulletinLibs\Response\InboundResponse
	 */
	public function pollForInbound()
	{
		$url = $this->_addCredentialsToURL($this->inboundEndpoint());
		$request = \Httpful\Request::get($url);
		if($this->isSSL($this->queueEndpoint()) && $this->verifyCerts()) {
			$request->strictSSL(true);
		}
		
		$response = $request->autoParse(false)->send();
		return Response\InboundResponse::fromHttp($response->code, $response->headers, $response->body);
	}
	
	/**
	 * Polls the server for status updates
	 * @return \Spronkware\BulletinLibs\Response\StatusResponse
	 */
	public function pollForStatusUpdates()
	{
		$url = $this->_addCredentialsToURL($this->statusEndpoint());
		$request = \Httpful\Request::get($url);
		if($this->isSSL($this->queueEndpoint()) && $this->verifyCerts()) {
			$request->strictSSL(true);
		}
		
		$response = $request->autoParse(false)->send();
		return Response\StatusResponse::fromHttp($response->code, $response->headers, $response->body);
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
	
	public function inboundEndpoint() {
		return $this->endpoint_inbound;
	}
	
	/**
	 * Sets the INBOUND endpoint URL to something other than default
	 */
	public function setInboundEndpoint($url) {
		$this->endpoint_inbound = $url;
	}
	
	public function statusEndpoint() {
		return $this->endpoint_status;
	}
	
	/**
	 * Sets the STATUS endpoint URL to something other than default
	 */
	public function setStatusEndpoint($url) {
		$this->endpoint_status = $url;
	}
	
	public function queueEndpoint() {
		return $this->endpoint_queue;
	}
	
	/**
	 * Sets the QUEUE endpoint URL to something other than default
	 */
	public function setQueueEndpoint($url) {
		$this->endpoint_queue = $url;
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