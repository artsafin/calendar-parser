<?php
namespace Parser\Calc;
use DateTime;

/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class Date
{
	protected $params;
	protected $now;

	public function __construct(DateTime $now, $params)
	{
		$this->params = $params;
		$this->now = $now;
	}

	public function getValue()
	{
		extract($this->params);

		/*
		 * date_day date_month_is_* date_year
		 * date_month date_day date_year
		 * date_month_is_* date_day
		 *
		 * rel_day_1f
		 * rel_day_today
		 * rel_day_1b
		 * rel_day_2f
		 * rel_day_2b
		 * rel_day_nf
		 * rel_week_1f
		 * rel_week_nf
		 * rel_month_1f
		 * rel_month_nf
		 * rel_week_1f_is_*
		 * rel_week_1f_some
		 * rel_week_2f_is_*
		 * date_week_is_*
		 * rel_week_near_is_*
		 *
		 * time_hour time_min
		 * time_hour_am
		 * time_hour_pm
		 * time_hour time_min?
		 */

	}

	public function getDateTime()
	{
		return new \DateTime('2012-11-30 04:00:00');
	}
}
