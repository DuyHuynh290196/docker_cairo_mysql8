<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:57
         compiled from page/list/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'page/list/list.tpl', 10, false),array('function', 'oxeval', 'page/list/list.tpl', 49, false),array('modifier', 'count', 'page/list/list.tpl', 125, false),array('insert', 'oxid_tracker', 'page/list/list.tpl', 142, false),)), $this); ?>
<?php $this->assign('actCategory', $this->_tpl_vars['oView']->getActiveCategory()); ?>

<?php ob_start(); ?>
    <?php $this->assign('listType', $this->_tpl_vars['oView']->getListType()); ?>
    <?php if ($this->_tpl_vars['listType'] == 'manufacturer' || $this->_tpl_vars['listType'] == 'vendor'): ?>
        <?php if ($this->_tpl_vars['actCategory'] && $this->_tpl_vars['actCategory']->getIconUrl()): ?>
        <div class="box">
            <h3>
                <?php if ($this->_tpl_vars['listType'] == 'manufacturer'): ?>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'BRAND'), $this);?>

                <?php elseif ($this->_tpl_vars['listType'] == 'vendor'): ?>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'VENDOR'), $this);?>

                <?php endif; ?>
            </h3>
            <div class="featured icon">
                <img src="<?php echo $this->_tpl_vars['oViewConf']->getImageUrl('spinner.gif'); ?>
" data-src="<?php echo $this->_tpl_vars['actCategory']->getIconUrl(); ?>
" alt="<?php echo $this->_tpl_vars['actCategory']->getTitle(); ?>
">
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_sidebar', ob_get_contents());ob_end_clean(); ?>

<?php ob_start(); ?>
    
        <?php if ($this->_tpl_vars['actCategory']->oxcategories__oxthumb->value && $this->_tpl_vars['actCategory']->getThumbUrl()): ?>
            <img src="<?php echo $this->_tpl_vars['oViewConf']->getImageUrl('spinner.gif'); ?>
" data-src="<?php echo $this->_tpl_vars['actCategory']->getThumbUrl(); ?>
" alt="<?php echo $this->_tpl_vars['actCategory']->oxcategories__oxtitle->value; ?>
" class="categoryPicture img-fluid img-thumbnail">
        <?php endif; ?>

        <div class="page-header">
            <?php $this->assign('rsslinks', $this->_tpl_vars['oView']->getRssLinks()); ?>
            <h1 class="h1">
                <?php echo $this->_tpl_vars['oView']->getTitle(); ?>

                <?php if ($this->_tpl_vars['rsslinks']['activeCategory']): ?>
                    <a class="rss" id="rssActiveCategory" aria-label="RSS Current Category" href="<?php echo $this->_tpl_vars['rsslinks']['activeCategory']['link']; ?>
" title="<?php echo $this->_tpl_vars['rsslinks']['activeCategory']['title']; ?>
" target="_blank">
                        <i class="fas fa-rss"></i>
                    </a>
                <?php endif; ?>
            </h1>
        </div>

        <?php $this->assign('oPageNavigation', $this->_tpl_vars['oView']->getPageNavigation()); ?>
        <?php if ($this->_tpl_vars['actCategory'] && $this->_tpl_vars['actCategory']->getShortDescription() && $this->_tpl_vars['oPageNavigation']->actPage == 1): ?>
            <p id="catDescLocator" class="categoryDescription">
                <em><?php echo $this->_tpl_vars['actCategory']->oxcategories__oxdesc->rawValue; ?>
</em>
            </p>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['actCategory']->oxcategories__oxlongdesc->value && $this->_tpl_vars['oPageNavigation']->actPage == 1): ?>
            <div id="catLongDescLocator" class="categoryDescription"><?php echo smarty_function_oxeval(array('var' => $this->_tpl_vars['actCategory']->oxcategories__oxlongdesc), $this);?>
