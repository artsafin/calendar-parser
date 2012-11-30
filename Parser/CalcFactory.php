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

	private function filterTokensBy($keyStart)
	{
		if (!is_array($keyStart)) $keyStart = array($keyStart);

		$params = array();
		foreach ($this->tokens as $k => $v)
		{
			foreach ($keyStart as $key)
				if (substr($k, 0, strlen($key)) == $key)
					$params[$k] = $v;
		}

		return $params;
	}

	public function createPeriod()
	{
		return new Calc\Period($this->filterTokensBy('period_'));
	}

	public function createDateTime($relativeNow)
	{
		return new Calc\Date($relativeNow, $this->filterTokensBy(array('date_', 'time_', 'rel_')));
	}

	public function createDuration($relativeNow)
	{
		return new Calc\Duration($relativeNow, $this->filterTokensBy(array('dur_', 'till_')));
	}

	public function createMessage()
	{
		return new Calc\Message($this->tokens['descr']);
	}
}
