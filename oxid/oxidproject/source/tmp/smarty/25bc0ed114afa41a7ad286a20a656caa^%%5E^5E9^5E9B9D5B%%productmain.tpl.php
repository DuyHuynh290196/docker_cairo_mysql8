<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from page/details/inc/productmain.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxscript', 'page/details/inc/productmain.tpl', 1, false),array('function', 'oxmultilang', 'page/details/inc/productmain.tpl', 103, false),array('function', 'oxprice', 'page/details/inc/productmain.tpl', 134, false),array('function', 'oxid_include_dynamic', 'page/details/inc/productmain.tpl', 335, false),array('function', 'oxgetseourl', 'page/details/inc/productmain.tpl', 340, false),array('function', 'mailto', 'page/details/inc/productmain.tpl', 371, false),array('modifier', 'cat', 'page/details/inc/productmain.tpl', 13, false),array('modifier', 'getimagesize', 'page/details/inc/productmain.tpl', 71, false),array('modifier', 'strip_tags', 'page/details/inc/productmain.tpl', 79, false),array('modifier', 'oxmultilangassign', 'page/details/inc/productmain.tpl', 218, false),array('modifier', 'default', 'page/details/inc/productmain.tpl', 371, false),array('block', 'oxhasrights', 'page/details/inc/productmain.tpl', 38, false),)), $this); ?>
<?php echo smarty_function_oxscript(array('include' => "js/pages/details.min.js",'priority' => 10), $this);?>


<?php $this->assign('oConfig', $this->_tpl_vars['oViewConf']->getConfig()); ?>
<?php $this->assign('oManufacturer', $this->_tpl_vars['oView']->getManufacturer()); ?>
<?php $this->assign('aVariantSelections', $this->_tpl_vars['oView']->getVariantSelections()); ?>
<?php $this->assign('blFullwidth', $this->_tpl_vars['oViewConf']->getViewThemeParam('blFullwidthLayout')); ?>

<?php if ($this->_tpl_vars['aVariantSelections'] && $this->_tpl_vars['aVariantSelections']['rawselections']): ?>
    <?php $this->assign('_sSelectionHashCollection', ""); ?>
    <?php $_from = $this->_tpl_vars['aVariantSelections']['rawselections']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iKey'] => $this->_tpl_vars['oSelectionList']):
?>
        <?php $this->assign('_sSelectionHash', ""); ?>
        <?php $_from = $this->_tpl_vars['oSelectionList']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iPos'] => $this->_tpl_vars['oListItem']):
?>
            <?php $this->assign('_sSelectionHash', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_sSelectionHash'])) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['iPos']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['iPos'])))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oListItem']['hash']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oListItem']['hash'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "|") : smarty_modifier_cat($_tmp, "|"))); ?>
        <?php endforeach; endif; unset($_from); ?>
        <?php if ($this->_tpl_vars['_sSelectionHash']): ?>
            <?php if ($this->_tpl_vars['_sSelectionHashCollection']): ?><?php $this->assign('_sSelectionHashCollection', ((is_array($_tmp=$this->_tpl_vars['_sSelectionHashCollection'])) ? $this->_run_mod_handler('cat', true, $_tmp, ",") : smarty_modifier_cat($_tmp, ","))); ?><?php endif; ?>
            <?php $this->assign('_sSelectionHashCollection', ((is_array($_tmp=$this->_tpl_vars['_sSelectionHashCollection'])) ? $this->_run_mod_handler('cat', true, $_tmp, "'".($this->_tpl_vars['_sSelectionHash'])."'") : smarty_modifier_cat($_tmp, "'".($this->_tpl_vars['_sSelectionHash'])."'"))); ?>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    <?php echo smarty_function_oxscript(array('add' => "oxVariantSelections  = [".($this->_tpl_vars['_sSelectionHashCollection'])."];"), $this);?>


    <form class="js-oxWidgetReload" action="<?php echo $this->_tpl_vars['oView']->getWidgetLink(); ?>
