<?php
namespace Spronkware\BulletinLibs;

class Message
{
	protected $to;
	protected $from;
	protected $body;
	protected $id;
	protected $fragmentationLimit;
	
	public function __construct()
	{
		$this->id = uniqid(null, true);
	}
	
	public function id($id) {
		$this->id = $id;
		return $this;
	}
	
	public function to($to) {
		$this->to = $to;
		return $this;
	}
	
	public function from($from) {
		$this->from = $from;
		return $this;
	}
	
	public function body($body) {
		$this->body = $body;
		return $this;
	}
	
	public function fragmentationLimit($fl)
	{
		$this->fragmentationLimit = $fl;
		return $this;
	}
	
	public function __get($v)
	{
		if(isset($this->$v)) { return $this->$v; }
	}
}