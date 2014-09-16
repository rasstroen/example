<?php
function siteShowMenu($data)
{
	?><ul>
		<?php foreach($data['menu'] as $title => $url) {
	if($data['selected'] == $url)
	{
		$css = 'class="selected"';
	}
	else
	{
		$css = '';
	}
	?>
		<li>
			<a <?=$css?> href="<?=\Util\Html::encode($url)?>"><?=\Util\Html::encode($title)?></a>
		</li>
		<?php }?>
	</ul><?
}