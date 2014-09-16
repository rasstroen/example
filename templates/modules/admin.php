<?php
/**
 * @var \Core\View\Base $view
 */

function adminShowHeader($results)
{

}

function adminShowMenu($results)
{

	adminShowMenuHelper($results['map']);

}

function adminShowMenuHelper($map)
{
	/**
	 * @var \Core\View\Base $view
	 */
	global $view;
	?><ul class="admin-show-menu"><?php
	foreach($map as $title => $item)
	{
		$selected   = $item['path'] == $view->getApplication()->request->getAbsoluteUrlWithoutParameters();
		?><li><a class="<?=$selected == $item['path'] ? 'selected' : ''?>" href="<?=$item['path'];?>"><?=$title;?></a><?
		if(isset($item['childs']))
		{
			adminShowMenuHelper($item['childs']);
		}
	?></li><?php
	}
?></ul><?php
}

function adminShowSitemap($data)
{
	if(! $data['selectedItemId'])
	{
		$selectedHtml = 'data-jstree=\'{"opened" : true, "selected" : true }\'';
	}
	else
	{
		$selectedHtml = '';
	}


	?>
	<h2>Карта сайта</h2>
	<div id="admin_sitemap">
		<?adminShowSitemapHelper($data['sitemap'], 0, $data['parentId'], $data['selectedItemId'])?>
	</div>
	<script>
		$(function () {
			$('#admin_sitemap').jstree()
				.on('changed.jstree', function (e, data) {
					$selectedId =   (data.selected).toString();
					id          =   $selectedId.replace(/node-(.*)-(\d+)/,'$1');
					parentId    =   $selectedId.replace(/node-(.*)-(\d+)/,'$2');
					$.get(
						'?writemodule=admin_sitemap_click',
						{id : id, parentId : parentId}
						,function(data){
							document.location.href = data.locationUrl;
						}
					)
				});
		});
	</script>

<?php
}

function adminShowSitemapHelper($sitemap, $parentId = 0, $currentParentId =0, $selectedItemId = 0)
{

	if(isset($sitemap[$parentId]))
	{
		foreach($sitemap[$parentId] as $sitemapItem)
		{
			if($sitemapItem['id'] == $selectedItemId)
			{
				$selectedHtml = 'data-jstree=\'{ "opened" : true, "selected" : true }\'';
			}
			else
			{
				$selectedHtml = '';
			}

			?><ul>
			<li <?=$selectedHtml?> id="node-<?=\Util\Html::encode($sitemapItem['id'])?>-<?=\Util\Html::encode($parentId)?>">
				<a id="node-<?=\Util\Html::encode($sitemapItem['id'])?>" href="?sitemapId=<?=\Util\Html::encode($sitemapItem['id'])?>">
					<?=\Util\Html::encode($sitemapItem['title']);?>
				</a>
				<?php adminShowSitemapHelper($sitemap, $sitemapItem['id'], $currentParentId, $selectedItemId)?>
			</li>
			</ul><?php
		}
	}
	if (('n' === $selectedItemId) && ($parentId == $currentParentId))
	{
		$selectedHtml = 'data-jstree=\'{ "opened" : true, "selected" : true }\'';
	}
	else
	{
		$selectedHtml = '';
	}
	if($parentId !== 0)
	{
		?><ul><li <?=$selectedHtml?> id="node-n-<?=\Util\Html::encode($parentId)?>">Добавить подраздел</li></ul><?php
	}
}

function adminShowSitemapItem(array $data)
{
	?><h2>Управление разделом</h2><?php
	if(isset($data['new']) && $data['new'])
	{
		/**
		 * Форма добавления раздела
		 */
		?>
		<h3>Добавление раздела</h3>
		<form class="sitemap_edit_form">
			<div><span>Шаблон страницы</span>
				<select name="template">
					<?php foreach($data['availableTemplates'] as $template){?>
					<option value="<?=\Util\Html::encode($template['id'])?>"><?=\Util\Html::encode($template['title'])?></option>
					<?php }?>
				</select>
			</div>
			<div><span>URL страницы</span><em><?=\Util\Html::encode($data['parentUrl'])?></em><input name="url" /></div>
			<div><span>Заголовок страницы</span><input name="title"></div>
			<div><input type="submit" value="Добавить раздел"></div>
			<input type="hidden" name="writemodule" value="admin_sitemap_addItem">
			<input type="hidden" name="parentId" value="<?=\Util\Html::encode($data['parentId']);?>">
		</form>
	<?php
	}
	else
	{
		$sitemapItem = $data['sitemapItem'];
		if(!empty($sitemapItem))
		{
		?>
		<form class="sitemap_edit_form">
			<div><span>Шаблон страницы</span>
				<select name="layout">
					<?php foreach($data['availableTemplates'] as $template){?>
						<option <?php if($sitemapItem['layout'] == $template['id']) echo 'selected="selected" ';?> value="<?=\Util\Html::encode($template['id'])?>"><?=\Util\Html::encode($template['title'])?></option>
					<?php }?>
				</select>
			</div>
			<div><span>Тема страницы</span><input name="template" value="<?=\Util\Html::encode($sitemapItem['template']);?>" /></div>
			<?php if($sitemapItem['parent_id']) {?>
			<div><span>URL страницы</span><em><?=\Util\Html::encode($data['parentUrl'])?></em><input name="url" value="<?=\Util\Html::encode($sitemapItem['name']);?>" /></div>
			<?php }?>
			<div><span>Заголовок страницы</span><input name="title" value="<?=\Util\Html::encode($sitemapItem['title']);?>" ></div>
			<div><input type="submit" value="Сохранить"></div>
			<input type="hidden" name="writemodule" value="admin_sitemap_saveItem">
			<input type="hidden" name="parentId" value="<?=\Util\Html::encode($data['parentId']);?>">
			<input type="hidden" name="id" value="<?=\Util\Html::encode($sitemapItem['id']);?>">
		</form>
	<?php
		}
		?>
		<h2>Модули раздела</h2>
		<?php  foreach($data['availableTemplates'][$sitemapItem['layout']]['blocks'] as $id => $block){?>
			<h3>Блок "<?=\Util\Html::encode($block['name'])?>"</h3>
		<?php }?>
	<?php
	}

}