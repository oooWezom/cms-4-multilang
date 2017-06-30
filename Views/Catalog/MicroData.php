<?php
use Core\HTML;
use Core\Widgets;
use Core\Config;
?>

<script type="application/ld+json">
{
	"@context": "http://schema.org/",
	"@type": "Product",
	"name": "<?php echo $obj->name; ?>",
    <?php if (is_file(HOST.HTML::media('images/catalog/big/'.$obj->image, false))) {
			$image=HTML::media('images/catalog/big/'.$obj->image);
		} else { 
			$image=HTML::media('pic/no-photo.png');
		}
	?>
	"image": "http://<?php echo $_SERVER['HTTP_HOST'].$image; ?>",
	"mpn": "<?php echo $obj->id; ?>",
	<?php if ($obj->brand_name): ?>
	"brand": {
		"@type": "Thing",
		"name": "<?php echo $obj->brand_name; ?>"
	},
	<?php endif; ?>
	<?php if (sizeof($reviews)): ?>
	<?php $rate=0; ?>
	"review": [
	<?php $i=1; ?>
	<?php foreach ($reviews as $review): ?>
	{"@type": "Review",
		"author": "<?php echo $review->name; ?>",
		"datePublished": "<?php echo date('Y-m-d',$review->date); ?>",
		"description": "<?php echo $review->text; ?>",
		"reviewRating": {
			"@type": "Rating",
			"bestRating": "5",
			"ratingValue": "<?php echo $review->rate; ?>",
			"worstRating": "1"
		}
	}<?php $i++; $rate = $rate+$review->rate; ?><?php if ($i<=count($reviews)) echo ','; ?>
	<?php endforeach; ?>
  ],
  <?php endif; ?>
  <?php if (sizeof($reviews) and $rate>0): ?>
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo round($rate/count($reviews)); ?>",
    "reviewCount": "<?php echo count($reviews); ?>"
  },
  <?php endif; ?>
  "offers": {
    "@type": "Offer",
    "priceCurrency": "UAH",
    "price": "<?php echo $obj->cost; ?>",
    "priceValidUntil": "<?php echo date('Y-m-d',(time()+365*24*60*60)); ?>",
    "itemCondition": "http://schema.org/UsedCondition",
	<?php if ($obj->available==1): ?>
    "availability": "http://schema.org/InStock",
	<?php elseif($obj->available==2): ?>
	"availability": "http://schema.org/LimitedAvailability",
	<?php else: ?>
	"availability": "http://schema.org/OutOfStock",
	<?php endif; ?>
	"url": "<?php echo HTML::link($obj->alias.'/p'.$obj->id, true, true); ?>",
    "seller": {
      "@type": "Organization",
      "name": "<?php echo Config::get('microdata.organization'); ?>"
    }
  }
}
</script>