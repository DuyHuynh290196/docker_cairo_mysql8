<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:53
         compiled from widget/header/categorylist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'widget/header/categorylist.tpl', 18, false),)), $this); ?>

    <?php if ($this->_tpl_vars['oxcmp_categories']): ?>
        <?php $this->assign('homeSelected', 'false'); ?>
        <?php if ($this->_tpl_vars['oViewConf']->getTopActionClassName() == 'start'): ?>
            <?php $this->assign('homeSelected', 'true'); ?>
        <?php endif; ?>
        <?php $this->assign('oxcmp_categories', $this->_tpl_vars['oxcmp_categories']); ?>
        <?php $this->assign('blFullwidth', $this->_tpl_vars['oViewConf']->getViewThemeParam('blFullwidthLayout')); ?>

        <nav id="mainnav" class="navbar navbar-expand-lg navbar-light<?php if ($this->_tpl_vars['blFullwidth']): ?> fullviewlayout<?php endif; ?>" role="navigation">
            <div class="container">
            
                
                    <div class="navbar-header justify-content-start">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-main-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <span class="d-lg-none"><?php echo smarty_function_oxmultilang(array('ident' => 'DD_ROLES_BEMAIN_UIROOTHEADER'), $this);?>
</span>
                    </div>
                
                <div class="collapse navbar-collapse navbar-main-collapse" id="navbarSupportedContent">
                    <ul id="navigation" class="navbar-nav nav">
                        
                            <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('blHomeLink')): ?>
                                <li class="nav-item<?php if ($this->_tpl_vars['homeSelected'] == 'true'): ?> active<?php endif; ?>">
                                    <a class="nav-link" href="<?php echo $this->_tpl_vars['oViewConf']->getHomeLink(); ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'HOME'), $this);?>
</a>
                                </li>
                            <?php endif; ?>

                            <?php $_from = $this->_tpl_vars['oxcmp_categories']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['root'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['root']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['catkey'] => $this->_tpl_vars['ocat']):
        $this->_foreach['root']['iteration']++;
?>
                                <?php if ($this->_tpl_vars['ocat']->getIsVisible()): ?>
                                    <?php $_from = $this->_tpl_vars['ocat']->getContentCats(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['MoreTopCms'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['MoreTopCms']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oTopCont']):
        $this->_foreach['MoreTopCms']['iteration']++;
?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php if ($this->_tpl_vars['oContent']->oxcontents__oxloadid->value === $this->_tpl_vars['oTopCont']->oxcontents__oxloadid->value): ?> active<?php endif; ?>" href="<?php echo $this->_tpl_vars['oTopCont']->getLink(); ?>
"><?php echo $this->_tpl_vars['oTopCont']->oxcontents__oxtitle->value; ?>
</a>
                                        </li>
                                    <?php endforeach; endif; unset($_from); ?>

                                    <li class="nav-item<?php if ($this->_tpl_vars['homeSelected'] == 'false' && $this->_tpl_vars['ocat']->expanded): ?> active<?php endif; ?><?php if ($this->_tpl_vars['ocat']->getSubCats()): ?> dropdown<?php endif; ?>">
                                        <a class="nav-link" href="<?php echo $this->_tpl_vars['ocat']->getLink(); ?>
"<?php if ($this->_tpl_vars['ocat']->getSubCats()): ?> class="dropdown-toggle" data-toggle="dropdown"<?php endif; ?>>
                                            <?php echo $this->_tpl_vars['ocat']->oxcategories__oxtitle->value; ?>
<?php if ($this->_tpl_vars['ocat']->getSubCats()): ?> <i class="fa fa-angle-down"></i><?php endif; ?>
                                        </a>

                                        <?php if ($this->_tpl_vars['ocat']->getSubCats()): ?>
                                            <ul class="dropdown-menu">
                                                <?php $_from = $this->_tpl_vars['ocat']->getSubCats(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['SubCat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['SubCat']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['subcatkey'] => $this->_tpl_vars['osubcat']):
        $this->_foreach['SubCat']['iteration']++;
?>
                                                    <?php if ($this->_tpl_vars['osubcat']->getIsVisible()): ?>
                                                        <?php $_from = $this->_tpl_vars['osubcat']->getContentCats(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['MoreCms'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['MoreCms']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['ocont']):
        $this->_foreach['MoreCms']['iteration']++;
?>
                                                            <li class="dropdown-item<?php if ($this->_tpl_vars['oViewConf']->getContentId() == $this->_tpl_vars['ocont']->getId()): ?> active<?php endif; ?>">
                                                                <a class="dropdown-link<?php if ($this->_tpl_vars['oViewConf']->getContentId() == $this->_tpl_vars['ocont']->getId()): ?> current<?php endif; ?>" href="<?php echo $this->_tpl_vars['ocont']->getLink(); ?>
"><?php echo $this->_tpl_vars['ocont']->oxcontents__oxtitle->value; ?>
</a>
                                                            </li>
                                                        <?php endforeach; endif; unset($_from); ?>

                                                        <?php if ($this->_tpl_vars['osubcat']->getIsVisible()): ?>
                                                            <li class="dropdown-item<?php if ($this->_tpl_vars['homeSelected'] == 'false' && $this->_tpl_vars['osubcat']->expanded): ?> active<?php endif; ?>">
                                                                <a class="dropdown-link<?php if ($this->_tpl_vars['homeSelected'] == 'false' && $this->_tpl_vars['osubcat']->expanded): ?> current<?php endif; ?>" href="<?php echo $this->_tpl_vars['osubcat']->getLink(); ?>
"><?php echo $this->_tpl_vars['osubcat']->oxcategories__oxtitle->value; ?>
</a>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; endif; unset($_from); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>
                        
                    </ul>

                    <ul class="fixed-header-actions">

                        
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/header/menubasket.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        

                        <li class="fixed-header-item">
                            <a href="javascript:void(null)" class="search-toggle fixed-header-link" rel="nofollow">
                                <i class="fa fa-search"></i>
                            </a>
                        </li>

                    </ul>

                    <?php if ($this->_tpl_vars['oView']->isDemoShop()): ?>

                        <a href="<?php echo $this->_tpl_vars['oViewConf']->getBaseDir(); ?>
admin/" class="btn btn-sm btn-danger navbar-btn navbar-right visible-lg">
                            <?php echo smarty_function_oxmultilang(array('ident' => 'DD_DEMO_ADMIN_TOOL'), $this);?>

                            <i class="fa fa-external-link" style="font-size: 80%;"></i>
                        </a>

                    <?php endif; ?>

                </div>
            
            </div>
        </nav>
    <?php endif; ?>