</div>
            <hr/>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['oView']->hasVisibleSubCats()): ?>
            <?php $this->assign('iSubCategoriesCount', 0); ?>
            <div class="subcatList">
                <div class="row">
                    <?php $_from = $this->_tpl_vars['oView']->getSubCatList(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['MoreSubCat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['MoreSubCat']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['category']):
        $this->_foreach['MoreSubCat']['iteration']++;
?>
                        <?php if ($this->_tpl_vars['category']->getContentCats()): ?>
                            <?php $_from = $this->_tpl_vars['category']->getContentCats(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['MoreCms'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['MoreCms']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['ocont']):
        $this->_foreach['MoreCms']['iteration']++;
?>
                                <?php $this->assign('iSubCategoriesCount', $this->_tpl_vars['iSubCategoriesCount']+1); ?>
                                <div class="box">
                                    <h3>
                                        <a id="moreSubCms_<?php echo $this->_foreach['MoreSubCat']['iteration']; ?>
_<?php echo $this->_foreach['MoreCms']['iteration']; ?>
" href="<?php echo $this->_tpl_vars['ocont']->getLink(); ?>
"><?php echo $this->_tpl_vars['ocont']->oxcontents__oxtitle->value; ?>
</a>
                                    </h3>
                                    <ul class="content"></ul>
                                </div>
                            <?php endforeach; endif; unset($_from); ?>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['iSubCategoriesCount']%4 == 0 && $this->_tpl_vars['iSubCategoriesCount'] != 0): ?>
                            </div><div class="row">
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['category']->getIsVisible()): ?>
                            <?php $this->assign('iSubCategoriesCount', $this->_tpl_vars['iSubCategoriesCount']+1); ?>
                            <?php $this->assign('iconUrl', $this->_tpl_vars['category']->getIconUrl()); ?>
                            <div class="col-12 col-sm-3">
                                <div class="card-wrapper">
                                    <div class="card">
                                        <div class="card-header">
                                            <a id="moreSubCat_<?php echo $this->_foreach['MoreSubCat']['iteration']; ?>
" href="<?php echo $this->_tpl_vars['category']->getLink(); ?>
"><?php echo $this->_tpl_vars['category']->oxcategories__oxtitle->value; ?>
</a><?php if ($this->_tpl_vars['oView']->showCategoryArticlesCount() && ( $this->_tpl_vars['category']->getNrOfArticles() > 0 )): ?> (<?php echo $this->_tpl_vars['category']->getNrOfArticles(); ?>
)<?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <?php if ($this->_tpl_vars['iconUrl']): ?>
                                                <a href="<?php echo $this->_tpl_vars['category']->getLink(); ?>
">
                                                    <img src="<?php echo $this->_tpl_vars['oViewConf']->getImageUrl('spinner.gif'); ?>
" data-src="<?php echo $this->_tpl_vars['category']->getIconUrl(); ?>
" alt="<?php echo $this->_tpl_vars['category']->oxcategories__oxtitle->value; ?>
" class="img-fluid subcat-icon">
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo $this->_tpl_vars['category']->getLink(); ?>
" class="btn btn-block btn-info"><?php echo smarty_function_oxmultilang(array('ident' => 'DD_LIST_SHOW_MORE','suffix' => 'ELLIPSIS'), $this);?>
</a>
                                            <?php endif; ?>

                                            <?php if ($this->_tpl_vars['category']->getHasVisibleSubCats()): ?>
                                                <hr/>
                                                <ul class="list-unstyled">
                                                    <?php $_from = $this->_tpl_vars['category']->getSubCats(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subcategory']):
?>
                                                        <?php if ($this->_tpl_vars['subcategory']->getIsVisible()): ?>
                                                            <?php $_from = $this->_tpl_vars['subcategory']->getContentCats(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['MoreCms'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['MoreCms']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['ocont']):
        $this->_foreach['MoreCms']['iteration']++;
?>
                                                                <li>
                                                                    <a href="<?php echo $this->_tpl_vars['ocont']->getLink(); ?>
"><strong><?php echo $this->_tpl_vars['ocont']->oxcontents__oxtitle->value; ?>
</strong></a>
                                                                </li>
                                                            <?php endforeach; endif; unset($_from); ?>
                                                            <li>
                                                                <a href="<?php echo $this->_tpl_vars['subcategory']->getLink(); ?>
"><?php echo $this->_tpl_vars['subcategory']->oxcategories__oxtitle->value; ?>
</a><?php if ($this->_tpl_vars['oView']->showCategoryArticlesCount() && ( $this->_tpl_vars['subcategory']->getNrOfArticles() > 0 )): ?>&nbsp;(<?php echo $this->_tpl_vars['subcategory']->getNrOfArticles(); ?>
)<?php endif; ?>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; endif; unset($_from); ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['iSubCategoriesCount']%4 == 0): ?>
                            </div>
                            <div class="row">
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?>
                </div>
            </div>
            <hr/>
        <?php endif; ?>
    

    
        <?php if ($this->_tpl_vars['oView']->getArticleList()): ?>
            <?php if (((is_array($_tmp=$this->_tpl_vars['oView']->getArticleList())) ? $this->_run_mod_handler('count', true, $_tmp) : count($_tmp))): ?>
                
                    <div class="listRefine">
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/locator/listlocator.tpl", 'smarty_include_vars' => array('locator' => $this->_tpl_vars['oView']->getPageNavigationLimitedTop(),'attributes' => $this->_tpl_vars['oView']->getAttributes(),'listDisplayType' => true,'itemsPerPage' => true,'sort' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </div>
                

                                
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/list.tpl", 'smarty_include_vars' => array('type' => $this->_tpl_vars['oView']->getListDisplayType(),'listId' => 'productList','products' => $this->_tpl_vars['oView']->getArticleList())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                
                
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/locator/listlocator.tpl", 'smarty_include_vars' => array('locator' => $this->_tpl_vars['oView']->getPageNavigationLimitedBottom(),'place' => 'bottom')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                
            <?php endif; ?>
        <?php endif; ?>
    
    <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'oxid_tracker')), $this); ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->append('oxidBlock_content', ob_get_contents());ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "layout/page.tpl", 'smarty_include_vars' => array('tree_path' => $this->_tpl_vars['oView']->getTreePath())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>