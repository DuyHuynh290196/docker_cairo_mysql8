<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:53
         compiled from page/shop/start.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'oxifcontent', 'page/shop/start.tpl', 6, false),array('modifier', 'oxmultilangassign', 'page/shop/start.tpl', 16, false),array('insert', 'oxid_tracker', 'page/shop/start.tpl', 45, false),)), $this); ?>
<?php ob_start(); ?>
    <?php $this->assign('oConfig', $this->_tpl_vars['oViewConf']->getConfig()); ?>
    <?php $this->assign('rsslinks', $this->_tpl_vars['oView']->getRssLinks()); ?>

    
        <?php $this->_tag_stack[] = array('oxifcontent', array('ident' => 'oxstartwelcome','object' => 'oCont')); $_block_repeat=true;smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
            <div class="welcome-teaser"><?php echo $this->_tpl_vars['oCont']->oxcontents__oxcontent->value; ?>
</div>
        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    

    <?php $this->assign('oTopArticles', $this->_tpl_vars['oView']->getTop5ArticleList()); ?>

    
        <?php $this->assign('oBargainArticles', $this->_tpl_vars['oView']->getBargainArticleList()); ?>
        <?php if ($this->_tpl_vars['oBargainArticles'] && $this->_tpl_vars['oBargainArticles']->count()): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => $this->_tpl_vars['oViewConf']->getViewThemeParam('sStartPageListDisplayType'),'head' => ((is_array($_tmp='START_BARGAIN_HEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'subhead' => ((is_array($_tmp='START_BARGAIN_SUBHEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'listId' => 'bargainItems','products' => $this->_tpl_vars['oBargainArticles'],'rsslink' => $this->_tpl_vars['rsslinks']['bargainArticles'],'rssId' => 'rssBargainProducts','showMainLink' => true,'iProductsPerLine' => 4)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
    

    
        <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('bl_showManufacturerSlider')): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/manufacturersslider.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
    

    
        <?php $this->assign('oNewestArticles', $this->_tpl_vars['oView']->getNewestArticles()); ?>
        <?php if ($this->_tpl_vars['oNewestArticles'] && $this->_tpl_vars['oNewestArticles']->count()): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => $this->_tpl_vars['oViewConf']->getViewThemeParam('sStartPageListDisplayType'),'head' => ((is_array($_tmp='START_NEWEST_HEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'subhead' => ((is_array($_tmp='START_NEWEST_SUBHEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'listId' => 'newItems','products' => $this->_tpl_vars['oNewestArticles'],'rsslink' => $this->_tpl_vars['rsslinks']['newestArticles'],'rssId' => 'rssNewestProducts','showMainLink' => true,'iProductsPerLine' => 4)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
    

    <?php if ($this->_tpl_vars['oNewestArticles'] && $this->_tpl_vars['oNewestArticles']->count() && $this->_tpl_vars['oTopArticles'] && $this->_tpl_vars['oTopArticles']->count()): ?>
        <div class="row">
            <hr>
        </div>
    <?php endif; ?>

    
        <?php if ($this->_tpl_vars['oTopArticles'] && $this->_tpl_vars['oTopArticles']->count()): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => $this->_tpl_vars['oViewConf']->getViewThemeParam('sStartPageListDisplayType'),'head' => ((is_array($_tmp='START_TOP_PRODUCTS_HEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'subhead' => ((is_array($_tmp='START_TOP_PRODUCTS_SUBHEADER')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp, $this->_tpl_vars['oTopArticles']->count()) : smarty_modifier_oxmultilangassign($_tmp, $this->_tpl_vars['oTopArticles']->count())),'listId' => 'topBox','products' => $this->_tpl_vars['oTopArticles'],'rsslink' => $this->_tpl_vars['rsslinks']['topArticles'],'rssId' => 'rssTopProducts','showMainLink' => true,'iProductsPerLine' => 4)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
    

    <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'oxid_tracker')), $this); ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_content', ob_get_contents());ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "layout/page.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>