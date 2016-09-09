
<div class="main-content">



 
  <?php if ($products) { ?>

  
  <div class="catalog" style="margin-left:15px;">
    <?php foreach ($products as $product) { ?>
    <div class="item">
	

                                <div class="title">
                                    <div class="cat-tag" title="<?php echo $product['category']; ?>"><?php echo $product['category']; ?></div>
                                    <h3><a href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a></h3>
                                </div>
                                <a href="<?php echo $product['href']; ?>" class="thumb" title="<?php echo $product['name']; ?>">
                                     <?php if ($product['thumb']) { ?><img src="<?php echo $product['thumb']; ?>" alt=""> <?php } ?>
                                </a>
                                <div class="price"><div><?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?></div></div>
                                <div class="excerpt"><?php echo $product['description']; ?></div>
                                <div class="o-shell btn-group">
								<a href="#" class="add-to-cart" onclick="addToCart('<?php echo $product['product_id']; ?>');return false; " class="button"><span>Купить</span></a>
                                <a href="<?php echo $product['href']; ?>" class="detail" title="описание товара">описание</a>
                                    <span class="divider"></span>
                                </div>
                      
     
    </div>
    <?php } ?>
  </div>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php if ( !$products) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
</div>