<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:07
         compiled from widget/locator/itemsperpage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/locator/itemsperpage.tpl', 6, false),array('modifier', 'oxaddparams', 'widget/locator/itemsperpage.tpl', 17, false),)), $this); ?>
<?php $this->assign('_additionalParams', $this->_tpl_vars['oView']->getAdditionalParams()); ?>
<?php $this->assign('listType', $this->_tpl_vars['oView']->getListDisplayType()); ?>

<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
        <strong><?php echo smarty_function_oxmultilang(array('ident' => 'PRODUCTS_PER_PAGE'), $this);?>
</strong>
        <?php if ($this->_tpl_vars['oViewConf']->getArtPerPageCount()): ?>
            <?php echo $this->_tpl_vars['oViewConf']->getArtPerPageCount(); ?>

        <?php else: ?>
            <?php echo smarty_function_oxmultilang(array('ident' => 'CHOOSE'), $this);?>

        <?php endif; ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <?php $_from = $this->_tpl_vars['oViewConf']->getNrOfCatArticles(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iItemsPerPage']):
?>
            <li>
                <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['oView']->getLink())) ? $this->_run_mod_handler('oxaddparams', true, $_tmp, "ldtype=".($this->_tpl_vars['listType'])."&amp;_artperpage=".($this->_tpl_vars['iItemsPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams'])) : smarty_modifier_oxaddparams($_tmp, "ldtype=".($this->_tpl_vars['listType'])."&amp;_artperpage=".($this->_tpl_vars['iItemsPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams']))); ?>
" <?php if ($this->_tpl_vars['oViewConf']->getArtPerPageCount() == $this->_tpl_vars['iItemsPerPage']): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['iItemsPerPage']; ?>
</a>
            </li>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>