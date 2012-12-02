<?php
namespace Parser;
use Parser\Calc;

/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class CalcFactory
{
	private $tokens;

	public function __construct($tokens)
	{
		$this->tokens = $tokens;
	}

	public function createPeriod()
	{
		return new Calc\Period(Util::extractStartingWith('period_', $this->tokens));
	}

	public function createDateTime($relativeNow)
	{
		return new Calc\Date($relativeNow, Util::extractStartingWith(array('date_', 'time_', 'rel_'), $this->tokens));
	}

	public function createDuration($relativeNow)
	{
		return new Calc\Duration($relativeNow, Util::extractStartingWith(array('dur_', 'till_'), $this->tokens));
	}

	public function createMessage()
	{
		return new Calc\Message($this->tokens['descr']);
	}
}
