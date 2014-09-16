<?php
return array(
	'map'  => array(
		''      => 'index',
		'admin'  => array(
			''  => 'admin',
			'map' => array(
				'' => 'admin_sitemap',
				'%d'    => array(
					'' => 'admin_sitemap_item'
				)
			),
			'theme' => array(
				'' => 'admin_theme',
			)
		)
	),
	'pages' => array(
		'admin' => array(
			'layout'    => 'admin',
			'title'     => 'Администрирование',
			'blocks'    => array(

			)
		),
		'admin_sitemap'=> array(
			'js'        => array(
				'http://static.jstree.com/3.0.4/assets/dist/jstree.min.js'
			),
			'css'       => array(
				'http://static.jstree.com/3.0.4/assets/dist/themes/default/style.min.css',
				'/static/css/admin_sitemap.css',
			),
			'layout'    => 'admin',
			'blocks'    => array(
				'left' => array(
					'admin_sitemap' => array('admin', 'show' , 'sitemap')
				),
				'center'    => array(
					'admin_sitemap_item'    => array('admin', 'show', 'sitemapItem'),
				)
			)
		),
		'admin_theme'=> array(
			'layout'    => 'admin',
			'blocks'    => array(
				'center' => array(
					'admin_theme' => array('admin', 'show' , 'theme')
				)
			)
		),
	),
	'layout_defaults'  => array(
		'admin' => array(
			'css'   => array(
				'/static/css/admin.css',
			),
			'js'    => array(
				'http://code.jquery.com/jquery-latest.min.js'
			),
			'blocks'    => array(
				'top'   => array(
					'admin_header' => array('admin','show','header'),
				),
				'left'  => array(
					'admin_menu'=>array('admin', 'show', 'menu')
				)
			)
		),
		'promo/main' => array(
			'css' => array(
				'/static/css/promo/promo.css',
			)
		)
	)
);