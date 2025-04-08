<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:57
         compiled from widget/product/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'widget/product/list.tpl', 56, false),array('function', 'counter', 'widget/product/list.tpl', 61, false),array('function', 'oxid_include_widget', 'widget/product/list.tpl', 66, false),array('modifier', 'cat', 'widget/product/list.tpl', 62, false),)), $this); ?>
<?php if (! $this->_tpl_vars['type']): ?>
    <?php $this->assign('type', 'infogrid'); ?>
<?php endif; ?>

<?php if (! $this->_tpl_vars['iProductsPerLine']): ?>
    <?php $this->assign('iProductsPerLine', 4); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['type'] == 'infogrid'): ?>
    <?php $this->assign('iProductsPerLine', 2); ?>
<?php elseif ($this->_tpl_vars['type'] == 'grid'): ?>
    <?php $this->assign('iProductsPerLine', 4); ?>
<?php elseif ($this->_tpl_vars['type'] == 'line'): ?>
    <?php $this->assign('iProductsPerLine', 1); ?>
<?php endif; ?>

<?php if (! $this->_tpl_vars['testid']): ?>
    <?php $this->assign('testid', $this->_tpl_vars['oView']->getViewParameter('testid')); ?>
<?php endif; ?>
<?php if (! $this->_tpl_vars['listId']): ?>
    <?php $this->assign('listId', $this->_tpl_vars['oView']->getViewParameter('listId')); ?>
<?php endif; ?>


    <div class="boxwrapper" id="boxwrapper_<?php echo $this->_tpl_vars['listId']; ?>
">
        
            <?php if ($this->_tpl_vars['head']): ?>
                <?php if ($this->_tpl_vars['header'] == 'light'): ?>
                    <div class="page-header">
                        <span class="h3"><?php echo $this->_tpl_vars['head']; ?>
</span>

                        <?php if ($this->_tpl_vars['subhead']): ?>
                            <small class="subhead"><?php echo $this->_tpl_vars['subhead']; ?>
</small>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="page-header">
                        <h2 class="h2">
                            <?php echo $this->_tpl_vars['head']; ?>

                            <?php if ($this->_tpl_vars['rsslink']): ?>
                                <a class="rss" id="<?php echo $this->_tpl_vars['rssId']; ?>
" aria-label="RSS" href="<?php echo $this->_tpl_vars['rsslink']['link']; ?>
" target="_blank">
                                    <i class="fas fa-rss"></i>
                                </a>
                            <?php endif; ?>
                        </h2>

                        <?php if ($this->_tpl_vars['subhead']): ?>
                            <small class="subhead"><?php echo $this->_tpl_vars['subhead']; ?>
</small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        

        <?php if ($this->_tpl_vars['products'] && ! empty ( $this->_tpl_vars['products'] )): ?>
            <?php echo smarty_function_math(array('equation' => "x / y",'x' => 12,'y' => $this->_tpl_vars['iProductsPerLine'],'assign' => 'iColIdent'), $this);?>


            <div class="list-container" id="<?php echo $this->_tpl_vars['listId']; ?>
">
                <div class="row <?php echo $this->_tpl_vars['type']; ?>
-view newItems">
                <?php $_from = $this->_tpl_vars['products']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['productlist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['productlist']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_product']):
        $this->_foreach['productlist']['iteration']++;
?>
                    <?php echo smarty_function_counter(array('print' => false,'assign' => 'productlistCounter'), $this);?>

                    <?php $this->assign('testid', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['listId'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_foreach['productlist']['iteration']) : smarty_modifier_cat($_tmp, $this->_foreach['productlist']['iteration']))); ?>

                    
                        <div class="productData col-12<?php if ($this->_tpl_vars['type'] != 'line'): ?> col-sm-6 col-md-4 col-lg-<?php echo $this->_tpl_vars['iColIdent']; ?>
<?php endif; ?> productBox product-box">
                            <?php echo smarty_function_oxid_include_widget(array('cl' => 'oxwArticleBox','_parent' => $this->_tpl_vars['oView']->getClassName(),'nocookie' => 1,'_navurlparams' => $this->_tpl_vars['oViewConf']->getNavUrlParams(),'iLinkType' => $this->_tpl_vars['_product']->getLinkType(),'_object' => $this->_tpl_vars['_product'],'anid' => $this->_tpl_vars['_product']->getId(),'sWidgetType' => 'product','sListType' => "listitem_".($this->_tpl_vars['type']),'iIndex' => $this->_tpl_vars['testid'],'blDisableToCart' => $this->_tpl_vars['blDisableToCart'],'isVatIncluded' => $this->_tpl_vars['oView']->isVatIncluded(),'showMainLink' => $this->_tpl_vars['showMainLink'],'recommid' => $this->_tpl_vars['recommid'],'owishid' => $this->_tpl_vars['owishid'],'toBasketFunction' => $this->_tpl_vars['toBasketFunction'],'removeFunction' => $this->_tpl_vars['removeFunction'],'altproduct' => $this->_tpl_vars['altproduct'],'inlist' => $this->_tpl_vars['_product']->isInList(),'skipESIforUser' => 1,'testid' => $this->_tpl_vars['testid']), $this);?>

                        </div>
                    

                <?php endforeach; endif; unset($_from); ?>
                </div>

                                <?php echo smarty_function_counter(array('print' => false,'assign' => 'productlistCounter','start' => 0), $this);?>

            </div>
        <?php endif; ?>
    </div>