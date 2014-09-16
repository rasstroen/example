<?php
namespace Application;
use Core\Request\Http;

/**
 * Class Web
 * @package Application
 *
 * @property \Core\Router\Base      $router
 * @property Http                   $request
 * @property Web                    $application
 * @property \Core\Controller\Web   $controller
 * @property \Core\View\Base        $view
 * @property \Core\BLL\Base         $bll
 * @property \Core\Database\Base    $db
 *
 *
 */
class Web extends Base
{

	public function getRootPath()
	{
		$configuration = $this->getConfiguration();
		return $configuration['projectRoot'];
	}
	public function run()
	{
		/**
		 * Обрабатываем запрос
		 */
		$this->request->processRequest();
		/**
		 * Создаем контроллер для ответа на запрос
		 */
		$this->controller->run($this->request);

		$this->controller->flushResponse();
	}
}