<?php /* Smarty version 2.6.33, created on 2025-04-03 13:15:14
         compiled from page/account/inc/account_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxgetseourl', 'page/account/inc/account_menu.tpl', 5, false),array('function', 'oxmultilang', 'page/account/inc/account_menu.tpl', 5, false),array('modifier', 'cat', 'page/account/inc/account_menu.tpl', 5, false),)), $this); ?>
<nav id="account_menu">
    <ul class="list-group">
        
            <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'password'): ?> active<?php endif; ?>">
                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_password") : smarty_modifier_cat($_tmp, "cl=account_password"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'CHANGE_PASSWORD'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'CHANGE_PASSWORD'), $this);?>
</a>
            </li>
            <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'newsletter'): ?> active<?php endif; ?>">
                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_newsletter") : smarty_modifier_cat($_tmp, "cl=account_newsletter"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'NEWSLETTER_SETTINGS'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'NEWSLETTER_SETTINGS'), $this);?>
</a>
            </li>
            <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'billship'): ?> active<?php endif; ?>">
                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_user") : smarty_modifier_cat($_tmp, "cl=account_user"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'BILLING_SHIPPING_SETTINGS'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'BILLING_SHIPPING_SETTINGS'), $this);?>
</a>
            </li>
            <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'orderhistory'): ?> active<?php endif; ?>">
                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_order") : smarty_modifier_cat($_tmp, "cl=account_order"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'ORDER_HISTORY'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ORDER_HISTORY'), $this);?>
</a>
            </li>
            <?php if ($this->_tpl_vars['oViewConf']->getShowCompareList()): ?>
                <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'compare'): ?> active<?php endif; ?>">
                    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=compare") : smarty_modifier_cat($_tmp, "cl=compare"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'MY_PRODUCT_COMPARISON'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_PRODUCT_COMPARISON'), $this);?>
<?php if ($this->_tpl_vars['oView']->getCompareItemCount() > 0): ?> <span class="badge"><?php echo $this->_tpl_vars['oView']->getCompareItemCount(); ?>
</span><?php endif; ?></a>
                </li>
            <?php endif; ?>
            <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'noticelist'): ?> active<?php endif; ?>">
                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_noticelist") : smarty_modifier_cat($_tmp, "cl=account_noticelist"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'MY_WISH_LIST'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_WISH_LIST'), $this);?>
<?php if ($this->_tpl_vars['oxcmp_user'] && $this->_tpl_vars['oxcmp_user']->getNoticeListArtCnt() > 0): ?> <span class="badge"><?php echo $this->_tpl_vars['oxcmp_user']->getNoticeListArtCnt(); ?>
</span><?php endif; ?></a>
            </li>
            <?php if ($this->_tpl_vars['oViewConf']->getShowWishlist()): ?>
                <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'wishlist'): ?> active<?php endif; ?>">
                    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_wishlist") : smarty_modifier_cat($_tmp, "cl=account_wishlist"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'MY_GIFT_REGISTRY'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_GIFT_REGISTRY'), $this);?>
<?php if ($this->_tpl_vars['oxcmp_user'] && $this->_tpl_vars['oxcmp_user']->getWishListArtCnt() > 0): ?> <span class="badge"><?php echo $this->_tpl_vars['oxcmp_user']->getWishListArtCnt(); ?>
</span><?php endif; ?></a>
                </li>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['oViewConf']->getShowListmania()): ?>
                <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'recommendationlist'): ?> active<?php endif; ?>">
                    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_recommlist") : smarty_modifier_cat($_tmp, "cl=account_recommlist"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'MY_LISTMANIA'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_LISTMANIA'), $this);?>
</a>
                </li>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['oView']->isEnabledDownloadableFiles()): ?>
                <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'downloads'): ?> active<?php endif; ?>">
                    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_downloads") : smarty_modifier_cat($_tmp, "cl=account_downloads"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'MY_DOWNLOADS'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_DOWNLOADS'), $this);?>
</a>
                </li>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['oView']->isUserAllowedToManageOwnReviews()): ?>
                <li class="list-group-item<?php if ($this->_tpl_vars['active_link'] == 'reviewlist'): ?> active<?php endif; ?>">
                    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_reviewlist") : smarty_modifier_cat($_tmp, "cl=account_reviewlist"))), $this);?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => 'MY_REVIEWS'), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_REVIEWS'), $this);?>

                        <?php if ($this->_tpl_vars['oView']->getReviewAndRatingItemsCount() > 0): ?><span class="badge"><?php echo $this->_tpl_vars['oView']->getReviewAndRatingItemsCount(); ?>
</span><?php endif; ?></a>
                </li>
            <?php endif; ?>
        
    </ul>
</nav>