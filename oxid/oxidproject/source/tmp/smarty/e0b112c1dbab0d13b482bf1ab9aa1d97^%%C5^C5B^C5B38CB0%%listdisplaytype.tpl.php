<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:57
         compiled from widget/locator/listdisplaytype.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/locator/listdisplaytype.tpl', 8, false),array('modifier', 'oxaddparams', 'widget/locator/listdisplaytype.tpl', 11, false),)), $this); ?>
<?php $this->assign('listType', $this->_tpl_vars['oView']->getListDisplayType()); ?>
<?php $this->assign('_additionalParams', $this->_tpl_vars['oView']->getAdditionalParams()); ?>
<?php $this->assign('_artPerPage', $this->_tpl_vars['oViewConf']->getArtPerPageCount()); ?>

<?php if ($this->_tpl_vars['oView']->canSelectDisplayType()): ?>
    <div class="btn-group d-none d-md-inline-flex">
        <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-toggle="dropdown">
            <strong><?php echo smarty_function_oxmultilang(array('ident' => 'LIST_DISPLAY_TYPE'), $this);?>
</strong> <?php echo smarty_function_oxmultilang(array('ident' => $this->_tpl_vars['listType']), $this);?>
 <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li class="filter-item<?php if ($this->_tpl_vars['listType'] == 'infogrid'): ?> selected<?php endif; ?>"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['oView']->getLink())) ? $this->_run_mod_handler('oxaddparams', true, $_tmp, "ldtype=infogrid&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams'])) : smarty_modifier_oxaddparams($_tmp, "ldtype=infogrid&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams']))); ?>
" class="filter-link<?php if ($this->_tpl_vars['listType'] == 'infogrid'): ?> selected<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'infogrid'), $this);?>
</a></li>
            <li class="filter-item<?php if ($this->_tpl_vars['listType'] == 'grid'): ?> selected<?php endif; ?>"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['oView']->getLink())) ? $this->_run_mod_handler('oxaddparams', true, $_tmp, "ldtype=grid&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams'])) : smarty_modifier_oxaddparams($_tmp, "ldtype=grid&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams']))); ?>
" class="filter-link<?php if ($this->_tpl_vars['listType'] == 'grid'): ?> selected<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'grid'), $this);?>
</a></li>
            <li class="filter-item<?php if ($this->_tpl_vars['listType'] == 'line'): ?> selected<?php endif; ?>"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['oView']->getLink())) ? $this->_run_mod_handler('oxaddparams', true, $_tmp, "ldtype=line&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams'])) : smarty_modifier_oxaddparams($_tmp, "ldtype=line&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams']))); ?>
" class="filter-link<?php if ($this->_tpl_vars['listType'] == 'line'): ?> selected<?php endif; ?>"><?php echo smarty_function_oxmultilang(array('ident' => 'line'), $this);?>
</a></li>
        </ul>
    </div>
<?php endif; ?>