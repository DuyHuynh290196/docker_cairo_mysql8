<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:45
         compiled from form/fieldset/salutation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'form/fieldset/salutation.tpl', 6, false),array('modifier', 'lower', 'form/fieldset/salutation.tpl', 7, false),)), $this); ?>
<select name="<?php echo $this->_tpl_vars['name']; ?>
"
        <?php if ($this->_tpl_vars['class']): ?>class="<?php echo $this->_tpl_vars['class']; ?>
"<?php endif; ?>
        <?php if ($this->_tpl_vars['id']): ?>id="<?php echo $this->_tpl_vars['id']; ?>
"<?php endif; ?>
        <?php if ($this->_tpl_vars['required']): ?>required="required"<?php endif; ?>>
    
        <option value="" <?php if (empty ( $this->_tpl_vars['value'] )): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'DD_CONTACT_SELECT_SALUTATION'), $this);?>
</option>
        <option value="MRS" <?php if (((is_array($_tmp=$this->_tpl_vars['value'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'mrs' || ((is_array($_tmp=$this->_tpl_vars['value2'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'mrs'): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'MRS'), $this);?>
</option>
        <option value="MR"  <?php if (((is_array($_tmp=$this->_tpl_vars['value'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'mr' || ((is_array($_tmp=$this->_tpl_vars['value2'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'mr'): ?>SELECTED<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'MR'), $this);?>
</option>
    
</select>