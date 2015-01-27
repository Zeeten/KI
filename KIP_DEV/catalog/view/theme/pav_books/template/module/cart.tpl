<?php $themeName = basename( dirname(dirname(dirname(__FILE__))) ); ?>

<div id="cart" class="pull-right"> 
    <div class="heading">
        <div class="pull-left">
			<i class="pavicon-cart">&nbsp;</i>
		</div>
        <div class="pav-cart-title pull-left">
            <h3><?php echo $heading_title; ?></h3>
            <a class="total-cart"><span id="cart-total"><?php echo $text_items; ?></span></a>
        </div>
    </div>	
    <div class="content">
        <?php if ($products || $vouchers) { ?>
        <div class="mini-cart-info">
            <table class="table">
                <?php foreach ($products as $product) { ?>
                <tr>
                    <td class="image">
                        <?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                        <?php } ?>
                    </td>
                    <td class="name">
                        <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                        <div class="mini-cart-content">
                            <?php foreach ($product['option'] as $option) { ?>
                            - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
                            <?php } ?>
                            

                            <?php if ($product['recurring']): ?>
                            - <small><?php echo $text_payment_profile ?> <?php echo $product['profile']; ?></small><br />
                            <?php endif; ?>

                        </div>
                    </td>
                    <td class="quantity">
                        x&nbsp;<?php echo $product['quantity']; ?>
                    </td>
                    <td class="total">
                        <?php echo $product['total']; ?>
                    </td>
                    <td class="remove">
                        <img src="catalog/view/theme/<?php echo $themeName;?>/image/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *');" />
                    </td>
                </tr>
                <?php } ?>
                <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                    <td class="image"></td>
                    <td class="name"><?php echo $voucher['description']; ?></td>
                    <td class="quantity">x&nbsp;1</td>
                    <td class="total"><?php echo $voucher['amount']; ?></td>
                    <td class="remove">
                        <img src="catalog/view/theme/<?php echo $themeName;?>/image/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');" />
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <div class="mini-cart-total">
            <table class="table">
                <?php foreach ($totals as $total) { ?>
                <tr>
                    <td class="a-right"><b class="fs13"><?php echo $total['title']; ?>:</b></td>
                    <td class="a-right"><?php echo $total['text']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <div class="checkout">
            <a href="<?php echo $cart; ?>" class="button"><?php echo $text_cart; ?></a>
            <a href="<?php echo $checkout; ?>" class="button"><?php echo $text_checkout; ?></a>
        </div>
        <?php } else { ?>
        <div class="empty"><?php echo $text_empty; ?></div>
		<p class="closecart"><button onclick="$('#cart').removeClass('active');" class="btn-small">Close</button></p>		
        <?php } ?>
    </div>
</div>