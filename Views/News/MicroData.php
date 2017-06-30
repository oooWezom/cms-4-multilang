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
    "@id": "<?php echo \Core\HTML::link('/news/'.$obj->alias, true); ?>"
  },
  "headline": "<?php echo $obj->name; ?>",
  <?php if (is_file(HOST.HTML::media('images/news/big/'.$obj->image ,false))) {
			$image=HTML::media('images/news/big/'.$obj->image);
		} else { 
			$image=HTML::media('pic/no-photo.png');
		}
		 $size=getimagesize(HOST.$image);
	?>
  "image": {
    "@type": "ImageObject",
    "url": "<?php echo $image; ?>",
    "height": <?php echo Arr::get($size,1); ?>,
    "width": <?php echo Arr::get($size,0); ?>
  },
  "datePublished": "<?php echo date('c',$obj->date); ?>",
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