" method="get">
        <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

        <?php echo $this->_tpl_vars['oViewConf']->getNavFormParams(); ?>

        <input type="hidden" name="cl" value="<?php echo $this->_tpl_vars['oView']->getClassName(); ?>
">
        <input type="hidden" name="oxwparent" value="<?php echo $this->_tpl_vars['oViewConf']->getTopActiveClassName(); ?>
">
        <input type="hidden" name="listtype" value="<?php echo $this->_tpl_vars['oView']->getListType(); ?>
">
        <input type="hidden" name="nocookie" value="1">
        <input type="hidden" name="cnid" value="<?php echo $this->_tpl_vars['oView']->getCategoryId(); ?>
">
        <input type="hidden" name="anid" value="<?php if (! $this->_tpl_vars['oDetailsProduct']->oxarticles__oxparentid->value): ?><?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxid->value; ?>
<?php else: ?><?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxparentid->value; ?>
<?php endif; ?>">
        <input type="hidden" name="actcontrol" value="<?php echo $this->_tpl_vars['oViewConf']->getTopActiveClassName(); ?>
">
        <?php if ($this->_tpl_vars['oConfig']->getRequestParameter('preview')): ?>
            <input type="hidden" name="preview" value="<?php echo $this->_tpl_vars['oConfig']->getRequestParameter('preview'); ?>
">
        <?php endif; ?>
    </form>
<?php endif; ?>

<?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'TOBASKET')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <form class="js-oxProductForm" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfActionLink(); ?>
" method="post">
        <div class="hidden">
            <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

            <?php echo $this->_tpl_vars['oViewConf']->getNavFormParams(); ?>

            <input type="hidden" name="cl" value="<?php echo $this->_tpl_vars['oViewConf']->getTopActiveClassName(); ?>
">
            <input type="hidden" name="aid" value="<?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxid->value; ?>
">
            <input type="hidden" name="anid" value="<?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value; ?>
">
            <input type="hidden" name="parentid" value="<?php if (! $this->_tpl_vars['oDetailsProduct']->oxarticles__oxparentid->value): ?><?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxid->value; ?>
<?php else: ?><?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxparentid->value; ?>
<?php endif; ?>">
            <input type="hidden" name="panid" value="">
            <?php if (! $this->_tpl_vars['oDetailsProduct']->isNotBuyable()): ?>
                <input type="hidden" name="fnc" value="tobasket">
            <?php endif; ?>
        </div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<div class="detailsInfo clear" itemscope itemtype="http://schema.org/Product">
    <div class="row">
        <div class="col-xs-12 col-md-4 details-col-left">
                        
                <?php echo smarty_function_oxscript(array('include' => "js/libs/photoswipe.min.js",'priority' => 8), $this);?>

                <?php echo smarty_function_oxscript(array('include' => "js/libs/photoswipe-ui-default.min.js",'priority' => 8), $this);?>

                <?php echo smarty_function_oxscript(array('add' => "\$( document ).ready( function() { Flow.initDetailsEvents(); });"), $this);?>


                                <?php if ($this->_tpl_vars['blWorkaroundInclude']): ?>
                    <?php echo smarty_function_oxscript(array('add' => "$( document ).ready( function() { Flow.initEvents();});"), $this);?>

                <?php endif; ?>

                <?php if ($this->_tpl_vars['oView']->showZoomPics()): ?>
                                        <?php if ($this->_tpl_vars['oConfig']->getConfigParam('sAltImageUrl') || $this->_tpl_vars['oConfig']->getConfigParam('sSSLAltImageUrl')): ?>
                        <?php $this->assign('aPictureInfo', getimagesize($this->_tpl_vars['oPictureProduct']->getMasterZoomPictureUrl(1))); ?>
                    <?php else: ?>
                        <?php $this->assign('sPictureName', $this->_tpl_vars['oPictureProduct']->oxarticles__oxpic1->value); ?>
                        <?php $this->assign('aPictureInfo', getimagesize($this->_tpl_vars['oConfig']->getMasterPicturePath("product/1/".($this->_tpl_vars['sPictureName'])))); ?>
                    <?php endif; ?>

                    <div class="picture text-center">
                        <a href="<?php echo $this->_tpl_vars['oPictureProduct']->getMasterZoomPictureUrl(1); ?>
