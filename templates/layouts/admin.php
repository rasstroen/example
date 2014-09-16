<?php require 'layout.php'; ?>
<body>

<div class="l-container">
	<div class="l-header">
		<?php $view->renderBlock('top'); ?>
	</div>
	<div class="l-wrapper">
		<div class="l-sidebar">
			<?php $view->renderBlock('left'); ?>
		</div>
		<div class="l-content">
			<?php $view->renderBlock('center'); ?>
		</div>
	</div>

</div>
<div class="l-footer">
	<?php $view->renderBlock('bottom'); ?>
</div>

</body>
