<?php /**
* @var \Core\View\Base $view
*/
?><!DOCTYPE html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?=\Util\Html::encode($view->getPageTitle())?></title>
	<?php
	foreach($view->getJs() as $jsScriptAddress)
	{
	?>
		<script type="text/javascript" src="<?=\Util\Html::encode($jsScriptAddress)?>"></script>
	<?php
	}
	foreach($view->getCss() as $cssScriptAddress)
	{
		?>
		<link rel="stylesheet" href="<?=\Util\Html::encode($cssScriptAddress)?>"/>
	<?php
	}
	?>
</head>