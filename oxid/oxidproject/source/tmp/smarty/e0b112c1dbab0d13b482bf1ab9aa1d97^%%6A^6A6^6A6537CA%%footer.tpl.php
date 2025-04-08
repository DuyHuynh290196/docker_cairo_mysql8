<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:53
         compiled from layout/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'layout/footer.tpl', 18, false),array('function', 'oxid_include_widget', 'layout/footer.tpl', 21, false),array('block', 'oxifcontent', 'layout/footer.tpl', 147, false),)), $this); ?>

    <?php $this->assign('blShowFullFooter', $this->_tpl_vars['oView']->showSearch()); ?>
    <?php $this->assign('blFullwidth', $this->_tpl_vars['oViewConf']->getViewThemeParam('blFullwidthLayout')); ?>
    <?php echo $this->_tpl_vars['oView']->setShowNewsletter($this->_tpl_vars['oViewConf']->getViewThemeParam('blFooterShowNewsletterForm')); ?>


    <?php if ($this->_tpl_vars['oxcmp_user']): ?>
    <?php $this->assign('force_sid', $this->_tpl_vars['oView']->getSidForWidget()); ?>
    <?php endif; ?>

    <footer class="footer" id="footer">
        <div class="<?php if ($this->_tpl_vars['blFullwidth']): ?>container<?php else: ?>container-fluid<?php endif; ?>">
            <div class="row mb-4">
                <div class="col-12 col-lg-8">
                    <div class="row">
                        
                        <section
                                class="col-12 <?php if ($this->_tpl_vars['blShowFullFooter']): ?>col-md-6 col-lg-3<?php else: ?>col-lg-6<?php endif; ?> footer-box footer-box-service">
                            <div class="h4 footer-box-title"><?php echo smarty_function_oxmultilang(array('ident' => 'SERVICES'), $this);?>
</div>
                            <div class="footer-box-content">
                                
                                <?php echo smarty_function_oxid_include_widget(array('cl' => 'oxwServiceList','noscript' => 1,'nocookie' => 1,'force_sid' => $this->_tpl_vars['force_sid']), $this);?>

                                
                            </div>
                        </section>
                        
                        
                        <section
                                class="col-12 <?php if ($this->_tpl_vars['blShowFullFooter']): ?>col-md-6 col-lg-3<?php else: ?>col-lg-6<?php endif; ?> footer-box footer-box-information">
                            <div class="h4 footer-box-title"><?php echo smarty_function_oxmultilang(array('ident' => 'INFORMATION'), $this);?>
</div>
                            <div class="footer-box-content">
                                
                                <?php echo smarty_function_oxid_include_widget(array('cl' => 'oxwInformation','noscript' => 1,'nocookie' => 1,'force_sid' => $this->_tpl_vars['force_sid']), $this);?>

                                
                            </div>
                        </section>
                        
                        <?php if ($this->_tpl_vars['blShowFullFooter']): ?>
                        
                        <section class="col-12 col-md-6 col-lg-3 footer-box footer-box-manufacturers">
                            <div class="h4 footer-box-title"><?php echo smarty_function_oxmultilang(array('ident' => 'OUR_BRANDS'), $this);?>
</div>
                            <div class="footer-box-content">
                                
                                <?php echo smarty_function_oxid_include_widget(array('cl' => 'oxwManufacturerList','_parent' => $this->_tpl_vars['oView']->getClassName(),'noscript' => 1,'nocookie' => 1), $this);?>

                                
                            </div>
                        </section>
                        
                        
                        <section class="col-12 col-md-6 col-lg-3 footer-box footer-box-categories">
                            <div class="h4 footer-box-title"><?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORIES'), $this);?>
</div>
                            <div class="footer-box-content">
                                
                                <?php echo smarty_function_oxid_include_widget(array('cl' => 'oxwCategoryTree','_parent' => $this->_tpl_vars['oView']->getClassName(),'sWidgetType' => 'footer','noscript' => 1,'nocookie' => 1), $this);?>

                                
                            </div>
                        </section>
                        
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 mx-auto mx-lg-0">
                            <?php if ($this->_tpl_vars['oView']->showNewsletter()): ?>
                            <section class="footer-box footer-box-newsletter">
                                <div class="h4 footer-box-title"><?php echo smarty_function_oxmultilang(array('ident' => 'NEWSLETTER'), $this);?>
</div>
                                <div class="footer-box-content">
                                    
                                    <p class="small"><?php echo smarty_function_oxmultilang(array('ident' => 'FOOTER_NEWSLETTER_INFO'), $this);?>
