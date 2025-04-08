<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:57
         compiled from widget/locator/paging.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/locator/paging.tpl', 7, false),)), $this); ?>

    <?php if ($this->_tpl_vars['pages']->changePage): ?>
    <ul class="pagination pagination-sm<?php if ($this->_tpl_vars['place'] == 'bottom'): ?> lineBox justify-content-center<?php endif; ?>" id="itemsPager<?php echo $this->_tpl_vars['place']; ?>
">

        <li class="page-item <?php if (! $this->_tpl_vars['pages']->previousPage): ?> disabled<?php endif; ?>">
            <?php if ($this->_tpl_vars['pages']->previousPage): ?>
            <a class="page-link" href="<?php echo $this->_tpl_vars['pages']->previousPage; ?>
" aria-label="<?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS'), $this);?>
">
                <span aria-hidden="true"><i class="fa fa-angle-left"></i>&nbsp; <?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS'), $this);?>
</span>
                <span class="sr-only"><?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS'), $this);?>
</span>
            </a>
            <?php else: ?>
            <span class="page-link"><i class="fa fa-angle-left"></i>&nbsp; <?php echo smarty_function_oxmultilang(array('ident' => 'PREVIOUS'), $this);?>
</span>
            <?php endif; ?>
        </li>

        <?php $this->assign('i', 1); ?>
        <?php $_from = $this->_tpl_vars['pages']->changePage; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iPage'] => $this->_tpl_vars['page']):
?>
        <?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['i']): ?>
        <li class="page-item <?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['pages']->actPage): ?> active<?php endif; ?>">
            <a href="<?php echo $this->_tpl_vars['page']->url; ?>
" class="page-link"><?php echo $this->_tpl_vars['iPage']; ?>
</a>
        </li>
        <?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
        <?php elseif ($this->_tpl_vars['iPage'] > $this->_tpl_vars['i']): ?>
        <li class="page-item disabled">
            <span>...</span>
        </li>
        <li class="page-item <?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['pages']->actPage): ?> active<?php endif; ?>">
            <a href="<?php echo $this->_tpl_vars['page']->url; ?>
" class="page-link"><?php echo $this->_tpl_vars['iPage']; ?>
</a>
        </li>
        <?php $this->assign('i', $this->_tpl_vars['iPage']+1); ?>
        <?php elseif ($this->_tpl_vars['iPage'] < $this->_tpl_vars['i']): ?>
        <li class="page-item <?php if ($this->_tpl_vars['iPage'] == $this->_tpl_vars['pages']->actPage): ?> active<?php endif; ?>">
            <a href="<?php echo $this->_tpl_vars['page']->url; ?>
" class="page-link"><?php echo $this->_tpl_vars['iPage']; ?>
</a>
        </li>
        <li class="page-item disabled">
            <span>...</span>
        </li>
        <?php $this->assign('i', $this->_tpl_vars['iPage']+1); ?>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>

        <li class="page-item <?php if (! $this->_tpl_vars['pages']->nextPage): ?> disabled<?php endif; ?>">
            <?php if ($this->_tpl_vars['pages']->nextPage): ?>
                <a class="page-link" href="<?php echo $this->_tpl_vars['pages']->nextPage; ?>
" aria-label="<?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
">
                    <span aria-hidden="true"><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
 &nbsp;<i class="fa fa-angle-right"></i></span>
                    <span class="sr-only"><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
</span>
                </a>
            <?php else: ?>
                <span class="page-link"><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
 &nbsp;<i class="fa fa-angle-right"></i></span>
            <?php endif; ?>
        </li>
    </ul>
    <?php endif; ?>