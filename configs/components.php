<?php
return array(
	'request' => array(
		'class' => '\Core\Request\Http',
	),
	'controller'    => array(
		'class' => '\Core\Controller\Web',
	),
	'router'    => array(
		'class' => '\Core\Router\Base',
	),
	'view'      => array(
		'class' => '\Core\View\Base',
	),
	'bll'       => array(
		'class' =>   '\Core\BLL\Base',
	),
	'db'       => array(
		'class' =>   '\Core\Database\Base',
	),
);