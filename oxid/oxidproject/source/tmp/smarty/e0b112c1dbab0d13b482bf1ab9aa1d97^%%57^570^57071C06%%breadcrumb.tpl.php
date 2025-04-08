<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:57
         compiled from widget/breadcrumb.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/breadcrumb.tpl', 6, false),array('modifier', 'escape', 'widget/breadcrumb.tpl', 10, false),)), $this); ?>

    <?php echo '<ol id="breadcrumb" class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList"><li class="text-muted">'; ?><?php echo smarty_function_oxmultilang(array('ident' => 'YOU_ARE_HERE'), $this);?><?php echo ':</li>'; ?><?php $_from = $this->_tpl_vars['oView']->getBreadCrumb(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['breadcrumb'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['breadcrumb']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sCrum']):
        $this->_foreach['breadcrumb']['iteration']++;
?><?php echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item'; ?><?php if (($this->_foreach['breadcrumb']['iteration'] == $this->_foreach['breadcrumb']['total'])): ?><?php echo ' active'; ?><?php endif; ?><?php echo '"><a href="'; ?><?php if ($this->_tpl_vars['sCrum']['link']): ?><?php echo ''; ?><?php echo $this->_tpl_vars['sCrum']['link']; ?><?php echo ''; ?><?php else: ?><?php echo '#'; ?><?php endif; ?><?php echo '" class="breadcrumb-link" title="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sCrum']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?><?php echo '" itemprop="item"><span itemprop="name">'; ?><?php echo $this->_tpl_vars['sCrum']['title']; ?><?php echo '</span></a><meta itemprop="position" content="'; ?><?php echo $this->_foreach['breadcrumb']['iteration']; ?><?php echo '" /></li>'; ?><?php endforeach; endif; unset($_from); ?><?php echo '</ol>'; ?>

