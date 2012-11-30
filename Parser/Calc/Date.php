<?php
namespace Parser\Calc;
use DateTime;
use Parser\Util;
use DateInterval;

/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class Date
{
	protected $params;
	protected $now;

	protected $value;

	public function __construct(DateTime $now, $params)
	{
		$this->params = $params;
		$this->now = $now;
	}

	public function getValue()
	{
		$dateTime = $this->getDateTime();

		return $dateTime->format('Y-m-d H:i:s');
	}

	private function getWeekDayIndex()
	{
	}

	/**
	 * @return DateTime
	 */
	public function getDateTime()
	{
		$weekDays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

		if (!$this->value)
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

			$realDt = clone $this->now;

			if (isset($rel_day_1f))
			{
				$realDt->add(new DateInterval('P1D'));
			}
			else if (isset($rel_day_1b))
			{
				$realDt->sub(new DateInterval('P1D'));
			}
			else if (isset($rel_day_2f))
			{
				$realDt->add(new DateInterval('P2D'));
			}
			else if (isset($rel_day_2b))
			{
				$realDt->sub(new DateInterval('P2D'));
			}
			else if (isset($rel_day_nf))
			{
				$rel_day_nf = intval($rel_day_nf);
				$realDt->add(new DateInterval("P{$rel_day_nf}D"));
			}
			else if (isset($rel_day_nb))
			{
				$rel_day_nb = intval($rel_day_nb);
				$realDt->sub(new DateInterval("P{$rel_day_nb}D"));
			}
			else if (isset($rel_week_1f))
			{
				$realDt->add(new DateInterval("P1W"));
			}
			else if (isset($rel_week_nf))
			{
				$rel_week_nf = intval($rel_week_nf);
				$realDt->add(new DateInterval("P{$rel_week_nf}W"));
			}
			else if (isset($rel_month_1f))
			{
				$realDt->add(new DateInterval("P1M"));
			}
			else if (isset($rel_month_nf))
			{
				$rel_month_nf = intval($rel_month_nf);
				$realDt->add(new DateInterval("P{$rel_month_nf}M"));
			}
			else if (false !==
				($zWeekIndex = Util::getTrueIndex(isset($rel_week_1f_is_mon), isset($rel_week_1f_is_tue), isset($rel_week_1f_is_wed), isset($rel_week_1f_is_thu), isset($rel_week_1f_is_fri), isset($rel_week_1f_is_sat), isset($rel_week_1f_is_sun)))
			)
			{
				$addDays = 7 - (intval($this->now->format('N')) - 1) + $zWeekIndex;
				$realDt->add(new DateInterval("P{$addDays}D"));
			}
			else if (isset($rel_week_1f_some)) // just monday
			{
				$addDays = 7 - (intval($this->now->format('N')) - 1);
				$realDt->add(new DateInterval("P{$addDays}D"));
			}
			else if (false !==
				($zWeekIndex = Util::getTrueIndex(isset($rel_week_2f_is_mon), isset($rel_week_2f_is_tue), isset($rel_week_2f_is_wed), isset($rel_week_2f_is_thu), isset($rel_week_2f_is_fri), isset($rel_week_2f_is_sat), isset($rel_week_2f_is_sun)))
			)
			{
				$addDays = 14 - (intval($this->now->format('N')) - 1) + $zWeekIndex;
				$realDt->add(new DateInterval("P{$addDays}D"));
			}
			else if (false !==
				($zWeekIndex = Util::getTrueIndex(isset($date_week_is_mon), isset($date_week_is_tue), isset($date_week_is_wed), isset($date_week_is_thu), isset($date_week_is_fri), isset($date_week_is_sat), isset($date_week_is_sun)))
			)
			{
				$realDt->modify($weekDays[$zWeekIndex]);
			}

			$this->value = $realDt;
		}

		return $this->value;
	}
}
