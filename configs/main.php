<?php
$projectName    = 'example';
$dbHost         = 'lj-top.ru';
$domain         = 'balancer1.lj-top.ru';

$configuration = array
(
	'includePathes'     => array(
		'classes',
		'lib'
	),
	'database'          => array(
		'web'           => array(
			'dsn'       => 'mysql:dbname=' . $projectName . ';host=' . $dbHost,
			'username'  => 'root',
			'password'  => '2912',
		)
	),
	'projectRoot'       => $projectRoot,
	'domain'            => $domain,
	'components'        => require_once 'components.php',
	'bll_components'    => require_once 'bll_components.php',
	'routing'           => require_once 'routing.php',
	'admin'             => array(
		'availableModules'  => array(
			'1'  => array(
				'menu' => array('site','show','menu'),
			),
			'2'  => array(
				'name'  => 'Лента новостей',
				'class' => '\Module\News',
				'routing'  => array(
					'%d'        => '\Module\NewsItem',
					'%s'        => '\Module\NewsItem',
					'edit'      => '\Module\NewsItem',
				)
			),
		),
		'availableTemplates'    => array(
			'1' => array(
				'id'    => 1,
				'title' => '1 колонка',
				'name'  => 'oneColumn',
			),
			'2' => array(
				'id'    => 2,
				'title' => '2 колонки',
				'name'  => 'twoColumns',
			),
			'3' => array(
				'id'    => 3,
				'title' => '3 колонки',
				'name'  => 'threeColumns',
			),
			'4' => array(
				'id'    => 4,
				'title' => 'Промо главная',
				'name'  => 'main',
				'css'   => array(
					'/static/css/promo/promo.css'
				),
				'blocks' => array(
					1 =>    array(
						'name'  => 'top',
					),
					2 =>    array(
						'name'  => 'bottom',
					),
				)
			),
		),
	),
);
return $configuration;