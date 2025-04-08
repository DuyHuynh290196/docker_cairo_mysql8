<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:07
         compiled from widget/locator/paging.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/locator/paging.tpl', 6, false),)), $this); ?>

    <?php if ($this->_tpl_vars['pages']->changePage): ?>
        <ol class="pagination pagination-sm<?php if ($this->_tpl_vars['place'] == 'bottom'): ?> lineBox<?php endif; ?>" id="itemsPager<?php echo $this->_tpl_vars['place']; ?>
">
            <li class="prev<?php if (! $this->_tpl_vars['pages']->previousPage): ?> disabled<?php endif; ?>">
                <?php if ($this->_tpl_vars['pages']->previousPage): ?>
                    <a href="<?php echo $this->_tpl_vars['pages']->previousPage; ?>
">&larr; <?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS'), $this);?>
</a>
                <?php else: ?>
                    <span>&larr; <?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS'), $this);?>
</span>
                <?php endif; ?>
            </li>

            <?php $this->assign('i', 1); ?>
            <?php $_from = $this->_tpl_vars['pages']->changePage; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iPage'] => $this->_tpl_vars['page']):
?>
                <?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['i']): ?>
                    <li<?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['pages']->actPage): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo $this->_tpl_vars['page']->url; ?>
"><?php echo $this->_tpl_vars['iPage']; ?>
</a>
                    </li>
                   <?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
                <?php elseif ($this->_tpl_vars['iPage'] > $this->_tpl_vars['i']): ?>
                    <li class="disabled">
                        <span>...</span>
                    </li>
                    <li<?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['pages']->actPage): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo $this->_tpl_vars['page']->url; ?>
"><?php echo $this->_tpl_vars['iPage']; ?>
</a>
                    </li>
                    <?php $this->assign('i', $this->_tpl_vars['iPage']+1); ?>
                <?php elseif ($this->_tpl_vars['iPage'] < $this->_tpl_vars['i']): ?>
                    <li<?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['pages']->actPage): ?> class="active"<?php endif; ?>>
                        <a href="<?php echo $this->_tpl_vars['page']->url; ?>
"><?php echo $this->_tpl_vars['iPage']; ?>
</a>
                    </li>
                    <li class="disabled">
                        <span>...</span>
                    </li>
                   <?php $this->assign('i', $this->_tpl_vars['iPage']+1); ?>
                <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>

            <li class="next<?php if (! $this->_tpl_vars['pages']->nextPage): ?> disabled<?php endif; ?>">
                <?php if ($this->_tpl_vars['pages']->nextPage): ?>
                    <a href="<?php echo $this->_tpl_vars['pages']->nextPage; ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
 &rarr;</a>
                <?php else: ?>
                    <span><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
 &rarr;</span>
                <?php endif; ?>
            </li>
         </ol>
    <?php endif; ?>