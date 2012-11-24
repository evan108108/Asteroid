$('body').delegate('<?php echo $listener['selector']; ?>', '<?php echo $listener['event']; ?>', function() {
	<?php echo $js; ?>
});
