<?php
namespace Parser\TokenMapper;
/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class NodeType
{
	const NOTE_WITH_DATE = 1;
	const NOTE_WITHOUT_DATE = 0;

	private $value;

	public function __construct($tokens)
	{
		unset($tokens['descr']);

		$this->value = count($tokens)? self::NOTE_WITH_DATE : self::NOTE_WITHOUT_DATE;
	}

	public function getValue()
	{
		return $this->value;
	}
}
