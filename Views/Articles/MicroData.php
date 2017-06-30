<?php 	use Core\HTML;
		use Core\Arr;
		use Core\Text;
		use Core\Config;
 ?>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "http://<?php echo $_SERVER['HTTP_HOST']. '/articles/'.$obj->alias; ?>"
  },
  "headline": "<?php echo $obj->name; ?>",
  <?php if (is_file(HOST.HTML::media('images/articles/big/'.$obj->image, false))) {
			$image=HTML::media('images/articles/big/'.$obj->image, true);
		} else { 
			$image=HTML::media('pic/no-photo.png', true);
		}
		 $size=getimagesize(HOST.$image);
	?>
  "image": {
    "@type": "ImageObject",
    "url": "<?php echo $image; ?>",
    "height": <?php echo Arr::get($size,1); ?>,
    "width": <?php echo Arr::get($size,0); ?>
  },
  "datePublished": "<?php echo date('c',$obj->created_at); ?>",
  <?php if ($obj->updated_at): ?>
  "dateModified": "<?php echo date('c',$obj->updated_at); ?>",
  <?php endif; ?>
  "author": {
    "@type": "Person",
    "name": "<?php echo Config::get('microdata.author'); ?>"
  },
   "publisher": {
    "@type": "Organization",
    "name": "<?php echo Config::get('microdata.organization'); ?>",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo HTML::media('pic/logo.png'); ?>",
      "width": 305,
      "height": 53
    }
  },
  "description": "<?php echo Text::limit_words(strip_tags($obj->text), 100); ?>"
}
</script>