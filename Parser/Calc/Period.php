<?php
namespace Parser\Calc;

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
		$params = array_map('str_word_count', $params);
		switch (1)
		{
			case $params['period_is_y']:
				return 4;
			case $params['period_is_q']:
				return 3;
			case $params['period_is_w']:
				return 2;
			case $params['period_is_d']:
				return 1;
			default:
				return 0;
		}
	}

	public function getValue()
	{
		return $this->value;
	}
}
