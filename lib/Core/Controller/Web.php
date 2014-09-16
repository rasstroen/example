<?php
namespace Core\Controller;

use Core\Component\Base;

/**
 * Class Web
 * @package Core\Controller
 *
 * @property \Application\Web $application
 */
class Web extends Base
{
	private $layout;
	private $routing;
	private $moduleResults;

	public function run()
	{
		/**
		 * Определяем, нужно ли выполнять модуль записи // @todo
		 */
		if($writemodule = $this->application->request->getQueryParam('writemodule'))
		{
			list($class, $action, $method) = explode('_', $writemodule);
			$method             = 'action' . ucfirst($action) . ucfirst($method);
			$className          = '\Module\\' . ucfirst($class);
			$writemoduleObject  = new $className($this->application);
			/**
			 * @var \Module\Base $writemoduleObject
			 */
			$writemoduleObject->$method();
		}
		/**
		 * Определяем набор модулей для формирования ответа на запрос
		 */
		$this->routing      = $this->application->router->getRoutingByRequest();
		$this->layout       = $this->routing['layout'];
		/**
		 * Запускаем обработку модулей
		 */
		$this->moduleResults  = $this->processRead($this->routing);


		/**
		 * Рендерим
		 */
		return $this;
	}

	private function getConfiguration()
	{
		return $this->getConfiguration();
	}


	public function processWrite()
	{
		return $this;
	}

	public function processRead(array $configuration)
	{
		$moduleBlocks = isset($configuration['blocks']) ? $configuration['blocks'] :array();
		$moduleResults = array();
		foreach($moduleBlocks as $blockName => $blocks)
		{
			foreach($blocks as $module)
			{
				$moduleClass = '\Module\\'.ucfirst($module[0]);
				$moduleObject = new $moduleClass($this->application);
				$method = 'action'.ucfirst($module[1]).ucfirst($module[2]);

				$moduleResults[$blockName][$module[0]][$module[1]][$module[2]] = $moduleObject->$method();
			}
		}

		return $moduleResults;
	}

	public function flushResponse()
	{
		$this->application->view->render($this->layout, $this->routing, $this->moduleResults);
		return $this;
	}
}