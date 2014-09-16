<?php
namespace Application;

/**
 * Class Base
 * @package Application
 */
abstract class Base
{
	protected   $rawConfiguration   = array();
	private     $components         = array();

	public function getConfiguration()
	{
		return $this->rawConfiguration;
	}

	public function setRoutingPagesConfiguration(array $routingConfiguration)
	{
		$this->rawConfiguration['routing']['pages'] = $routingConfiguration;
	}

	function __construct(array $rawConfiguration = array())
	{
		$this->rawConfiguration = $rawConfiguration;
	}

	abstract function run();

	public function __get($componentName)
	{
		if(!isset($this->components[$componentName]))
		{
			$componentConfiguration             = $this->rawConfiguration['components'][$componentName];
			$this->components[$componentName]   = new $componentConfiguration['class']($this, $componentConfiguration);
		}

		return $this->components[$componentName];
	}
}