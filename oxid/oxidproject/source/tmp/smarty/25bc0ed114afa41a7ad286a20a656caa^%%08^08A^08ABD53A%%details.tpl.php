<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from page/details/details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxid_include_widget', 'page/details/details.tpl', 6, false),array('function', 'oxmultilang', 'page/details/details.tpl', 48, false),array('modifier', 'cat', 'page/details/details.tpl', 13, false),array('modifier', 'oxmultilangassign', 'page/details/details.tpl', 17, false),)), $this); ?>
<?php if (! $this->_tpl_vars['blWorkaroundInclude']): ?>
    <?php ob_start(); ?>
        <?php if ($this->_tpl_vars['oxcmp_user']): ?>
            <?php $this->assign('force_sid', $this->_tpl_vars['oView']->getSidForWidget()); ?>
        <?php endif; ?>
        <?php echo smarty_function_oxid_include_widget(array('cl' => 'oxwArticleDetails','_parent' => $this->_tpl_vars['oView']->getClassName(),'nocookie' => 1,'force_sid' => $this->_tpl_vars['force_sid'],'_navurlparams' => $this->_tpl_vars['oViewConf']->getNavUrlParams(),'_object' => $this->_tpl_vars['oView']->getProduct(),'anid' => $this->_tpl_vars['oViewConf']->getActArticleId(),'iPriceAlarmStatus' => $this->_tpl_vars['oView']->getPriceAlarmStatus(),'sorting' => $this->_tpl_vars['oView']->getSortingParameters(),'skipESIforUser' => 1), $this);?>

    <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_content', ob_get_contents());ob_end_clean(); ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "layout/page.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
    <?php $this->assign('oDetailsProduct', $this->_tpl_vars['oView']->getProduct()); ?>
    <?php $this->assign('oPictureProduct', $this->_tpl_vars['oView']->getPicturesProduct()); ?>
    <?php $this->assign('currency', $this->_tpl_vars['oView']->getActCurrency()); ?>
    <?php $this->assign('sPageHeadTitle', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['oDetailsProduct']->oxarticles__oxtitle->value)) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' ')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oDetailsProduct']->oxarticles__oxvarselect->value) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oDetailsProduct']->oxarticles__oxvarselect->value))); ?>
    <?php $this->assign('blFullwidth', $this->_tpl_vars['oViewConf']->getViewThemeParam('blFullwidthLayout')); ?>

    <?php if ($this->_tpl_vars['oView']->getPriceAlarmStatus() == 1): ?>
        <?php $this->assign('_statusMessage1', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='PAGE_DETAILS_THANKYOUMESSAGE1')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' ')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oxcmp_shop']->oxshops__oxname->value) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oxcmp_shop']->oxshops__oxname->value))); ?>
        <?php $this->assign('_statusMessage2', ((is_array($_tmp=((is_array($_tmp='PAGE_DETAILS_THANKYOUMESSAGE2')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' '))); ?>
        <?php $this->assign('_statusMessage3', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='PAGE_DETAILS_THANKYOUMESSAGE3')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' ')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oView']->getBidPrice()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oView']->getBidPrice())))) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' ')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['currency']->sign) : smarty_modifier_cat($_tmp, $this->_tpl_vars['currency']->sign)))) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' '))); ?>
        <?php $this->assign('_statusMessage4', ((is_array($_tmp='PAGE_DETAILS_THANKYOUMESSAGE4')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/success.tpl", 'smarty_include_vars' => array('statusMessage' => ($this->_tpl_vars['_statusMessage1']).($this->_tpl_vars['_statusMessage2']).($this->_tpl_vars['_statusMessage3']).($this->_tpl_vars['_statusMessage4']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php elseif ($this->_tpl_vars['oView']->getPriceAlarmStatus() == 2): ?>
        <?php $this->assign('_statusMessage', ((is_array($_tmp='MESSAGE_WRONG_VERIFICATION_CODE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/error.tpl", 'smarty_include_vars' => array('statusMessage' => $this->_tpl_vars['_statusMessage'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php elseif ($this->_tpl_vars['oView']->getPriceAlarmStatus() === 0): ?>
        <?php $this->assign('_statusMessage1', ((is_array($_tmp=((is_array($_tmp='MESSAGE_NOT_ABLE_TO_SEND_EMAIL')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))) ? $this->_run_mod_handler('cat', true, $_tmp, "<br> ") : smarty_modifier_cat($_tmp, "<br> "))); ?>
        <?php $this->assign('_statusMessage2', ((is_array($_tmp='MESSAGE_VERIFY_YOUR_EMAIL')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "message/error.tpl", 'smarty_include_vars' => array('statusMessage' => ($this->_tpl_vars['_statusMessage1']).($this->_tpl_vars['_statusMessage2']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>

    <div id="details_container">
        <div id="details">
            <?php if ($this->_tpl_vars['oView']->getSearchTitle()): ?>
                <?php $this->assign('detailsLocation', $this->_tpl_vars['oView']->getSearchTitle()); ?>
            <?php else: ?>
                <?php $_from = $this->_tpl_vars['oView']->getCatTreePath(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['detailslocation'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['detailslocation']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oCatPath']):
        $this->_foreach['detailslocation']['iteration']++;
?>
                    <?php if (($this->_foreach['detailslocation']['iteration'] == $this->_foreach['detailslocation']['total'])): ?>
                        <?php $this->assign('detailsLocation', $this->_tpl_vars['oCatPath']->oxcategories__oxtitle->value); ?>
                    <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>

                        <?php $this->assign('actCategory', $this->_tpl_vars['oView']->getActiveCategory()); ?>
            <div class="detailsParams listRefine bottomRound">
                <div class="row refineParams clear" id="detailsItemsPager">
                    <div class="col-xs-3 text-left pager-overview-link">
                        <i class="fa fa-bars"></i> <a href="<?php echo $this->_tpl_vars['actCategory']->toListLink; ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'BACK_TO_OVERVIEW'), $this);?>
</a>
                    </div>
                    <div class="col-xs-3 text-left pager-prev">
                        <?php if ($this->_tpl_vars['actCategory']->prevProductLink): ?>
                            <i class="fa fa-angle-left"></i> <a id="linkPrevArticle" class="" href="<?php echo $this->_tpl_vars['actCategory']->prevProductLink; ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS_PRODUCT'), $this);?>
</a>
                        <?php endif; ?>
                    </div>
                    <div class="col-xs-3 text-center pager-current-page">
                        <?php if ($this->_tpl_vars['actCategory']->iProductPos): ?>
                            <?php echo smarty_function_oxmultilang(array('ident' => 'PRODUCT'), $this);?>
 <?php echo $this->_tpl_vars['actCategory']->iProductPos; ?>
 <?php echo smarty_function_oxmultilang(array('ident' => 'OF'), $this);?>
 <?php echo $this->_tpl_vars['actCategory']->iCntOfProd; ?>

                        <?php endif; ?>
                    </div>
                    <div class="col-xs-3 text-right pager-next">
                        <?php if ($this->_tpl_vars['actCategory']->nextProductLink): ?>
                            <a id="linkNextArticle" href="<?php echo $this->_tpl_vars['actCategory']->nextProductLink; ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT_PRODUCT'), $this);?>
</a> <i class="fa fa-angle-right"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($this->_tpl_vars['blFullwidth']): ?>
                    <hr>
                <?php endif; ?>
            </div>

                        <div id="productinfo">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/details/inc/fullproductinfo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div>
        </div>
    </div>
<?php endif; ?>