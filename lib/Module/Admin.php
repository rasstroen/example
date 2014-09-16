<?php
namespace Module;
class Admin extends \Module\Base
{
	public function actionShowHeader()
	{
		/**
		 * Шапка админки
		 */
	}

	public function actionSitemapClick()
	{
		$nodeId     = $this->application->request->getQueryParam('id');
		$parentId   = $this->application->request->getQueryParam('parentId');
		$this->responseJson(
			array(
				'locationUrl'   => '?sitemapId=' . $nodeId .'&' . 'parentId=' . $parentId
			)
		);
	}

	public function actionShowTheme()
	{

	}

	public function actionSitemapSaveItem()
	{
		$id = $this->application->request->getQueryParam('id');
		$parentId = $this->application->request->getQueryParam('parentId');
		$layout = $this->application->request->getQueryParam('layout');
		$url = $this->application->request->getQueryParam('url');
		$template = $this->application->request->getQueryParam('template');
		$title = $this->application->request->getQueryParam('title');
		$this->application->bll->sitemap->update(
			$id,
			array(
				'parentId'  => $parentId,
				'layout'  => $layout,
				'url'  => $url,
				'title'  => $title,
				'template'  => $template
			)
		);
		$this->application->request->redirect('/admin/map/?sitemapId=' . $id . '&parentId=' . $parentId);
	}

	public function actionSitemapAddItem()
	{
		$parentId = $this->application->request->getQueryParam('parentId');
		$layout = $this->application->request->getQueryParam('template');
		$url = $this->application->request->getQueryParam('url');
		$title = $this->application->request->getQueryParam('title');
		$sitemapId  = $this->application->bll->sitemap->add(
			$parentId,
			$layout,
			$url,
			$title
		);
		$this->application->request->redirect('/admin/map/?sitemapId=' . $sitemapId . '&parentId=' . $parentId);

	}

	public function actionShowSitemapItem()
	{
		$data                   = array();
		$data['sitemapItem']    = array();
		$itemId         = $this->application->request->getQueryParam('sitemapId', 0);
		$parentId       = $this->application->request->getQueryParam('parentId', 0);
		if($itemId)
		{
			if($itemId == 'n')
			{
				/**
				 * Добавление подраздела
				 */
				$data['new'] = true;
			}
			else
			{
				$data['sitemapItem']  = $this->application->bll->sitemap->getById($itemId);
			}
		}

		$configuration = $this->application->getConfiguration();
		$data['availableModules']   = $configuration['admin']['availableModules'];
		$data['availableTemplates'] = $configuration['admin']['availableTemplates'];
		$sitemap    = $this->application->bll->sitemap->getAll();
		$breads = $this->getBreadCumbs($sitemap, $parentId);
		$data['parentUrl'] = array();
		foreach($breads as $breadItem)
		{
			if($breadItem['name'])
			{
				$data['parentUrl'][] = $breadItem['name'];
			}
		}
		$data['parentUrl']  = $this->application->router->getAbsoluteRoot() . (!empty($data['parentUrl']) ? implode('/', $data['parentUrl']) . '/' : '') ;
		$data['parentId']   = $parentId;

		return $data;
	}

	private function getBreadCumbs($sitemap, $parentId)
	{
		$breads = array();
		while($parentId && isset($sitemap[$parentId]))
		{
			$breads[] = $sitemap[$parentId];
			$parentId = $sitemap[$parentId]['parent_id'];
		}
		return array_reverse($breads);
	}

	public function actionShowMenu()
	{
		/**
		 * Меню в административной части
		 */
		$routing = $this->application->router;

		return array(
			'map' => array
			(
				'Администрирование' => array(
					'path' => $routing->getAdminPath(),
					'childs' => array
					(
						'Карта сайта' => array(
							'path' => $routing->getAdminMapPath(),
						),
						'Тема'          =>array(
							'path' => $routing->getAdminThemePath(),
						),
					),
				),

			),
		);
	}

	public function actionShowSitemap()
	{
		$sitemap    = $this->application->bll->sitemap->getAll();
		$byParents  = array();
		foreach($sitemap as $sitemapItem)
		{
			$byParents[$sitemapItem['parent_id']][$sitemapItem['id']] = $sitemapItem;
		}
		$selectedItemId = $this->application->request->getQueryParam('sitemapId' , 0);
		return array(
			'sitemap'   => $byParents,
			'selectedItemId'    => $selectedItemId,
			'parentId'  =>  $this->application->request->getQueryParam('parentId' , 0)
		);
	}
}