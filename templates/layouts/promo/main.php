<?php /**
 * @var \Core\View\Base $view
 */
?>
<?php require $view->getApplication()->getRootPath() . 'templates/layouts/layout.php'; ?>
<body>

<div class="l-container">
	<div class="l-header box effect-shadow-5">
		<?php $view->renderBlock('top'); ?>
	</div>
	<div class="l-wrapper">
		<div class="l-content box effect-shadow-2">
			<div class="content">
				<h1><?=$view->getPageTitle()?></h1>
			</div>
		</div>
	</div>
</div>
<div class="l-footer">
	пол
</div>

</body>
