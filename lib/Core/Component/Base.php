<?php
namespace Core\Component;
use Application\Base as Application;
use Application\Web;

class Base
{
	protected   $application;

	/**
	 * @var array
	 */
	private $configuration;

	/**
	 * @param Application|Web $application
	 * @param array $componentConfiguration
	 */
	function __construct(Application $application, array $componentConfiguration = array())
	{
		$this->application      = $application;
		$this->configuration    = $componentConfiguration;
	}

	public function getApplication()
	{
		return $this->application;
	}
}