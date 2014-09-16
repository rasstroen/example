<?php
namespace Core\BLL;
/**
 * Class Base
 * @package Core\BLL
 *
 * @property Sitemap $sitemap
 */
class Base extends \Core\Component\Base
{
	private $components;

	public function __get($componentName)
	{
		$configuration = $this->application->getConfiguration();
		if(!isset($this->components[$componentName]))
		{
			$componentConfiguration             = $configuration['bll_components'][$componentName];
			$this->components[$componentName]   = new $componentConfiguration['class']($this->application, $componentConfiguration);
		}

		return $this->components[$componentName];
	}
}