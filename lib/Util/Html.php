<?php
namespace Util;
class Html
{
	public static function encode($string)
	{
		return htmlspecialchars($string);
	}
}
