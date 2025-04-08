<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from widget/address/billing_address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/address/billing_address.tpl', 3, false),array('modifier', 'oxmultilangsal', 'widget/address/billing_address.tpl', 4, false),)), $this); ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxcompany->value): ?> <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxcompany->value; ?>
<br> <?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxaddinfo->value): ?> <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxaddinfo->value; ?>
<br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxustid->value): ?> <?php echo smarty_function_oxmultilang(array('ident' => 'VAT_ID_NUMBER'), $this);?>
 <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxustid->value; ?>
<br> <?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxsal->value || $this->_tpl_vars['oxcmp_user']->oxuser__oxfname->value || $this->_tpl_vars['oxcmp_user']->oxuser__oxlname->value): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['oxcmp_user']->oxuser__oxsal->value)) ? $this->_run_mod_handler('oxmultilangsal', true, $_tmp) : smarty_modifier_oxmultilangsal($_tmp)); ?>
&nbsp;<?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxfname->value; ?>
&nbsp;<?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxlname->value; ?>
<br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxstreet->value || $this->_tpl_vars['oxcmp_user']->oxuser__oxstreetnr->value): ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxstreet->value; ?>
&nbsp;<?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxstreetnr->value; ?>
<br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxstateid->value): ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxstateid->value; ?>
 <?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxzip->value || $this->_tpl_vars['oxcmp_user']->oxuser__oxcity->value): ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxzip->value; ?>
&nbsp;<?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxcity->value; ?>
<br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxcountry->value): ?><?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxcountry->value; ?>
<br><br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxusername->value): ?><strong><?php echo smarty_function_oxmultilang(array('ident' => 'EMAIL'), $this);?>
</strong> <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxusername->value; ?>
<br><br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxfon->value): ?><strong><?php echo smarty_function_oxmultilang(array('ident' => 'PHONE'), $this);?>
</strong> <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxfon->value; ?>
<br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxfax->value): ?><strong><?php echo smarty_function_oxmultilang(array('ident' => 'FAX'), $this);?>
</strong> <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxfax->value; ?>
<br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxmobfon->value): ?><strong><?php echo smarty_function_oxmultilang(array('ident' => 'CELLUAR_PHONE'), $this);?>
</strong> <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxmobfon->value; ?>
<br><?php endif; ?>
<?php if ($this->_tpl_vars['oxcmp_user']->oxuser__oxprivfon->value): ?><strong><?php echo smarty_function_oxmultilang(array('ident' => 'PERSONAL_PHONE'), $this);?>
</strong> <?php echo $this->_tpl_vars['oxcmp_user']->oxuser__oxprivfon->value; ?>
<br><?php endif; ?>