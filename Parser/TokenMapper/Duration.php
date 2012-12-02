<?php
namespace Parser\TokenMapper;

use DateTime;
use DateInterval;

/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class Duration
{
	protected $params;
	protected $now;

	public function __construct(DateTime $now, $params)
	{
		$this->params = $params;
		$this->now = $now;
	}

	public function getDefaultValue()
	{
		return 15;
	}

	public function getValue()
	{
		extract($this->params);

		/*
		 * till_hour_pm
		 * till_hour_am
		 * till_hour till_min
		 * dur_hour
		 * dur_min
		 */
		if (isset($till_hour_pm))
			$toDate = new DateTime("{$this->now->format('Y-m-d')} {$till_hour_pm} pm", $this->now->getTimezone());
		else if (isset($till_hour_am))
			$toDate = new DateTime("{$this->now->format('Y-m-d')} {$till_hour_am} am", $this->now->getTimezone());
		else if (isset($till_hour) && isset($till_min))
			$toDate = new DateTime("{$this->now->format('Y-m-d')} {$till_hour}:{$till_min}", $this->now->getTimezone());
		else if (isset($till_hour) && !isset($till_min))
			$toDate = new DateTime("{$this->now->format('Y-m-d')} {$till_hour}:00", $this->now->getTimezone());
		else if (isset($dur_hour) && isset($dur_min))
		{
			$toDate = clone $this->now;
			$toDate->add(new DateInterval("PT{$dur_hour}H{$dur_min}M"));
		}
		else if (isset($dur_hour) && !isset($dur_min))
		{
			$toDate = clone $this->now;
			$toDate->add(new DateInterval("PT{$dur_hour}H"));
		}
		else if (!isset($dur_hour) && isset($dur_min))
		{
			$toDate = clone $this->now;
			$toDate->add(new DateInterval("PT{$dur_min}M"));
		}
		else return $this->getDefaultValue();

		if ($toDate <= $this->now)
			$toDate->add(new DateInterval('P1D'));

		return ($toDate->format('U') - $this->now->format('U')) / 60;
	}
}
