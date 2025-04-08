<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:15
         compiled from widget/minibasket/countdown.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/minibasket/countdown.tpl', 4, false),array('function', 'counter', 'widget/minibasket/countdown.tpl', 5, false),array('modifier', 'oxformattime', 'widget/minibasket/countdown.tpl', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['oViewConf']->getShowBasketTimeout()): ?>
    <p class="totals">
        <span class="item">
            <?php echo smarty_function_oxmultilang(array('ident' => 'EXPIRES_IN'), $this);?>

            <?php echo smarty_function_counter(array('name' => 'mini_basket_countdown_nr','assign' => 'countdown_nr'), $this);?>

        </span>
        <strong class="price" id="countdown"><?php echo ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getBasketTimeLeft())) ? $this->_run_mod_handler('oxformattime', true, $_tmp) : smarty_modifier_oxformattime($_tmp)); ?>
</strong>
    </p>
    <hr>
<?php endif; ?>