<?php
namespace Core\View;
class Base extends \Core\Component\Base
{
	private $routing;
	private $results;

	private $pageSettings = array(
		'title' => '',
	);
	public function render($layout, $routing, $results)
	{
		if(isset($routing['template']) && $routing['template'])
		{
			$layout = $routing['template'] . DIRECTORY_SEPARATOR . $layout;
		}
		$this->pageSettings['title'] = isset($routing['title']) ? $routing['title'] : '';

		$configuration  = $this->application->getConfiguration();
		$this->routing  = $routing;
		$this->results  = $results;
		ob_start();
		global $view;
		$view = $this;
		require_once $configuration['projectRoot'] . 'templates/layouts/' . $layout . '.php';
		echo ob_get_clean();
	}

	public function getPageTitle()
	{
		return $this->pageSettings['title'];
	}

	public function getCss()
	{
		return isset($this->routing['css']) ? $this->routing['css'] : array();
	}

	public function getJs()
	{
		return isset($this->routing['js']) ? $this->routing['js'] : array();
	}

	public function renderBlock($blockName)
	{
		if(isset($this->routing['blocks'][$blockName]))
		{
			foreach($this->routing['blocks'][$blockName] as $module)
			{
				$this->renderBlockModule($blockName, $module);
			}
		}
	}

	private function renderBlockModule($blockName, array $blockModule)
	{
		$configuration  = $this->application->getConfiguration();
		require_once  $configuration['projectRoot']    . 'templates/modules/' . $blockModule[0] . '.php';
		$method = $blockModule[0].ucfirst($blockModule[1]).ucfirst($blockModule[2]);
		$method($this->results[$blockName][$blockModule[0]][$blockModule[1]][$blockModule[2]]);
	}
}