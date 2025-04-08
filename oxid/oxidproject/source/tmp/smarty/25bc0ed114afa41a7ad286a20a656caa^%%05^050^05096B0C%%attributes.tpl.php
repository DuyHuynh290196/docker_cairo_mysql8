<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from page/details/inc/attributes.tpl */ ?>
<dl class="attributes dl-horizontal">
    <?php $_from = $this->_tpl_vars['oView']->getAttributes(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['attribute'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['attribute']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oAttr']):
        $this->_foreach['attribute']['iteration']++;
?>
        <dt id="attrTitle_<?php echo $this->_foreach['attribute']['iteration']; ?>
" class="hasTooltip" title="<?php echo $this->_tpl_vars['oAttr']->title; ?>
" data-placement="right" ><?php echo $this->_tpl_vars['oAttr']->title; ?>
</dt>
        <dd id="attrValue_<?php echo $this->_foreach['attribute']['iteration']; ?>
"><?php echo $this->_tpl_vars['oAttr']->value; ?>
</dd>
    <?php endforeach; endif; unset($_from); ?>
</dl>