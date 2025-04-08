<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:53
         compiled from widget/header/minibasket.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxgetseourl', 'widget/header/minibasket.tpl', 2, false),array('function', 'oxid_include_dynamic', 'widget/header/minibasket.tpl', 18, false),array('modifier', 'cat', 'widget/header/minibasket.tpl', 2, false),)), $this); ?>
<div class="btn-group minibasket-menu">
    <button type="button" aria-label="Minibasket" class="btn dropdown-toggle" data-toggle="dropdown" data-href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=basket") : smarty_modifier_cat($_tmp, "cl=basket"))), $this);?>
">
        
            <i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
            <?php if ($this->_tpl_vars['oxcmp_basket']->getItemsCount() > 0): ?>
                <span class="badge">
                    <?php echo $this->_tpl_vars['oxcmp_basket']->getItemsCount(); ?>

                </span>
            <?php endif; ?>
        
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
        
            <li>
                <div class="row">
                    <div class="col-12 ml-auto">
                        <div class="minibasket-menu-box">
                            <?php echo smarty_function_oxid_include_dynamic(array('file' => "widget/minibasket/minibasket.tpl"), $this);?>

                        </div>
                    </div>
                </div>
            </li>
        
    </ul>
</div>