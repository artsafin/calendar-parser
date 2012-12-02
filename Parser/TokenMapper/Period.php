<?php
namespace Parser\TokenMapper;
use Parser\Util;

/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class Period
{
	private $value;

	public function __construct($params)
	{
		$this->value = $this->calcValue($params);
	}

	private function calcValue($params)
	{
		$zPeriodIndex = Util::getTrueIndex(
			$params['period_is_d'],
			$params['period_is_w'],
			$params['period_is_q'],
			$params['period_is_y']
		);

		if ($zPeriodIndex === false)
			return 0;
		else
			return $zPeriodIndex + 1;
	}

	public function getValue()
	{
		return $this->value;
	}
}
