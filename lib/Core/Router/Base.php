<?php
namespace Core\Router;
use Core\Request\Http;
use Util\Arr;

class Base extends \Core\Component\Base
{
	public function getAdminMapPath()
	{
		return '/admin/map';
	}

	public function getAbsoluteRoot()
	{
		$configuration = $this->application->getConfiguration();
		return 'http://' . $configuration['domain'] . '/';
	}

	public function getAdminThemePath()
	{
		return '/admin/theme';
	}

	public function getAdminPath()
	{
		return '/admin';
	}

	public function getRoutingByRequest()
	{
		list($pageKey, $uriVariables) = $this->getConfigurationByRequest();
		$configuration  = $this->application->getConfiguration();
		if(isset($configuration['routing']['pages'][$pageKey]))
		{
			$pageConfiguration  = $configuration['routing']['pages'][$pageKey];
		}
		else
		{
			throw new \Exception('Missed configuration for key "' . $pageKey . '"');
		}
		$key = $pageConfiguration['layout'];
		if(isset($pageConfiguration['template']) && $pageConfiguration['template'])
		{
			$key = $pageConfiguration['template'] . '/' . $key ;
		}

		if(isset($configuration['routing']['layout_defaults'][$key]))
		{
			$pageConfiguration = Arr::mergeArray($configuration['routing']['layout_defaults'][$key], $pageConfiguration);
		}
		$pageConfiguration['variables'] = $uriVariables;
		return $pageConfiguration;
	}

	public function getConfigurationByRequest()
	{
		/**
		 * По запросу определяем ключ страницы, которую будет показывать
		 */
		list($configurationKey, $uriVariables) = $this->getConfigurationKeyVariablesByRequest();
		/**
		 *  Возвращаем объект - конфигурацию страницы для отрисовки
		 */
		return array($configurationKey, $uriVariables);
	}


	private function applyUserMap($byParents, &$map, $parentId = 0, $currentLevelName = '')
	{
		if(!isset($byParents[$parentId]))
		{
			return $map;
		}
		foreach($byParents[$parentId] as $item)
		{
			if($parentId == 0)
			{
				$map[''] =  'index';
				$this->applyUserMap($byParents, $map, 1, $currentLevelName);
			}
			else
			{
				$map[$item['name']] = array(
					'' => $item['name']
				);
				if(isset($byParents[$item['id']]))
				{
					$this->applyUserMap($byParents, $map[$item['name']], $item['id'], $currentLevelName);
				}
			}
		}
		return $map;
	}

	private function prepareUserPageConfiguration(array $mapItem)
	{
		$configuration  = $this->application->getConfiguration();
		return array(
			'layout'    => $configuration['admin']['availableTemplates'][$mapItem['layout']]['name'],
			'title'     => $mapItem['title'],
			'template'  => isset($mapItem['template']) ? $mapItem['template'] : '',
			'blocks'    => isset($mapItem['blocks']) ? $mapItem['blocks'] : array()
		);
	}

	private function getConfigurationKeyVariablesByRequest()
	{
		/**
		 * Получаем карту роутинга
		 */
		$configuration  = $this->application->getConfiguration();
		$map = $configuration['routing']['map'];
		/**
		 * applying users map
		 */
		$userMap = $this->application->bll->sitemap->getAll();
		foreach($userMap as $mapItem)
		{
			$byParents[$mapItem['parent_id']][$mapItem['id']] = $mapItem;
		}
		ksort($byParents);
		$this->applyUserMap($byParents, $map, 0);

		$userBlocks =  $this->application->bll->sitemap->getBlocks();
		foreach($userBlocks as $userBlockModule)
		{
			$sitemapItem = $userMap[$userBlockModule['sitemap_id']];

			$blockName  =$configuration['admin']['availableTemplates'][$sitemapItem['layout']]['blocks'][$userBlockModule['block_id']]['name'];
			$userMap[$userBlockModule['sitemap_id']]['blocks'][$blockName] = $configuration['admin']['availableModules'][$userBlockModule['module_id']];
		}

		foreach($userMap as $mapItem)
		{
			$configuration['routing']['pages'][$mapItem['name'] ? $mapItem['name'] : 'index'] = $this->prepareUserPageConfiguration($mapItem);
		}
		$this->application->setRoutingPagesConfiguration($configuration['routing']['pages']);



		/**
		 * Чистим requestUri
		 */
		$requestUri = $this->application->request->getAbsoluteUrl();
		$parameters = '';
		if(strpos($requestUri, '?'))
		{
			list($requestUri, $parameters) = explode('?', $requestUri);
			if($parameters)
			{
				$parameters = '?' . $parameters;
			}
		}
		$requestUriArray            = explode('/', $requestUri);
		$preparedRequestUriArray    = array();
		foreach($requestUriArray as $uriPart)
		{
			if(trim($uriPart))
			{
				$preparedRequestUriArray[] = $uriPart;
			}
		}

		/**
		 * Определяем страницу по очищенному requestUri
		 */
		list($idealRequestUriParts, $pageKey, $uriVariables) = $this->findPageKey($map, $preparedRequestUriArray);


		if($pageKey)
		{
			$idealUrl = '/' . implode('/', $idealRequestUriParts) . $parameters;
		}
		else
		{
			$idealUrl = '/';
		}
		/**
		 * URL неправильный
		 */
		if($idealUrl !== $this->application->request->getAbsoluteUrl())
		{

			if($pageKey && ($pageKey !== 'index'))
			{
				$this->application->request->redirect($idealUrl);
			}
			else
			{
				throw new \Exception(404);
			}
			return;
		}
		if($pageKey == '')
		{
			$pageKey = 'index';
		}
		return array($pageKey, $uriVariables);
	}

	private function findPageKey($map, $requestArray, $currentIndex = 0, &$idealRequestUriParts = array(), &$variables = array())
	{

		$currentUriPart = isset($requestArray[$currentIndex]) ? $requestArray[$currentIndex] : false;
		if(isset($map['_var']))
		{
			$variables[$map['_var']] = $requestArray[$currentIndex - 1];
			unset($map['_var']);
		}
		foreach($map as $key => $value)
		{
			if($key == $currentUriPart)
			{
				/**
				 * точное совпадение
				 */
				if($currentUriPart)
				{
					$idealRequestUriParts[] = $currentUriPart;
				}

				if(is_array($value))
				{
					return $this->findPageKey($value, $requestArray, $currentIndex + 1, $idealRequestUriParts, $variables);
				}
				else
				{
					return array($idealRequestUriParts, $value, $variables);
				}
			}
			elseif(($key == '%d') && (intval($currentUriPart) > 0) && is_numeric($currentUriPart))
			{
				/**
				 * цифра
				 */
				$idealRequestUriParts[] = $currentUriPart;

				if(is_array($value))
				{
					return $this->findPageKey($value, $requestArray, $currentIndex + 1, $idealRequestUriParts, $variables);
				}
				else
				{
					return array($idealRequestUriParts, $value, $variables);
				}
			}
			elseif($key == '%s' && $currentUriPart)
			{
				$idealRequestUriParts[] = $currentUriPart;

				if(is_array($value))
				{
					return $this->findPageKey($value, $requestArray, $currentIndex + 1, $idealRequestUriParts, $variables);
				}
				else
				{
					return array($idealRequestUriParts, $value, $variables);
				}
			}

		}

		if(isset($map['']) && !is_array($map['']))
		{
			return array($idealRequestUriParts, $map[''], $variables);
		}

		return array($idealRequestUriParts, false, $variables);
	}


}