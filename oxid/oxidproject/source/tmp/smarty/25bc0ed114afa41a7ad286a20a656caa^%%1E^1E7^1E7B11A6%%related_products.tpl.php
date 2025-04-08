<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from page/details/inc/related_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'page/details/inc/related_products.tpl', 3, false),)), $this); ?>

    <?php if ($this->_tpl_vars['oView']->getAlsoBoughtTheseProducts()): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => 'grid','listId' => 'alsoBought','head' => ((is_array($_tmp='CUSTOMERS_ALSO_BOUGHT')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'subhead' => ((is_array($_tmp='PAGE_DETAILS_CUSTOMERS_ALSO_BOUGHT_SUBHEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'products' => $this->_tpl_vars['oView']->getAlsoBoughtTheseProducts())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>



    <?php if ($this->_tpl_vars['oView']->getAccessoires()): ?>
        <?php ob_start(); ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => 'grid','listId' => 'accessories','products' => $this->_tpl_vars['oView']->getAccessoires(),'head' => ((is_array($_tmp='ACCESSORIES')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'subhead' => ((is_array($_tmp='WIDGET_PRODUCT_RELATED_PRODUCTS_ACCESSORIES_SUBHEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_productbar', ob_get_contents());ob_end_clean(); ?>
    <?php endif; ?>



    <?php if ($this->_tpl_vars['oView']->getSimilarProducts()): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => 'grid','listId' => 'similar','products' => $this->_tpl_vars['oView']->getSimilarProducts(),'head' => ((is_array($_tmp='SIMILAR_PRODUCTS')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'subhead' => ((is_array($_tmp='WIDGET_PRODUCT_RELATED_PRODUCTS_SIMILAR_SUBHEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>



    <?php if ($this->_tpl_vars['oView']->getCrossSelling()): ?>
        <?php ob_start(); ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => 'grid','listId' => 'cross','products' => $this->_tpl_vars['oView']->getCrossSelling(),'head' => ((is_array($_tmp='HAVE_YOU_SEEN')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'subhead' => ((is_array($_tmp='WIDGET_PRODUCT_RELATED_PRODUCTS_CROSSSELING_SUBHEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_productbar', ob_get_contents());ob_end_clean(); ?>
    <?php endif; ?>


<?php if ($this->_tpl_vars['oxidBlock_productbar']): ?>
    <div id="relProducts" class="relatedProducts">
        <?php $_from = $this->_tpl_vars['oxidBlock_productbar']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_block']):
?>
            <?php echo $this->_tpl_vars['_block']; ?>

        <?php endforeach; endif; unset($_from); ?>
    </div>
<?php endif; ?>