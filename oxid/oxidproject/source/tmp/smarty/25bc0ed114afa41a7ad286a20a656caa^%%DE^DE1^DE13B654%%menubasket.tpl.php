<?php /* Smarty version 2.6.33, created on 2025-04-02 05:02:42
         compiled from widget/header/menubasket.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxgetseourl', 'widget/header/menubasket.tpl', 2, false),array('modifier', 'cat', 'widget/header/menubasket.tpl', 2, false),)), $this); ?>
<li>
    <a href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=basket") : smarty_modifier_cat($_tmp, "cl=basket"))), $this);?>
" rel="nofollow">
        <i class="fa fa-shopping-cart"></i>
        <?php if (isset ( $this->_tpl_vars['oxcmp_basket'] ) && $this->_tpl_vars['oxcmp_basket']->getItemsCount() > 0): ?>
            <span class="badge">
                <?php echo $this->_tpl_vars['oxcmp_basket']->getItemsCount(); ?>

            </span>
        <?php endif; ?>
    </a>
</li>