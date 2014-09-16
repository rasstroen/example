<?php
namespace Module;
class Site extends \Module\Base
{
	public function actionShowMenu()
	{

		$menu = $this->application->bll->sitemap->getAll();
		$mainMenu = array();
		foreach($menu as $menuItem)
		{
			if($menuItem['parent_id'] < 2)
			{
				$mainMenu[$menuItem['title']] = '/' . $menuItem['name'];
			}
		}
		return array(
			'menu'      => $mainMenu,
			'selected'  => $this->application->request->getAbsoluteUrlWithoutParameters(),
		);
	}
}