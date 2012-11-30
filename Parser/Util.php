<?php

namespace Parser;

/**
 * @author Artur.Safin <treilor@gmail.com>
 * @date 30.11.12
 */
class Util
{
	public static function extractStartingWith($keyStart, $haystack)
	{
		if (!is_array($keyStart)) $keyStart = array($keyStart);

		$params = array();
		foreach ($haystack as $k => $v)
		{
			foreach ($keyStart as $key)
				if (substr($k, 0, strlen($key)) == $key)
					$params[$k] = $v;
		}

		return $params;
	}

	public static function getTrueIndex()
	{
		foreach (func_get_args() as $index => $v)
			if ($v)
				return $index;

		return false;
	}
}
