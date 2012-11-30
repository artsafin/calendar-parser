<?php
namespace Parser;
/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class Tokenizer
{
	public function tokenize($string)
	{
		return $this->normalizeMatches($this->extractTokens($string));
	}

	private function normalizeMatches($matches)
	{
		// Do a lot of black magic
		$matches = array_reduce(array_map(function ($i)
		{
			return array_diff_key(array_filter($i, 'strlen'), array_flip(array_filter(array_keys($i), 'is_numeric')));
		}, $matches), 'array_merge', array());

		$matches = array_reduce(array_keys($matches), function (&$acc, $item) use ($matches)
		{
			$token = strstr($item, '__', true);
			if ($token === false)
				$token = $item;
			$acc[$token] = $matches[$item];
			return $acc;
		}, array());

		return $matches;
	}

	private function extractTokens($string)
	{
		$re_space = "\s+";

		$re = array();

		$re[] = "(?<period_is_w>каждую{$re_space}неделю)|(?:каждый{$re_space}(?:(?<period_is_q>квартал)|(?<period_is_d>день)|(?<period_is_m>месяц)|(?<period_is_y>год)))?";

		$re[] = "(?:\s*
(?:
	(?<date_day__0>[0123]?\d)
	{$re_space}
	(?:
		(?<date_month_is_jan__0>января)|(?<date_month_is_feb__0>февраля)|(?<date_month_is_mar__0>марта)|(?<date_month_is_apr__0>апреля)|(?<date_month_is_may__0>мая)|(?<date_month_is_jun__0>июня)|(?<date_month_is_jul__0>июля)|(?<date_month_is_aug__0>августа)|(?<date_month_is_sep__0>сентября)|(?<date_month_is_oct__0>октября)|(?<date_month_is_nov__0>ноября)|(?<date_month_is_dec__0>декабря)
	)
	(?:{$re_space}(?<date_year__0>2\d{3}))?
)
|(?:
	(?<date_day__1>[0123]?\d)[.\\/](?<date_month__1>[01]\d)[.\\/](?<date_year__1>2\d{3})
)
|(?:
	(?<date_month__2>[01]\d)[.\\/](?<date_day__2>[0123]?\d)[.\\/](?<date_year__2>2\d{3})
)
|(?:
	(?<date_year__3>2\d{3})-(?<date_month__3>[01]\d)-(?<date_day__3>[0123]?\d)
)
|(?:
	(?:
		(?<date_month_is_jan__4>январь)|(?<date_month_is_feb__4>февраль)|(?<date_month_is_mar__4>март)|(?<date_month_is_apr__4>апрель)|(?<date_month_is_may__4>май)|(?<date_month_is_jun__4>июнь)|(?<date_month_is_jul__4>июль)|(?<date_month_is_aug__4>август)|(?<date_month_is_sep__4>сентябрь)|(?<date_month_is_oct__4>октябрь)|(?<date_month_is_nov__4>ноябрь)|(?<date_month_is_dec__4>декабрь)
	),{$re_space}(?<date_day__4>[0123]?\d)
)|(?<rel_day_1f>Завтра)
|(?<rel_day_today>Сегодня)
|(?<rel_day_1b>Вчера)
|(?<rel_day_2f>Послезавтра|Через{$re_space}день)
|(?<rel_day_2b>Позавчера)
|(?:
	Через{$re_space}(?<rel_day_nf>[1-9]\d*){$re_space}(?:день|дня|дней)
)
|(?<rel_week_1f>Через{$re_space}неделю)
|(?:
	Через{$re_space}(?<rel_week_nf>[1-9]\d*){$re_space}(?:неделю|недели|недель)
)
|(?<rel_month_1f>Через{$re_space}месяц)
|(?:
	Через{$re_space}(?<rel_month_nf>[1-9]\d*){$re_space}(?:месяц|месяца|месяцев)
)
|(?:
	На{$re_space}следующей{$re_space}неделе{$re_space}в{$re_space}(?:(?<rel_week_1f_is_mon>понедельник)|(?<rel_week_1f_is_tue>вторник)|(?<rel_week_1f_is_wed>среду)|(?<rel_week_1f_is_thu>четверг)|(?<rel_week_1f_is_fri>пятницу)|(?<rel_week_1f_is_sat>субботу)|(?<rel_week_1f_is_sun>воскресенье))
)
|(?<rel_week_1f_some>На{$re_space}следующей{$re_space}неделе)
|(?:
	В{$re_space}(?:(?<rel_week_2f_is_mon>понедельник)|(?<rel_week_2f_is_tue>вторник)|(?<rel_week_2f_is_wed>среду)|(?<rel_week_2f_is_thu>четверг)|(?<rel_week_2f_is_fri>пятницу)|(?<rel_week_2f_is_sat>субботу)|(?<rel_week_2f_is_sun>воскресенье)){$re_space}через{$re_space}неделю
)
|(?:
	(?:В{$re_space})?(?:(?<date_week_is_mon>понедельник|пн)|(?<date_week_is_tue>вторник|вт)|(?<date_week_is_wed>среду|ср)|(?<date_week_is_thu>четверг|чт)|(?<date_week_is_fri>пятницу|пт)|(?<date_week_is_sat>субботу|сб)|(?<date_week_is_sun>воскресенье|вс))
)|(?:
	В{$re_space}(?:следующий|следующую|следующее) (?:(?<rel_week_near_is_mon>понедельник)|(?<rel_week_near_is_tue>вторник)|(?<rel_week_near_is_wed>среду)|(?<rel_week_near_is_thu>четверг)|(?<rel_week_near_is_fri>пятницу)|(?<rel_week_near_is_sat>субботу)|(?<rel_week_near_is_sun>воскресенье))
)
)?";

		$re[] = "(?:\s*
(?:
	В{$re_space}(?<time_hour__0>\d\d?):(?<time_min__0>\d\d?)
)
|(?:
	В{$re_space}(?<time_hour_am__1>\d\d?){$re_space}утра
)
|(?:
	В{$re_space}(?<time_hour_pm__2>\d\d?){$re_space}вечера
)
|(?:
	Утром{$re_space}в{$re_space}(?<time_hour_am__3>\d\d?)
)
|(?:
	Вечером{$re_space}в{$re_space}(?<time_hour_pm__4>\d\d?)
)
|(?:
	В{$re_space}(?<time_hour__5>[012]\d{0,1})(?:{$re_space}(?:часов|часа|час))?
	(?:{$re_space}(?<time_min__5>\d\d{0,1})(?:{$re_space}(?:минуту|минуты|минут))?)?
)
)?";

		$re[] = "(?:\s*
