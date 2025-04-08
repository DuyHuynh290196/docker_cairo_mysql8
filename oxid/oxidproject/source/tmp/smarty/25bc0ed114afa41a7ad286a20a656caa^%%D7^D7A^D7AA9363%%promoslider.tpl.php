<?php /* Smarty version 2.6.33, created on 2025-04-02 05:02:42
         compiled from widget/promoslider.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'widget/promoslider.tpl', 6, false),array('modifier', 'trim', 'widget/promoslider.tpl', 37, false),array('function', 'oxscript', 'widget/promoslider.tpl', 7, false),array('function', 'oxstyle', 'widget/promoslider.tpl', 8, false),)), $this); ?>

    <?php $this->assign('oBanners', $this->_tpl_vars['oView']->getBanners()); ?>
    <?php $this->assign('currency', $this->_tpl_vars['oView']->getActCurrency()); ?>
    <?php $this->assign('blFullwidth', $this->_tpl_vars['oViewConf']->getViewThemeParam('blFullwidthLayout')); ?>

    <?php if (count($this->_tpl_vars['oBanners'])): ?>
        <?php echo smarty_function_oxscript(array('include' => "js/libs/jquery.flexslider.min.js",'priority' => 2), $this);?>

        <?php echo smarty_function_oxstyle(array('include' => "css/libs/jquery.flexslider.min.css"), $this);?>


        <?php if ($this->_tpl_vars['blFullwidth']): ?>
            <div class="col-xs-12">
        <?php endif; ?>

        <div id="promo-carousel" class="flexslider">
            <ul class="slides">
                
                    <?php $_from = $this->_tpl_vars['oBanners']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['promoslider'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['promoslider']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['iPicNr'] => $this->_tpl_vars['oBanner']):
        $this->_foreach['promoslider']['iteration']++;
?>
                        <?php $this->assign('oArticle', $this->_tpl_vars['oBanner']->getBannerArticle()); ?>
                        <?php $this->assign('sBannerPictureUrl', $this->_tpl_vars['oBanner']->getBannerPictureUrl()); ?>
                        <?php if ($this->_tpl_vars['sBannerPictureUrl']): ?>
                            <li class="item">
                                <?php $this->assign('sBannerLink', $this->_tpl_vars['oBanner']->getBannerLink()); ?>
                                <?php if ($this->_tpl_vars['sBannerLink']): ?>
                                    <a href="<?php echo $this->_tpl_vars['sBannerLink']; ?>
" title="<?php echo $this->_tpl_vars['oBanner']->oxactions__oxtitle->value; ?>
">
                                <?php endif; ?>

                                <img src="<?php echo $this->_tpl_vars['sBannerPictureUrl']; ?>
" alt="<?php echo $this->_tpl_vars['oBanner']->oxactions__oxtitle->value; ?>
" title="<?php echo $this->_tpl_vars['oBanner']->oxactions__oxtitle->value; ?>
">

                                <?php if ($this->_tpl_vars['sBannerLink']): ?>
                                    </a>
                                <?php endif; ?>
                                <?php if ($this->_tpl_vars['oViewConf']->getViewThemeParam('blSliderShowImageCaption') && $this->_tpl_vars['oArticle']): ?>
                                    <p class="flex-caption">
                                        <?php if ($this->_tpl_vars['sBannerLink']): ?>
                                            <a href="<?php echo $this->_tpl_vars['sBannerLink']; ?>
" title="<?php echo $this->_tpl_vars['oBanner']->oxactions__oxtitle->value; ?>
">
                                        <?php endif; ?>
                                        <span class="title"><?php echo $this->_tpl_vars['oArticle']->oxarticles__oxtitle->value; ?>
</span><?php if (((is_array($_tmp=$this->_tpl_vars['oArticle']->oxarticles__oxshortdesc->value)) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp))): ?><br/><span class="shortdesc"><?php echo ((is_array($_tmp=$this->_tpl_vars['oArticle']->oxarticles__oxshortdesc->value)) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
</span><?php endif; ?>
                                        <?php if ($this->_tpl_vars['sBannerLink']): ?>
                                            </a>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?>
                
            </ul>
        </div>

        <?php if ($this->_tpl_vars['blFullwidth']): ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
