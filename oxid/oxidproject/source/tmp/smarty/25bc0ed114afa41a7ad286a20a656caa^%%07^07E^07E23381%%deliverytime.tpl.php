<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from page/details/inc/deliverytime.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'page/details/inc/deliverytime.tpl', 3, false),array('modifier', 'cat', 'page/details/inc/deliverytime.tpl', 11, false),)), $this); ?>
<?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxmindeltime->value || $this->_tpl_vars['oDetailsProduct']->oxarticles__oxmaxdeltime->value): ?>
	<span class="deliverytime">
		<?php echo smarty_function_oxmultilang(array('ident' => 'DELIVERYTIME_DELIVERYTIME','suffix' => 'COLON'), $this);?>

		<?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxmindeltime->value && $this->_tpl_vars['oDetailsProduct']->oxarticles__oxmindeltime->value != $this->_tpl_vars['oDetailsProduct']->oxarticles__oxmaxdeltime->value): ?>
			<?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxmindeltime->value; ?>
 -
		<?php endif; ?>
		<?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxmaxdeltime->value): ?>
			<?php $this->assign('unit', $this->_tpl_vars['oDetailsProduct']->oxarticles__oxdeltimeunit->value); ?>
			<?php $this->assign('ident', $this->_tpl_vars['unit']); ?>
			<?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxmaxdeltime->value > 1): ?>
				<?php $this->assign('ident', ((is_array($_tmp=$this->_tpl_vars['ident'])) ? $this->_run_mod_handler('cat', true, $_tmp, 'S') : smarty_modifier_cat($_tmp, 'S'))); ?>
			<?php endif; ?>
			<?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxmaxdeltime->value; ?>
 <?php echo smarty_function_oxmultilang(array('ident' => $this->_tpl_vars['ident']), $this);?>

		<?php endif; ?>
	</span>
<?php endif; ?>