(?:
	На{$re_space}(?<dur_hour__0>\d\d?)(?:{$re_space})?(?:часов|часа|час|ч.?)
	(?:{$re_space}(?<dur_min__0>\d\d?)(?:{$re_space}(?:минуту|минуты|минут|мин.?))?)?
)
|(?:
	На{$re_space}
	(?:(?<dur_hour__1>\d\d?)(?:{$re_space})?(?:часов|часа|час|ч.?){$re_space})?
	(?<dur_min__1>\d\d?)(?:{$re_space}(?:минуту|минуты|минут|мин.?))?
)
|(?:
	До{$re_space}(?<till_hour_pm>\d\d?){$re_space}вечера
)
|(?:
	До{$re_space}(?<till_hour_am>\d\d?){$re_space}утра
)
|(?:
	До{$re_space}(?<till_hour__0>\d\d?)(?<till_min__0>[: ]\d\d?)?
)
)?";

		$re = array_map(function ($i)
		{
			return str_replace(array("\n", "\t", "\r"), array('', '', ''), $i);
		}, $re);
		$regexp = sprintf('/%s/iu', implode('', $re));
//		log('RE: ', $regexp);

		preg_match_all($regexp, $string, $matches, PREG_SET_ORDER);

		$matches[] = array('descr' => trim(preg_replace($regexp, '', $string)));

//		log('Raw matches: ', $matches);

		return $matches;
	}
}
