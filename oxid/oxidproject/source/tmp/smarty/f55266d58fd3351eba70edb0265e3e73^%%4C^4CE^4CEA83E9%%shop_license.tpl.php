<?php /* Smarty version 2.6.33, created on 2025-04-02 05:10:11
         compiled from shop_license.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'shop_license.tpl', 1, false),array('function', 'oxmultilang', 'shop_license.tpl', 26, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['error']): ?><div class="errorbox"><?php echo $this->_tpl_vars['error']; ?>
</div><?php endif; ?>
<?php if ($this->_tpl_vars['message']): ?><div class="messagebox"><?php echo $this->_tpl_vars['message']; ?>
</div><?php endif; ?>

<?php if ($this->_tpl_vars['readonly']): ?>
    <?php $this->assign('readonly', 'readonly disabled'); ?>
<?php else: ?>
    <?php $this->assign('readonly', ""); ?>
<?php endif; ?>

<form name="transfer" id="transfer" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="cl" value="shop_license">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="<?php echo $this->_tpl_vars['oViewConf']->getActiveShopId(); ?>
">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="<?php echo $this->_tpl_vars['editlanguage']; ?>
">
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include/update_views_notice.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table id="tShopLicense" border="0" width="45%">
    <tr>
        <td class="edittext">
            <br><strong><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_LICENSE_VERSION'), $this);?>
</strong>
        </td>
        <td class="edittext">
            <b><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_OXIDESHOP'), $this);?>

               <?php echo $this->_tpl_vars['oView']->getShopEdition(); ?>
 <?php echo $this->_tpl_vars['oView']->getShopVersion(); ?>
_<?php echo $this->_tpl_vars['oView']->getRevision(); ?>

               <?php if ($this->_tpl_vars['oView']->isDemoVersion()): ?>
                   <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_LICENSE_DEMO'), $this);?>

               <?php endif; ?>
            </b>
        </td>
    </tr>
</table>
<table id="tVersionInfo" border="0">
    <tr>
        <td>
            <span><?php echo $this->_tpl_vars['aCurVersionInfo']; ?>
</span>
        </td>
    </tr>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomnaviitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>