" id="zoom1"<?php if ($this->_tpl_vars['aPictureInfo']): ?> data-width="<?php echo $this->_tpl_vars['aPictureInfo']['0']; ?>
" data-height="<?php echo $this->_tpl_vars['aPictureInfo']['1']; ?>
"<?php endif; ?>>
                            <img src="<?php echo $this->_tpl_vars['oView']->getActPicture(); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['oPictureProduct']->oxarticles__oxtitle->value)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['oPictureProduct']->oxarticles__oxvarselect->value)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
" itemprop="image" class="img-responsive">
                        </a>
                    </div>
                <?php else: ?>
                    <div class="picture  text-center">
                        <img src="<?php echo $this->_tpl_vars['oView']->getActPicture(); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['oPictureProduct']->oxarticles__oxtitle->value)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['oPictureProduct']->oxarticles__oxvarselect->value)) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
" itemprop="image" class="img-responsive">
                    </div>
                <?php endif; ?>
            

            
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/details/inc/morepics.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            
        </div>

        <div class="col-xs-12 col-sm-8 col-md-5 col-lg-6 details-col-middle">
            
                <h1 id="productTitle" itemprop="name">
                    <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxtitle->value; ?>
 <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxvarselect->value; ?>

                </h1>
            

                        
                <span class="small text-muted"><?php echo smarty_function_oxmultilang(array('ident' => 'ARTNUM','suffix' => 'COLON'), $this);?>
 <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxartnum->value; ?>
</span>
            

                        <div class="star-ratings">
                <?php if ($this->_tpl_vars['oView']->ratingIsActive()): ?>
                    
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/reviews/rating.tpl", 'smarty_include_vars' => array('itemid' => "anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value),'sRateUrl' => $this->_tpl_vars['oDetailsProduct']->getLink())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    
                <?php endif; ?>
            </div>

                        
                <?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'SHOWSHORTDESCRIPTION')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
                    <?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxshortdesc->rawValue): ?>
                        <p class="shortdesc" id="productShortdesc" itemprop="description"><?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxshortdesc->rawValue; ?>
</p>
                    <?php endif; ?>
                <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
            

                        <div class="information" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <div class="productMainInfo<?php if ($this->_tpl_vars['oManufacturer']->oxmanufacturers__oxicon->value): ?> hasBrand<?php endif; ?>">

                                        <?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'SHOWARTICLEPRICE')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
                    <div class="additionalInfo clearfix">
                        <?php $this->assign('oUnitPrice', $this->_tpl_vars['oDetailsProduct']->getUnitPrice()); ?>
                        
                            <?php if ($this->_tpl_vars['oUnitPrice']): ?>
                                <span id="productPriceUnit"><?php echo smarty_function_oxprice(array('price' => $this->_tpl_vars['oUnitPrice'],'currency' => $this->_tpl_vars['currency']), $this);?>
/<?php echo $this->_tpl_vars['oDetailsProduct']->getUnitName(); ?>
</span>
                            <?php endif; ?>
                        
                    </div>
                    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

                    <?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxweight->value): ?>
                        <div class="weight">
                            
                                <?php echo smarty_function_oxmultilang(array('ident' => 'WEIGHT','suffix' => 'COLON'), $this);?>
 <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxweight->value; ?>
 <?php echo smarty_function_oxmultilang(array('ident' => 'KG'), $this);?>

                            
                        </div>
                    <?php endif; ?>

                    
                        <?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxvpe->value > 1): ?>
                            <span class="vpe small"><?php echo smarty_function_oxmultilang(array('ident' => 'DETAILS_VPE_MESSAGE_1'), $this);?>
 <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxvpe->value; ?>
 <?php echo smarty_function_oxmultilang(array('ident' => 'DETAILS_VPE_MESSAGE_2'), $this);?>
