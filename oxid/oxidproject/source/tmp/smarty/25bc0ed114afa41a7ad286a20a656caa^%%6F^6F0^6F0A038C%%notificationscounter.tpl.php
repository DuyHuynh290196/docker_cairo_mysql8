<?php /* Smarty version 2.6.33, created on 2025-04-02 05:02:42
         compiled from widget/header/notificationscounter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'widget/header/notificationscounter.tpl', 10, false),)), $this); ?>
<?php if ($this->_tpl_vars['oxcmp_user']): ?>
    <?php $this->assign('noticeListCount', $this->_tpl_vars['oxcmp_user']->getNoticeListArtCnt()); ?>
    <?php $this->assign('wishListCount', $this->_tpl_vars['oxcmp_user']->getWishListArtCnt()); ?>
    <?php $this->assign('recommListCount', $this->_tpl_vars['oxcmp_user']->getRecommListsCount()); ?>
<?php else: ?>
    <?php $this->assign('noticeListCount', '0'); ?>
    <?php $this->assign('wishListCount', '0'); ?>
    <?php $this->assign('recommListCount', '0'); ?>
<?php endif; ?>
<?php echo smarty_function_math(array('equation' => "a+b+c+d",'a' => $this->_tpl_vars['oView']->getCompareItemsCnt(),'b' => $this->_tpl_vars['noticeListCount'],'c' => $this->_tpl_vars['wishListCount'],'d' => $this->_tpl_vars['recommListCount'],'assign' => 'notificationsCounter'), $this);?>


<?php if ($this->_tpl_vars['notificationsCounter'] > 0): ?>
    <span class="badge"><?php echo $this->_tpl_vars['notificationsCounter']; ?>
</span>
<?php endif; ?>