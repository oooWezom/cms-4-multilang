<?php 
	use Core\View;
?>
 <?php echo View::tpl(['result' => $links['content'], 'cur' => 0, 'add' => '', 'items' => null], 'Sitemap/Recursive'); ?>