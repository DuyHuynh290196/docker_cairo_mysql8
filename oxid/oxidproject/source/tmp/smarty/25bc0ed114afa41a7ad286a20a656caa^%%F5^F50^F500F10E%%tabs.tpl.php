<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from page/details/inc/tabs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'oxhasrights', 'page/details/inc/tabs.tpl', 4, false),array('function', 'oxmultilang', 'page/details/inc/tabs.tpl', 7, false),array('function', 'oxeval', 'page/details/inc/tabs.tpl', 10, false),)), $this); ?>
<?php $this->assign('blFirstTab', true); ?>


    <?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'SHOWLONGDESCRIPTION')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <?php $this->assign('oLongdesc', $this->_tpl_vars['oDetailsProduct']->getLongDescription()); ?>
        <?php if ($this->_tpl_vars['oLongdesc']->value): ?>
            <?php ob_start(); ?><a href="#description" data-toggle="tab"><?php echo smarty_function_oxmultilang(array('ident' => 'DESCRIPTION'), $this);?>
</a><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabs', ob_get_contents());ob_end_clean(); ?>
            <?php ob_start(); ?>
                <div id="description" class="tab-pane<?php if ($this->_tpl_vars['blFirstTab']): ?> active<?php endif; ?>" itemprop="description">
                    <?php echo smarty_function_oxeval(array('var' => $this->_tpl_vars['oLongdesc']), $this);?>

                    <?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxexturl->value): ?>
                        <a id="productExturl" class="js-external" href="<?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxexturl->value; ?>
">
                        <?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxurldesc->value): ?>
                            <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxurldesc->value; ?>

                        <?php else: ?>
                            <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxexturl->value; ?>

                        <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabsContent', ob_get_contents());ob_end_clean(); ?>
            <?php $this->assign('blFirstTab', false); ?>
        <?php endif; ?>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>



    <?php if ($this->_tpl_vars['oView']->getAttributes()): ?>
        <?php ob_start(); ?><a href="#attributes" data-toggle="tab"><?php echo smarty_function_oxmultilang(array('ident' => 'SPECIFICATION'), $this);?>
</a><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabs', ob_get_contents());ob_end_clean(); ?>
        <?php ob_start(); ?>
            <div id="attributes" class="tab-pane<?php if ($this->_tpl_vars['blFirstTab']): ?> active<?php endif; ?>"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/details/inc/attributes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
            <?php $this->assign('blFirstTab', false); ?>
        <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabsContent', ob_get_contents());ob_end_clean(); ?>
    <?php endif; ?>


<?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('bl_showPriceAlarm')): ?>
    
        <?php if ($this->_tpl_vars['oView']->isPriceAlarm() && ! $this->_tpl_vars['oDetailsProduct']->isParentNotBuyable()): ?>
            <?php ob_start(); ?><a href="#pricealarm" data-toggle="tab"><?php echo smarty_function_oxmultilang(array('ident' => 'PRICE_ALERT'), $this);?>
</a><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabs', ob_get_contents());ob_end_clean(); ?>
            <?php ob_start(); ?>
                <div id="pricealarm" class="tab-pane<?php if ($this->_tpl_vars['blFirstTab']): ?> active<?php endif; ?>"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/pricealarm.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
                <?php $this->assign('blFirstTab', false); ?>
            <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabsContent', ob_get_contents());ob_end_clean(); ?>
        <?php endif; ?>
    
<?php endif; ?>





    <?php if ($this->_tpl_vars['oView']->getMediaFiles() || $this->_tpl_vars['oDetailsProduct']->oxarticles__oxfile->value): ?>
        <?php ob_start(); ?><a href="#media" data-toggle="tab"><?php echo smarty_function_oxmultilang(array('ident' => 'MEDIA'), $this);?>
</a><?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabs', ob_get_contents());ob_end_clean(); ?>
        <?php ob_start(); ?>
            <div id="media" class="tab-pane<?php if ($this->_tpl_vars['blFirstTab']): ?> active<?php endif; ?>"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/details/inc/media.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
            <?php $this->assign('blFirstTab', false); ?>
        <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('tabsContent', ob_get_contents());ob_end_clean(); ?>
    <?php endif; ?>









    <?php if ($this->_tpl_vars['tabs']): ?>
        <div class="tabbedWidgetBox clear">
            <ul id="itemTabs" class="nav nav-tabs">
                <?php $_from = $this->_tpl_vars['tabs']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['tabs'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tabs']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['tab']):
        $this->_foreach['tabs']['iteration']++;
?>
                    <li<?php if (($this->_foreach['tabs']['iteration'] <= 1)): ?> class="active"<?php endif; ?>><?php echo $this->_tpl_vars['tab']; ?>
</li>
                <?php endforeach; endif; unset($_from); ?>
                
            </ul>
            <div class="tab-content">
                <?php $_from = $this->_tpl_vars['tabsContent']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['tabsContent'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tabsContent']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['tabContent']):
        $this->_foreach['tabsContent']['iteration']++;
?>
                    <?php echo $this->_tpl_vars['tabContent']; ?>

                <?php endforeach; endif; unset($_from); ?>
                
            </div>
        </div>
    <?php endif; ?>

