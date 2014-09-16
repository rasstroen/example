<?php

class AutoLoader
{
	public static function init($projectRoot, array $includePathes)
	{
		foreach($includePathes as $relativePath)
		{
			$absolutePathes[] = $projectRoot . $relativePath;
		}
		set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $absolutePathes));
	}
}

function autoload($className)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	if ($lastNsPos = strripos($className, '\\'))
	{
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	require $fileName;
}

spl_autoload_register('autoload');