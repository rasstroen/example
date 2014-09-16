<?php require $view->getApplication()->getRootPath() . 'templates/layouts/layout.php'; ?>
<body>

<div class="l-container">
	<div class="l-header">
		<?php $view->renderBlock('top'); ?>
	</div>
	<div class="l-wrapper">
		<div class="l-content">
			<?php $view->renderBlock('center'); ?>
		</div>
	</div>

</div>
<div class="l-footer">
	<?php $view->renderBlock('bottom'); ?>
</div>

</body>
