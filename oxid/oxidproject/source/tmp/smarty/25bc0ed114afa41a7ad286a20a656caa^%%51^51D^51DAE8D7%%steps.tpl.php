<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:38
         compiled from page/checkout/inc/steps.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxgetseourl', 'page/checkout/inc/steps.tpl', 9, false),array('function', 'oxmultilang', 'page/checkout/inc/steps.tpl', 12, false),)), $this); ?>

    <ol class="checkoutSteps">
        <?php if ($this->_tpl_vars['oxcmp_basket']->getProductsCount()): ?>
            <?php $this->assign('showStepLinks', true); ?>
        <?php endif; ?>

        
            <li class="step1<?php if ($this->_tpl_vars['active'] == 1): ?> active <?php elseif ($this->_tpl_vars['active'] > 1): ?> passed <?php endif; ?>">
                <?php if ($this->_tpl_vars['showStepLinks']): ?><a href="<?php echo smarty_function_oxgetseourl(array('ident' => $this->_tpl_vars['oViewConf']->getBasketLink()), $this);?>
"><?php endif; ?>
                <div class="num">1</div>
                <div class="text">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'STEPS_BASKET'), $this);?>

                </div>
                <?php if ($this->_tpl_vars['showStepLinks']): ?></a><?php endif; ?>
            </li>
        

        <?php $this->assign('showStepLinks', false); ?>
        <?php if (! $this->_tpl_vars['oView']->isLowOrderPrice() && $this->_tpl_vars['oxcmp_basket']->getProductsCount()): ?>
            <?php $this->assign('showStepLinks', true); ?>
        <?php endif; ?>

        
            <li class="step2<?php if ($this->_tpl_vars['active'] == 2): ?> active <?php elseif ($this->_tpl_vars['active'] > 2): ?> passed <?php endif; ?>">
                <?php if ($this->_tpl_vars['showStepLinks']): ?><a href="<?php echo smarty_function_oxgetseourl(array('ident' => $this->_tpl_vars['oViewConf']->getOrderLink()), $this);?>
"><?php endif; ?>
                <div class="num">2</div>
                <div class="text">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'STEPS_SEND'), $this);?>

                </div>
                <?php if ($this->_tpl_vars['showStepLinks']): ?></a><?php endif; ?>
            </li>
        

        <?php $this->assign('showStepLinks', false); ?>
        <?php if ($this->_tpl_vars['active'] != 1 && $this->_tpl_vars['oxcmp_user'] && ! $this->_tpl_vars['oView']->isLowOrderPrice() && $this->_tpl_vars['oxcmp_basket']->getProductsCount()): ?>
            <?php $this->assign('showStepLinks', true); ?>
        <?php endif; ?>

        
            <li class="step3<?php if ($this->_tpl_vars['active'] == 3): ?> active <?php elseif ($this->_tpl_vars['active'] > 3): ?> passed <?php endif; ?>">
                <?php if ($this->_tpl_vars['showStepLinks']): ?><a <?php if ($this->_tpl_vars['oViewConf']->getActiveClassName() == 'user'): ?>id="paymentStep"<?php endif; ?> href="<?php echo smarty_function_oxgetseourl(array('ident' => $this->_tpl_vars['oViewConf']->getPaymentLink()), $this);?>
"><?php endif; ?>
                <div class="num">3</div>
                <div class="text">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'STEPS_PAY'), $this);?>

                </div>
                <?php if ($this->_tpl_vars['showStepLinks']): ?></a><?php endif; ?>
            </li>
                    

        <?php $this->assign('showStepLinks', false); ?>
        <?php if ($this->_tpl_vars['active'] != 1 && $this->_tpl_vars['oxcmp_user'] && $this->_tpl_vars['oxcmp_basket']->getProductsCount() && $this->_tpl_vars['oView']->getPaymentList() && ! $this->_tpl_vars['oView']->isLowOrderPrice()): ?>
            <?php $this->assign('showStepLinks', true); ?>
        <?php endif; ?>

        
            <li class="step4<?php if ($this->_tpl_vars['active'] == 4): ?> active <?php elseif ($this->_tpl_vars['active'] > 4): ?> passed <?php endif; ?>">
                <?php if ($this->_tpl_vars['showStepLinks']): ?><a <?php if ($this->_tpl_vars['oViewConf']->getActiveClassName() == 'payment'): ?>id="orderStep"<?php endif; ?> href="<?php if ($this->_tpl_vars['oViewConf']->getActiveClassName() == 'payment'): ?>javascript:document.forms.order.submit();<?php else: ?><?php echo smarty_function_oxgetseourl(array('ident' => $this->_tpl_vars['oViewConf']->getOrderConfirmLink()), $this);?>
<?php endif; ?>"><?php endif; ?>
                <div class="num">4</div>
                <div class="text">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'STEPS_ORDER'), $this);?>

                </div>
                <?php if ($this->_tpl_vars['showStepLinks']): ?></a><?php endif; ?>
            </li>
                    

        
            <li class="step5<?php if ($this->_tpl_vars['active'] == 5): ?> activeLast <?php else: ?> defaultLast <?php endif; ?> ">
                <div class="num">5</div>
                <div class="text">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'READY'), $this);?>

                </div>
            </li>
        
    </ol>
    <div class="clearfix"></div>
    <div class="spacer"></div>