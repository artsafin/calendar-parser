<?php
namespace Parser;
use Parser\TokenMapper;

/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class TokenMapperFactory
{
	private $tokens;

	public function __construct($tokens)
	{
		$this->tokens = $tokens;
	}

	public function createPeriod()
	{
		return new TokenMapper\Period(Util::extractStartingWith('period_', $this->tokens));
	}

	public function createDateTime($relativeNow)
	{
		return new TokenMapper\Date($relativeNow, Util::extractStartingWith(array('date_', 'time_', 'rel_'), $this->tokens));
	}

	public function createDuration($relativeNow)
	{
		return new TokenMapper\Duration($relativeNow, Util::extractStartingWith(array('dur_', 'till_'), $this->tokens));
	}

	public function createMessage()
	{
		return new TokenMapper\Message($this->tokens['descr']);
	}

	public function createNodeType()
	{
		return new TokenMapper\NodeType($this->tokens);
	}
}
