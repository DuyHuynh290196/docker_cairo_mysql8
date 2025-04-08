<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:57
         compiled from widget/locator/sort.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/locator/sort.tpl', 11, false),array('modifier', 'upper', 'widget/locator/sort.tpl', 13, false),array('modifier', 'oxaddparams', 'widget/locator/sort.tpl', 22, false),)), $this); ?>

    <?php if ($this->_tpl_vars['oView']->showSorting()): ?>
        <?php $this->assign('_listType', $this->_tpl_vars['oView']->getListDisplayType()); ?>
        <?php $this->assign('_additionalParams', $this->_tpl_vars['oView']->getAdditionalParams()); ?>
        <?php $this->assign('_artPerPage', $this->_tpl_vars['oViewConf']->getArtPerPageCount()); ?>
        <?php $this->assign('_sortColumnVarName', $this->_tpl_vars['oView']->getSortOrderByParameterName()); ?>
        <?php $this->assign('_sortDirectionVarName', $this->_tpl_vars['oView']->getSortOrderParameterName()); ?>

        <div class="btn-group">
            <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-toggle="dropdown">
                <strong><?php echo smarty_function_oxmultilang(array('ident' => 'SORT_BY'), $this);?>
:</strong>
                <?php if ($this->_tpl_vars['oView']->getListOrderBy()): ?>
                    <?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oView']->getListOrderBy())) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp))), $this);?>

                <?php else: ?>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'CHOOSE'), $this);?>

                <?php endif; ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <?php $_from = $this->_tpl_vars['oView']->getSortColumns(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sColumnName']):
?>
                    <li class="filter-item desc<?php if ($this->_tpl_vars['oView']->getListOrderDirection() == 'desc' && $this->_tpl_vars['sColumnName'] == $this->_tpl_vars['oView']->getListOrderBy()): ?> active<?php endif; ?>">
                        <a class="filter-link" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['oView']->getLink())) ? $this->_run_mod_handler('oxaddparams', true, $_tmp, "ldtype=".($this->_tpl_vars['_listType'])."&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;".($this->_tpl_vars['_sortColumnVarName'])."=".($this->_tpl_vars['sColumnName'])."&amp;".($this->_tpl_vars['_sortDirectionVarName'])."=desc&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams'])) : smarty_modifier_oxaddparams($_tmp, "ldtype=".($this->_tpl_vars['_listType'])."&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;".($this->_tpl_vars['_sortColumnVarName'])."=".($this->_tpl_vars['sColumnName'])."&amp;".($this->_tpl_vars['_sortDirectionVarName'])."=desc&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams']))); ?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp=$this->_tpl_vars['sColumnName'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp))), $this);?>
 <?php echo smarty_function_oxmultilang(array('ident' => 'DD_SORT_DESC'), $this);?>
">
                            <i class="fa fa-caret-down"></i> <?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp=$this->_tpl_vars['sColumnName'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp))), $this);?>

                        </a>
                    </li>
                    <li class="filter-item asc<?php if ($this->_tpl_vars['oView']->getListOrderDirection() == 'asc' && $this->_tpl_vars['sColumnName'] == $this->_tpl_vars['oView']->getListOrderBy()): ?> active<?php endif; ?>">
                        <a class="filter-link" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['oView']->getLink())) ? $this->_run_mod_handler('oxaddparams', true, $_tmp, "ldtype=".($this->_tpl_vars['_listType'])."&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;".($this->_tpl_vars['_sortColumnVarName'])."=".($this->_tpl_vars['sColumnName'])."&amp;".($this->_tpl_vars['_sortDirectionVarName'])."=asc&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams'])) : smarty_modifier_oxaddparams($_tmp, "ldtype=".($this->_tpl_vars['_listType'])."&amp;_artperpage=".($this->_tpl_vars['_artPerPage'])."&amp;".($this->_tpl_vars['_sortColumnVarName'])."=".($this->_tpl_vars['sColumnName'])."&amp;".($this->_tpl_vars['_sortDirectionVarName'])."=asc&amp;pgNr=0&amp;".($this->_tpl_vars['_additionalParams']))); ?>
" title="<?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp=$this->_tpl_vars['sColumnName'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp))), $this);?>
 <?php echo smarty_function_oxmultilang(array('ident' => 'DD_SORT_ASC'), $this);?>
">
                            <i class="fa fa-caret-up"></i> <?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp=$this->_tpl_vars['sColumnName'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp))), $this);?>

                        </a>
                    </li>
                <?php endforeach; endif; unset($_from); ?>
            </ul>
        </div>
    <?php endif; ?>