</p>
                                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/footer/newsletter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                    
                                </div>
                            </section>
                            <?php endif; ?>

                            

                        </div>
                    </div>
                </div>
            </div>

                        
                <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('sFacebookUrl') || $this->_tpl_vars['oViewConf']->getViewThemeParam('sGooglePlusUrl') || $this->_tpl_vars['oViewConf']->getViewThemeParam('sTwitterUrl') || $this->_tpl_vars['oViewConf']->getViewThemeParam('sYouTubeUrl') || $this->_tpl_vars['oViewConf']->getViewThemeParam('sBlogUrl')): ?>
                    <div class="social-links">
                    
                    <ul class="social-links-list ">
                        
                        <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('sFacebookUrl')): ?>
                        <li class="social-links-item">
                            <a target="_blank" class="social-links-link"
                               rel="noopener"
                               href="<?php echo $this->_tpl_vars['oViewConf']->getViewThemeParam('sFacebookUrl'); ?>
">
                                <i class="fab fa-facebook-f"></i> <span>Facebook</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('sGooglePlusUrl')): ?>
                        <li class="social-links-item">
                            <a target="_blank" class="social-links-link"
                               rel="noopener"
                               href="<?php echo $this->_tpl_vars['oViewConf']->getViewThemeParam('sGooglePlusUrl'); ?>
">
                                <i class="fab fa-google-plus-square"></i> <span>Google+</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('sTwitterUrl')): ?>
                        <li class="social-links-item">
                            <a target="_blank" class="social-links-link"
                               rel="noopener"
                               href="<?php echo $this->_tpl_vars['oViewConf']->getViewThemeParam('sTwitterUrl'); ?>
">
                                <i class="fab fa-twitter"></i> <span>Twitter</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('sYouTubeUrl')): ?>
                        <li class="social-links-item">
                            <a target="_blank" class="social-links-link"
                               rel="noopener"
                               href="<?php echo $this->_tpl_vars['oViewConf']->getViewThemeParam('sYouTubeUrl'); ?>
">
                                <i class="fab fa-youtube"></i> <span>YouTube</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('sBlogUrl')): ?>
                        <li class="social-links-item">
                            <a target="_blank" class="social-links-link"
                               rel="noopener"
                               href="<?php echo $this->_tpl_vars['oViewConf']->getViewThemeParam('sBlogUrl'); ?>
">
                                <i class="fab fa-wordpress"></i> <span>Blog</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                    </ul>
                    
                </div>
                <?php endif; ?>
            
                    </div>

        <?php if ($this->_tpl_vars['oView']->isPriceCalculated()): ?>
        
        
        <?php $this->_tag_stack[] = array('oxifcontent', array('ident' => 'oxdeliveryinfo','object' => 'oCont')); $_block_repeat=true;smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <div class="<?php if ($this->_tpl_vars['blFullwidth']): ?>container<?php else: ?>container-fluid<?php endif; ?>">
            <div class="vat-info">
                <?php if ($this->_tpl_vars['oView']->isVatIncluded()): ?>
                <span class="vat-info-text">* <?php echo smarty_function_oxmultilang(array('ident' => 'PLUS_SHIPPING'), $this);?>
<a
                        href="<?php echo $this->_tpl_vars['oCont']->getLink(); ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'PLUS_SHIPPING2'), $this);?>
</a></span>
                <?php else: ?>
                <span class="vat-info-text">* <?php echo smarty_function_oxmultilang(array('ident' => 'PLUS'), $this);?>
<a
                        href="<?php echo $this->_tpl_vars['oCont']->getLink(); ?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'PLUS_SHIPPING2'), $this);?>
</a></span>
                <?php endif; ?>
            </div>
        </div>

        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        
        
        <?php endif; ?>
    </footer>

    <?php $this->_tag_stack[] = array('oxifcontent', array('ident' => 'oxstdfooter','object' => 'oCont')); $_block_repeat=true;smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        <div class="legal">
            <div class="<?php if ($this->_tpl_vars['blFullwidth']): ?>container<?php else: ?>container-fluid<?php endif; ?>">
                <section class="legal-box">
                    
                        <?php echo $this->_tpl_vars['oCont']->oxcontents__oxcontent->value; ?>

                    
                </section>
            </div>
        </div>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    

    <?php if ($this->_tpl_vars['oView']->isRootCatChanged()): ?>
        <div id="scRootCatChanged" class="popupBox corners FXgradGreyLight glowShadow" style="display: none;">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/privatesales/basketexcl.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
    <?php endif; ?>