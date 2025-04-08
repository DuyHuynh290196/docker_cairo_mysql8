<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from page/details/inc/morepics.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'page/details/inc/morepics.tpl', 4, false),array('modifier', 'getimagesize', 'page/details/inc/morepics.tpl', 15, false),array('function', 'oxscript', 'page/details/inc/morepics.tpl', 6, false),array('function', 'oxstyle', 'page/details/inc/morepics.tpl', 7, false),)), $this); ?>
<?php $this->assign('oConfig', $this->_tpl_vars['oViewConf']->getConfig()); ?>

<?php if ($this->_tpl_vars['oView']->morePics()): ?>
    <?php $this->assign('iMorePics', count($this->_tpl_vars['oView']->getIcons())); ?>
    <?php if ($this->_tpl_vars['iMorePics'] > 4): ?>
        <?php echo smarty_function_oxscript(array('include' => "js/libs/jquery.flexslider.min.js",'priority' => 2), $this);?>

        <?php echo smarty_function_oxstyle(array('include' => "css/libs/jquery.flexslider.min.css"), $this);?>

    <?php endif; ?>

    <div class="otherPictures<?php if ($this->_tpl_vars['iMorePics'] > 4): ?> flexslider<?php endif; ?>" id="morePicsContainer">
        <ul class="<?php if ($this->_tpl_vars['iMorePics'] > 4): ?>slides<?php else: ?>list-inline<?php endif; ?>">
            <?php echo smarty_function_oxscript(array('add' => "var aMorePic=new Array();"), $this);?>

            <?php $_from = $this->_tpl_vars['oView']->getIcons(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['sMorePics'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['sMorePics']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['iPicNr'] => $this->_tpl_vars['oArtIcon']):
        $this->_foreach['sMorePics']['iteration']++;
?>
                <?php $this->assign('sPictureName', $this->_tpl_vars['oPictureProduct']->getPictureFieldValue('oxpic',$this->_tpl_vars['iPicNr'])); ?>
                <?php $this->assign('aPictureInfo', getimagesize($this->_tpl_vars['oConfig']->getMasterPicturePath("product/".($this->_tpl_vars['iPicNr'])."/".($this->_tpl_vars['sPictureName'])))); ?>
                <li>
                    <a id="morePics_<?php echo $this->_foreach['sMorePics']['iteration']; ?>
" <?php if (($this->_foreach['sMorePics']['iteration'] <= 1)): ?> class="selected"<?php endif; ?> href="<?php echo $this->_tpl_vars['oPictureProduct']->getPictureUrl($this->_tpl_vars['iPicNr']); ?>
" data-num="<?php echo $this->_foreach['sMorePics']['iteration']; ?>
"<?php if ($this->_tpl_vars['aPictureInfo']): ?> data-width="<?php echo $this->_tpl_vars['aPictureInfo']['0']; ?>
" data-height="<?php echo $this->_tpl_vars['aPictureInfo']['1']; ?>
"<?php endif; ?> data-zoom-url="<?php echo $this->_tpl_vars['oPictureProduct']->getMasterZoomPictureUrl($this->_tpl_vars['iPicNr']); ?>
">
                        <img src="<?php echo $this->_tpl_vars['oPictureProduct']->getIconUrl($this->_tpl_vars['iPicNr']); ?>
" alt="morepic-<?php echo $this->_foreach['sMorePics']['iteration']; ?>
">
                    </a>
                </li>
            <?php endforeach; endif; unset($_from); ?>
        </ul>
    </div>
<?php endif; ?>