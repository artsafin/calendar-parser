<?php
namespace Parser\Calc;
/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class Message
{
	private $msg;

	public function __construct($msg)
	{
		$this->msg = $msg;
	}

	public function getValue()
	{
		return $this->msg;
	}
}