</span>
                            <br>
                        <?php endif; ?>
                    

                    <?php $this->assign('blCanBuy', true); ?>
                                        
                        <?php if ($this->_tpl_vars['aVariantSelections'] && $this->_tpl_vars['aVariantSelections']['selections']): ?>
                            <?php echo smarty_function_oxscript(array('include' => "js/widgets/oxajax.min.js",'priority' => 10), $this);?>

                            <?php echo smarty_function_oxscript(array('include' => "js/widgets/oxarticlevariant.min.js",'priority' => 10), $this);?>

                            <?php echo smarty_function_oxscript(array('add' => "$( '#variants' ).oxArticleVariant();"), $this);?>

                            <?php $this->assign('blCanBuy', $this->_tpl_vars['aVariantSelections']['blPerfectFit']); ?>
                            <?php if (! $this->_tpl_vars['blHasActiveSelections']): ?>
                                <?php if (! $this->_tpl_vars['blCanBuy'] && ! $this->_tpl_vars['oDetailsProduct']->isParentNotBuyable()): ?>
                                    <?php $this->assign('blCanBuy', true); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div id="variants" class="selectorsBox js-fnSubmit clear">
                                <?php $this->assign('blHasActiveSelections', false); ?>
                                <?php $_from = $this->_tpl_vars['aVariantSelections']['selections']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iKey'] => $this->_tpl_vars['oList']):
?>
                                    <?php if ($this->_tpl_vars['oList']->getActiveSelection()): ?>
                                        <?php $this->assign('blHasActiveSelections', true); ?>
                                    <?php endif; ?>
                                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/selectbox.tpl", 'smarty_include_vars' => array('oSelectionList' => $this->_tpl_vars['oList'],'iKey' => $this->_tpl_vars['iKey'],'blInDetails' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                    <div class="clearfix"></div>
                                <?php endforeach; endif; unset($_from); ?>
                            </div>
                        <?php endif; ?>
                    
                </div>

                                
                    <?php if ($this->_tpl_vars['oViewConf']->showSelectLists()): ?>
                        <?php $this->assign('oSelections', $this->_tpl_vars['oDetailsProduct']->getSelections()); ?>
                        <?php if ($this->_tpl_vars['oSelections']): ?>
                            <div class="selectorsBox js-fnSubmit clear" id="productSelections">
                                <?php $_from = $this->_tpl_vars['oSelections']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['selections'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['selections']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oList']):
        $this->_foreach['selections']['iteration']++;
?>
                                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "widget/product/selectbox.tpl", 'smarty_include_vars' => array('oSelectionList' => $this->_tpl_vars['oList'],'sFieldName' => 'sel','iKey' => ($this->_foreach['selections']['iteration']-1),'blHideDefault' => true,'sSelType' => 'seldrop')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                <?php endforeach; endif; unset($_from); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                

                <div class="pricebox">
                    
                        <?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'SHOWARTICLEPRICE')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
                            <?php if ($this->_tpl_vars['oDetailsProduct']->getTPrice()): ?>
                                <del><?php echo smarty_function_oxprice(array('price' => $this->_tpl_vars['oDetailsProduct']->getTPrice(),'currency' => $this->_tpl_vars['currency']), $this);?>
