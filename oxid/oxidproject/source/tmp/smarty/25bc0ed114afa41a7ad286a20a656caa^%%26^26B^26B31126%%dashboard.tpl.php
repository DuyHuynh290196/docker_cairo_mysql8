<?php /* Smarty version 2.6.33, created on 2025-04-03 13:15:14
         compiled from page/account/dashboard.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'page/account/dashboard.tpl', 1, false),array('modifier', 'cat', 'page/account/dashboard.tpl', 17, false),array('function', 'oxmultilang', 'page/account/dashboard.tpl', 4, false),array('function', 'oxgetseourl', 'page/account/dashboard.tpl', 17, false),array('insert', 'oxid_tracker', 'page/account/dashboard.tpl', 147, false),)), $this); ?>
<?php $this->assign('template_title', ((is_array($_tmp='MY_ACCOUNT')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
<?php ob_start(); ?>
    <div class="accountDashboardView">
        <h1 id="accountMain" class="page-header"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_ACCOUNT'), $this);?>

            "<?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxusername->value; ?>
"</h1>

        <?php if ($this->_tpl_vars['oView']->getAccountDeletionStatus() === false): ?>
            <?php $this->assign('statusMessage', ((is_array($_tmp='DD_DELETE_MY_ACCOUNT_ERROR')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/error.tpl", 'smarty_include_vars' => array('statusMessage' => $this->_tpl_vars['statusMessage'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a id="linkAccountPassword" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_password") : smarty_modifier_cat($_tmp, "cl=account_password"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'CHANGE_PASSWORD'), $this);?>
</a>
                            <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_password") : smarty_modifier_cat($_tmp, "cl=account_password"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'CHANGE_PASSWORD'), $this);?>
</div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a id="linkAccountNewsletter" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_newsletter") : smarty_modifier_cat($_tmp, "cl=account_newsletter"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'NEWSLETTER_SETTINGS'), $this);?>
</a>
                            <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_newsletter") : smarty_modifier_cat($_tmp, "cl=account_newsletter"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'NEWSLETTER_SUBSCRIBE_CANCEL'), $this);?>
</div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a id="linkAccountBillship" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_user") : smarty_modifier_cat($_tmp, "cl=account_user"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'BILLING_SHIPPING_SETTINGS'), $this);?>
</a>
                            <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_user") : smarty_modifier_cat($_tmp, "cl=account_user"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'UPDATE_YOUR_BILLING_SHIPPING_SETTINGS'), $this);?>
</div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a id="linkAccountOrder" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_order") : smarty_modifier_cat($_tmp, "cl=account_order"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ORDER_HISTORY'), $this);?>
</a>
                            <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_order") : smarty_modifier_cat($_tmp, "cl=account_order"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'ORDERS'), $this);?>
 <?php echo $this->_tpl_vars['oView']->getOrderCnt(); ?>
</div>
                    </div>
                    <?php if ($this->_tpl_vars['oView']->isEnabledDownloadableFiles()): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a id="linkAccountDownloads" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_downloads") : smarty_modifier_cat($_tmp, "cl=account_downloads"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_DOWNLOADS'), $this);?>
</a>
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_downloads") : smarty_modifier_cat($_tmp, "cl=account_downloads"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_DOWNLOADS_DESC'), $this);?>
</div>
                        </div>
                    <?php endif; ?>
                
            </div>
            <div class="col-xs-12 col-md-6">
                
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_noticelist") : smarty_modifier_cat($_tmp, "cl=account_noticelist"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_WISH_LIST'), $this);?>
</a>
                            <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_noticelist") : smarty_modifier_cat($_tmp, "cl=account_noticelist"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'PRODUCT'), $this);?>
 <?php if ($this->_tpl_vars['oxcmp_user']): ?><?php echo $this->_tpl_vars['oxcmp_user']->getNoticeListArtCnt(); ?>
<?php else: ?>0<?php endif; ?></div>
                    </div>
                    <?php if ($this->_tpl_vars['oViewConf']->getShowWishlist()): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_wishlist") : smarty_modifier_cat($_tmp, "cl=account_wishlist"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_GIFT_REGISTRY'), $this);?>
</a>
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_wishlist") : smarty_modifier_cat($_tmp, "cl=account_wishlist"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'PRODUCT'), $this);?>
 <?php if ($this->_tpl_vars['oxcmp_user']): ?><?php echo $this->_tpl_vars['oxcmp_user']->getWishListArtCnt(); ?>
<?php else: ?>0<?php endif; ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['oViewConf']->getShowCompareList()): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=compare") : smarty_modifier_cat($_tmp, "cl=compare"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_PRODUCT_COMPARISON'), $this);?>
</a>
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=compare") : smarty_modifier_cat($_tmp, "cl=compare"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'PRODUCT'), $this);?>
 <?php if ($this->_tpl_vars['oView']->getCompareItemsCnt()): ?><?php echo $this->_tpl_vars['oView']->getCompareItemsCnt(); ?>
<?php else: ?>0<?php endif; ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['oViewConf']->getShowListmania()): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_recommlist") : smarty_modifier_cat($_tmp, "cl=account_recommlist"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_LISTMANIA'), $this);?>
</a>
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_recommlist") : smarty_modifier_cat($_tmp, "cl=account_recommlist"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'LISTS'), $this);?>
 <?php if ($this->_tpl_vars['oxcmp_user']->getRecommListsCount()): ?><?php echo $this->_tpl_vars['oxcmp_user']->getRecommListsCount(); ?>
<?php else: ?>0<?php endif; ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['oView']->isUserAllowedToManageOwnReviews()): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_reviewlist") : smarty_modifier_cat($_tmp, "cl=account_reviewlist"))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_REVIEWS'), $this);?>
</a>
                                <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSslSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account_reviewlist") : smarty_modifier_cat($_tmp, "cl=account_reviewlist"))), $this);?>
" class="btn btn-default btn-xs pull-right">
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="panel-body"><?php echo smarty_function_oxmultilang(array('ident' => 'MY_REVIEWS'), $this);?>
 <?php if ($this->_tpl_vars['oView']->getReviewAndRatingItemsCount()): ?><?php echo $this->_tpl_vars['oView']->getReviewAndRatingItemsCount(); ?>
<?php else: ?>0<?php endif; ?></div>
                        </div>
                    <?php endif; ?>
                
            </div>
        </div>
    </div>

    <div class="row">
        
            <div class="col-xs-6">
                <?php if ($this->_tpl_vars['oView']->isUserAllowedToDeleteOwnAccount()): ?>
                <button
                        class="btn btn-danger"
                        data-toggle="modal"
                        data-target="#delete_my_account_confirmation"
                >
                    <i class="fa fa-trash"></i>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'DD_DELETE_MY_ACCOUNT'), $this);?>

                </button>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/account/delete_my_account_confirmation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php endif; ?>
            </div>
        
        <div class="col-xs-6 text-right">
            <a href="<?php echo $this->_tpl_vars['oViewConf']->getLogoutLink(); ?>
" class="btn btn-warning" role="getLogoutLink">
                <i class="fa fa-power-off"></i> <?php echo smarty_function_oxmultilang(array('ident' => 'LOGOUT'), $this);?>

            </a>
        </div>
        <p>&nbsp;</p>
    </div>
    <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'oxid_tracker', 'title' => $this->_tpl_vars['template_title'])), $this); ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_content', ob_get_contents());ob_end_clean(); ?>


<?php ob_start(); ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/account/inc/account_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_sidebar', ob_get_contents());ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "layout/page.tpl", 'smarty_include_vars' => array('sidebar' => 'Left')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>