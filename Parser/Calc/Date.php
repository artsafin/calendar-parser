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
		if (!$this->value)
		{
			extract($this->params);

			/*
			 * date_day date_month_is_* date_year
			 * date_month date_day date_year
			 * date_month_is_* date_day
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

			$zMonthIndex = Util::getTrueIndex(
				isset($date_month_is_jan),
				isset($date_month_is_feb),
				isset($date_month_is_mar),
				isset($date_month_is_apr),
				isset($date_month_is_may),
				isset($date_month_is_jun),
				isset($date_month_is_jul),
				isset($date_month_is_aug),
				isset($date_month_is_sep),
				isset($date_month_is_oct),
				isset($date_month_is_nov),
				isset($date_month_is_dec)
			);

			/*
			 * DATES AND RELATIVES
			 */
			if (isset($date_day) && $zMonthIndex !== false && isset($date_year))
			{
				$realDt->setDate($date_year, $zMonthIndex + 1, $date_day);
			}
			else if (isset($date_month) && isset($date_day) && isset($date_year))
			{
				$realDt->setDate($date_year, $date_month, $date_day);
			}
			else if (isset($date_day) && $zMonthIndex !== false)
			{
				$realDt->setDate($realDt->format('Y'), $zMonthIndex + 1, $date_day);
			}
			else if (isset($rel_day_1f))
			{
				$realDt->modify('+1 day');
			}
			else if (isset($rel_day_1b))
			{
				$realDt->modify('-1 day');
			}
			else if (isset($rel_day_2f))
			{
				$realDt->modify('+2 days');
			}
			else if (isset($rel_day_2b))
			{
				$realDt->modify('-2 days');
			}
			else if (isset($rel_day_nf))
			{
				$realDt->modify(sprintf('+%d days', $rel_day_nf));
			}
			else if (isset($rel_day_nb))
			{
				$realDt->modify(sprintf('-%d days', $rel_day_nb));
			}
			else if (isset($rel_week_1f))
			{
				$realDt->modify('+1 week');
			}
			else if (isset($rel_week_nf))
			{
				$realDt->modify(sprintf('+%d weeks', $rel_week_nf));
			}
			else if (isset($rel_month_1f))
			{
				$realDt->modify('+1 month');
			}
			else if (isset($rel_month_nf))
			{
				$realDt->modify(sprintf('+%d months', $rel_month_nf));
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
				|| false !==
				($zWeekIndex = Util::getTrueIndex(isset($rel_week_near_is_mon), isset($rel_week_near_is_tue), isset($rel_week_near_is_wed), isset($rel_week_near_is_thu), isset($rel_week_near_is_fri), isset($rel_week_near_is_sat), isset($rel_week_near_is_sun)))
			)
			{
				$weekDays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
				$realDt->modify($weekDays[$zWeekIndex]);
			}

			/*
			 * TIME
			 */
			if (isset($time_hour) && isset($time_min))
			{
				$realDt->setTime($time_hour, $time_min);
				if ($realDt <= $this->now)
					$realDt->modify('+1 day');
			}
			else if (isset($time_hour) && !isset($time_min))
			{
				$realDt->setTime($time_hour, intval($realDt->format('i')));
				if ($realDt <= $this->now)
					$realDt->modify('+1 day');
			}
			else if (isset($time_hour_am))
			{
				$realDt = new DateTime("{$realDt->format('Y-m-d')} {$time_hour_am} am", $realDt->getTimezone());

				if ($realDt <= $this->now)
					$realDt->modify('+1 day');
			}
			else if (isset($time_hour_pm))
			{
				$realDt = new DateTime("{$realDt->format('Y-m-d')} {$time_hour_pm} pm", $realDt->getTimezone());

				if ($realDt <= $this->now)
					$realDt->modify('+1 day');
			}

			$this->value = $realDt;
		}

		return $this->value;
	}
}