</del>
                                <br/>
                            <?php endif; ?>
                        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
                    

                    

                    
                        <?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'SHOWARTICLEPRICE')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
                            
                                <?php if ($this->_tpl_vars['oDetailsProduct']->getFPrice()): ?>
                                    <label id="productPrice" class="price">
                                        <?php $this->assign('sFrom', ""); ?>
                                        <?php $this->assign('oPrice', $this->_tpl_vars['oDetailsProduct']->getPrice()); ?>
                                        <?php if ($this->_tpl_vars['oDetailsProduct']->isParentNotBuyable()): ?>
                                            <?php $this->assign('oPrice', $this->_tpl_vars['oDetailsProduct']->getVarMinPrice()); ?>
                                            <?php if ($this->_tpl_vars['oDetailsProduct']->isRangePrice()): ?>
                                                <?php $this->assign('sFrom', ((is_array($_tmp='PRICE_FROM')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))); ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <span<?php if ($this->_tpl_vars['oDetailsProduct']->getTPrice()): ?> class="text-danger"<?php endif; ?>>
                                            <span class="price-from"><?php echo $this->_tpl_vars['sFrom']; ?>
</span>
                                            <span class="price"><?php echo smarty_function_oxprice(array('price' => $this->_tpl_vars['oPrice'],'currency' => $this->_tpl_vars['currency']), $this);?>
</span>
                                            <?php if ($this->_tpl_vars['oView']->isVatIncluded()): ?>
                                                <span class="price-markup">*</span>
                                            <?php endif; ?>
                                            <span class="hidden">
                                                <span itemprop="price"><?php echo smarty_function_oxprice(array('price' => $this->_tpl_vars['oPrice'],'currency' => $this->_tpl_vars['currency']), $this);?>
</span>
                                            </span>
                                        </span>
                                    </label>
                                <?php endif; ?>
                                <?php if ($this->_tpl_vars['oDetailsProduct']->loadAmountPriceInfo()): ?>
                                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/details/inc/priceinfo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                <?php endif; ?>
                            
                        <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
                    
                </div>

                <div class="tobasket">
                                        
                        <?php if ($this->_tpl_vars['oView']->isPersParam()): ?>
                            <div class="persparamBox clear form-group">
                                <label for="persistentParam" class="control-label"><?php echo smarty_function_oxmultilang(array('ident' => 'LABEL'), $this);?>
</label>
                                <input type="text" id="persistentParam" name="persparam[details]" value="<?php echo $this->_tpl_vars['oDetailsProduct']->aPersistParam['text']; ?>
" size="35" class="form-control">
                            </div>
                        <?php endif; ?>
                    

                    
                        <div class="tobasketFunction clear">
                            <?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'TOBASKET')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
                                <?php if (! $this->_tpl_vars['oDetailsProduct']->isNotBuyable()): ?>
                                    <div class="input-group">
                                        <input id="amountToBasket" type="text" name="am" value="1" autocomplete="off" class="form-control">
                                        <div class="input-group-tweak">
                                            <button id="toBasket" type="submit" <?php if (! $this->_tpl_vars['blCanBuy']): ?>disabled="disabled"<?php endif; ?> class="btn btn-primary submitButton largeButton"><i class="fa fa-shopping-cart"></i> <?php echo smarty_function_oxmultilang(array('ident' => 'TO_CART'), $this);?>
