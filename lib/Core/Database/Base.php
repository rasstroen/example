<?php
namespace Core\Database;
/**
 * Class Base
 * @package Core\Database
 *
 * @property  PDODatabase $web
 */
class Base extends \Core\Component\Base
{
	private $connections;

	public function __get($baseName)
	{
		return isset($this->connections[$baseName]) ? $this->connections[$baseName] : $this->connect($baseName);
	}

	private function connect($baseName)
	{
		$configuration  = $this->application->getConfiguration();
		$dbConfig       = $configuration['database'][$baseName];
		return $this->connections[$baseName] = new PDODatabase(new \PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password']));
	}
}