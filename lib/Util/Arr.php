<?php
namespace Util;
class Arr
{
	public static function mergeArray($a,$b)
	{
		return self::_mergeArray(func_get_args(), false);
	}

	public static function mergeArrayIntegerSafe($a, $b)
	{
		return self::_mergeArray(func_get_args(), true);
	}

	private static function _mergeArray(array $arrays, $isIntegerSafe)
	{
		$res=array_shift($arrays);
		while(!empty($arrays))
		{
			$next=array_shift($arrays);
			foreach($next as $k => $v)
			{
				if(is_integer($k) && !$isIntegerSafe)
					isset($res[$k]) ? $res[]=$v : $res[$k]=$v;
				else if(is_array($v) && isset($res[$k]) && is_array($res[$k]))
					$res[$k]=self::mergeArray($res[$k],$v);
				else
					$res[$k]=$v;
			}
		}
		return $res;
	}
}