</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
                        </div>
                    

                    
                        <?php if ($this->_tpl_vars['oDetailsProduct']->getStockStatus() == -1): ?>
                            <span class="stockFlag notOnStock">
                                <i class="fa fa-circle text-danger"></i>
                                <?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnostocktext->value): ?>
                                    <link itemprop="availability" href="http://schema.org/OutOfStock"/>
                                    <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxnostocktext->value; ?>

                                <?php elseif ($this->_tpl_vars['oViewConf']->getStockOffDefaultMessage()): ?>
                                    <link itemprop="availability" href="http://schema.org/OutOfStock"/>
                                    <?php echo smarty_function_oxmultilang(array('ident' => 'MESSAGE_NOT_ON_STOCK'), $this);?>

                                <?php endif; ?>
                                <?php if ($this->_tpl_vars['oDetailsProduct']->getDeliveryDate()): ?>
                                    <link itemprop="availability" href="http://schema.org/PreOrder"/>
                                    <?php echo smarty_function_oxmultilang(array('ident' => 'AVAILABLE_ON'), $this);?>
 <?php echo $this->_tpl_vars['oDetailsProduct']->getDeliveryDate(); ?>

                                <?php endif; ?>
                            </span>
                        <?php elseif ($this->_tpl_vars['oDetailsProduct']->getStockStatus() == 1): ?>
                            <link itemprop="availability" href="http://schema.org/InStock"/>
                            <span class="stockFlag lowStock">
                                <i class="fa fa-circle text-warning"></i> <?php echo smarty_function_oxmultilang(array('ident' => 'LOW_STOCK'), $this);?>

                            </span>
                        <?php elseif ($this->_tpl_vars['oDetailsProduct']->getStockStatus() == 0): ?>
                            <span class="stockFlag">
                                <link itemprop="availability" href="http://schema.org/InStock"/>
                                <i class="fa fa-circle text-success"></i>
                                <?php if ($this->_tpl_vars['oDetailsProduct']->oxarticles__oxstocktext->value): ?>
                                    <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxstocktext->value; ?>

                                <?php elseif ($this->_tpl_vars['oViewConf']->getStockOnDefaultMessage()): ?>
                                    <?php echo smarty_function_oxmultilang(array('ident' => 'READY_FOR_SHIPPING'), $this);?>

                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    

                    <?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'TOBASKET')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
                        <?php if ($this->_tpl_vars['oDetailsProduct']->isBuyable()): ?>
							
								<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page/details/inc/deliverytime.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
							
                        <?php endif; ?>
                    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

                    
                    
                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 details-col-right">
            <?php if ($this->_tpl_vars['oManufacturer']): ?>
                <div class="brandLogo">
                    
                        <a href="<?php echo $this->_tpl_vars['oManufacturer']->getLink(); ?>
" title="<?php echo $this->_tpl_vars['oManufacturer']->oxmanufacturers__oxtitle->value; ?>
">
                            <?php if ($this->_tpl_vars['oManufacturer']->oxmanufacturers__oxicon->value): ?>
                                <img src="<?php echo $this->_tpl_vars['oManufacturer']->getIconUrl(); ?>
" alt="<?php echo $this->_tpl_vars['oManufacturer']->oxmanufacturers__oxtitle->value; ?>
">
                            <?php endif; ?>
                        </a>
                        <span itemprop="brand" class="hidden"><?php echo $this->_tpl_vars['oManufacturer']->oxmanufacturers__oxtitle->value; ?>
</span>
                    
                </div>
            <?php endif; ?>

            
                
                    <ul class="list-unstyled action-links">
                        <?php if ($this->_tpl_vars['oViewConf']->getShowCompareList()): ?>
							<li>
                                <?php echo smarty_function_oxid_include_dynamic(array('file' => "page/details/inc/compare_links.tpl",'testid' => "",'type' => 'compare','aid' => $this->_tpl_vars['oDetailsProduct']->oxarticles__oxid->value,'anid' => $this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value,'in_list' => $this->_tpl_vars['oDetailsProduct']->isOnComparisonList(),'page' => $this->_tpl_vars['oView']->getActPage(),'text_to_id' => 'COMPARE','text_from_id' => 'REMOVE_FROM_COMPARE_LIST'), $this);?>

							</li>
						<?php endif; ?>
                        <li>
                            <?php if ($this->_tpl_vars['oViewConf']->getShowSuggest()): ?>
                                <a id="suggest" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=suggest") : smarty_modifier_cat($_tmp, "cl=suggest")),'params' => ((is_array($_tmp="anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'RECOMMEND'), $this);?>
