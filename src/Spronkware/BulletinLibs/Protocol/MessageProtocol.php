<?php
namespace Spronkware\BulletinLibs\Protocol;

class MessageProtocol
{
	public static function renderToProperties(\Spronkware\BulletinLibs\Message $message)
	{
		$data = array();
		$data['to'] = $message->to;
		$data['body'] = $message->body;
		$data['messageId'] = $message->id;
		if(!empty($message->from)) {
			$data['from'] = $message->from;
		}
		if(!empty($message->fragmentationLimit)) {
			$data['fragmentationLimit'] = $message->fragmentationLimit;
		}
		return $data;
	}
}