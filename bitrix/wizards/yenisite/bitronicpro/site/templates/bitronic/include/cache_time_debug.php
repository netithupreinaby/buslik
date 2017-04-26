
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(''); ?>

<div class='cache_time_debug'>
<?=date('H:i:s - d.m.Y');?>

<?=$templateFile?>

</div>
<?if(method_exists($this, 'createFrame')) $frame->end();?>