</a>
                            <?php endif; ?>
                        </li>

						<?php if ($this->_tpl_vars['oViewConf']->getShowListmania()): ?>
							<li>
								<?php if ($this->_tpl_vars['oxcmp_user']): ?>
                                    <a id="recommList" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=recommadd") : smarty_modifier_cat($_tmp, "cl=recommadd")),'params' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp="aid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value)."&amp;anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams())))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;stoken=") : smarty_modifier_cat($_tmp, "&amp;stoken=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getSessionChallengeToken()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getSessionChallengeToken()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ADD_TO_LISTMANIA_LIST'), $this);?>
</a>
                                <?php else: ?>
                                    <a id="loginToRecommlist" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account") : smarty_modifier_cat($_tmp, "cl=account")),'params' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp="anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;sourcecl=") : smarty_modifier_cat($_tmp, "&amp;sourcecl=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName())))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ADD_TO_LISTMANIA_LIST'), $this);?>
</a>
                                <?php endif; ?>
							</li>
                       <?php endif; ?>
                        <li>
                            <?php if ($this->_tpl_vars['oxcmp_user']): ?>
                                <a id="linkToNoticeList" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=") : smarty_modifier_cat($_tmp, "cl=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName())),'params' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp="aid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value)."&amp;anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value)."&amp;fnc=tonoticelist&amp;am=1")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams())))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;stoken=") : smarty_modifier_cat($_tmp, "&amp;stoken=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getSessionChallengeToken()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getSessionChallengeToken()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ADD_TO_WISH_LIST'), $this);?>
</a>
                            <?php else: ?>
                                <a id="loginToNotice" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account") : smarty_modifier_cat($_tmp, "cl=account")),'params' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp="anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;sourcecl=") : smarty_modifier_cat($_tmp, "&amp;sourcecl=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName())))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ADD_TO_WISH_LIST'), $this);?>
</a>
                            <?php endif; ?>
                        </li>
                       <?php if ($this->_tpl_vars['oViewConf']->getShowWishlist()): ?>
	                        <li>
                                <?php if ($this->_tpl_vars['oxcmp_user']): ?>
                                    <a id="linkToWishList" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=") : smarty_modifier_cat($_tmp, "cl=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName())),'params' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp="aid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value)."&anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value)."&amp;fnc=towishlist&amp;am=1")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams())))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;stoken=") : smarty_modifier_cat($_tmp, "&amp;stoken=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getSessionChallengeToken()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getSessionChallengeToken()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ADD_TO_GIFT_REGISTRY'), $this);?>
</a>
                                <?php else: ?>
                                    <a id="loginToWish" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account") : smarty_modifier_cat($_tmp, "cl=account")),'params' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp="anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;sourcecl=") : smarty_modifier_cat($_tmp, "&amp;sourcecl=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getTopActiveClassName())))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'ADD_TO_GIFT_REGISTRY'), $this);?>
</a>
                                <?php endif; ?>
							</li>
                       <?php endif; ?>

                        <li>
                            <?php echo smarty_function_mailto(array('extra' => 'id="questionMail"','address' => ((is_array($_tmp=@$this->_tpl_vars['oDetailsProduct']->oxarticles__oxquestionemail->value)) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['oxcmp_shop']->oxshops__oxinfoemail->value) : smarty_modifier_default($_tmp, @$this->_tpl_vars['oxcmp_shop']->oxshops__oxinfoemail->value)),'subject' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='QUESTIONS_ABOUT_THIS_PRODUCT')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' ')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oDetailsProduct']->oxarticles__oxartnum->value) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oDetailsProduct']->oxarticles__oxartnum->value)),'text' => ((is_array($_tmp='QUESTIONS_ABOUT_THIS_PRODUCT')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp))), $this);?>

                        </li>
                    </ul>
                
            
        </div>
    </div>
    <?php if ($this->_tpl_vars['blFullwidth']): ?>
        <hr>
    <?php endif; ?>
</div>

<?php $this->_tag_stack[] = array('oxhasrights', array('ident' => 'TOBASKET')); $_block_repeat=true;smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    </form>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxhasrights($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>