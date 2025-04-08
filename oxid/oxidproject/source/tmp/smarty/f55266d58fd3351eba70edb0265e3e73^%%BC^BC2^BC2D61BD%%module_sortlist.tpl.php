<?php /* Smarty version 2.6.33, created on 2025-04-02 05:10:37
         compiled from module_sortlist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'module_sortlist.tpl', 1, false),array('modifier', 'replace', 'module_sortlist.tpl', 79, false),array('function', 'oxmultilang', 'module_sortlist.tpl', 20, false),array('function', 'oxscript', 'module_sortlist.tpl', 98, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'box' => 'box')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="container">

    <form name="transfer" id="transfer" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
        <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

        <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
        <input type="hidden" name="cl" value="module_main">
        <input type="hidden" name="editlanguage" value="<?php echo $this->_tpl_vars['editlanguage']; ?>
">
    </form>

    

    <div id="infoContent">

        <?php if ($this->_tpl_vars['aDeletedExt']): ?>
            <div class="msgBox">

                <div class="info">
                    <p><?php echo smarty_function_oxmultilang(array('ident' => 'MODULE_EXTENSIONISDELETED'), $this);?>
</p>
                    <p><?php echo smarty_function_oxmultilang(array('ident' => 'MODULE_DELETEEXTENSION'), $this);?>
</p>

                    <table cellspacing="0" cellpadding="0" border="0" width="98%">
                        <tr>
                            <td class="listheader first"><?php echo smarty_function_oxmultilang(array('ident' => 'MODULE_ID'), $this);?>
</td>
                            <td class="listheader"><?php echo smarty_function_oxmultilang(array('ident' => 'MODULE_PROBLEMATIC_FILES'), $this);?>
</td>
                        </tr>
                        <?php $_from = $this->_tpl_vars['aDeletedExt']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sModuleId'] => $this->_tpl_vars['aModules']):
?>
                            <?php $this->assign('listclass', "listitem".($this->_tpl_vars['blWhite'])); ?>
                            <tr>
                                <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo $this->_tpl_vars['sModuleId']; ?>
</td>
                                <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
">
                                    <ul>
                                        <?php $_from = $this->_tpl_vars['aModules']['extensions']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sClassName'] => $this->_tpl_vars['mFile']):
?>
                                            <?php if (is_array ( $this->_tpl_vars['mFile'] )): ?>
                                                <?php $_from = $this->_tpl_vars['mFile']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sFile']):
?>
                                                    <li><?php if (! is_int ( $this->_tpl_vars['sClassName'] )): ?><?php echo $this->_tpl_vars['sClassName']; ?>
 =&gt; <?php endif; ?><?php echo $this->_tpl_vars['sFile']; ?>
</li>
                                                <?php endforeach; endif; unset($_from); ?>
                                            <?php else: ?>
                                                <li><?php if (! is_int ( $this->_tpl_vars['sClassName'] )): ?><?php echo $this->_tpl_vars['sClassName']; ?>
 =&gt; <?php endif; ?><?php echo $this->_tpl_vars['mFile']; ?>
</li>
                                            <?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?>
                                        <?php $_from = $this->_tpl_vars['aModules']['files']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sClassName'] => $this->_tpl_vars['sFile']):
?>
                                            <li><?php if (! is_int ( $this->_tpl_vars['sClassName'] )): ?><?php echo $this->_tpl_vars['sClassName']; ?>
 =&gt; <?php endif; ?><?php echo $this->_tpl_vars['sFile']; ?>
</li>
                                        <?php endforeach; endif; unset($_from); ?>
                                    </ul>
                                </td>
                            </tr>
                            <?php if ($this->_tpl_vars['blWhite'] == '2'): ?>
                                <?php $this->assign('blWhite', ""); ?>
                            <?php else: ?>
                                <?php $this->assign('blWhite', '2'); ?>
                            <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?>
                    </table>
                </div>

                <div>
                    <form name="remove" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
                        <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

                        <input type="hidden" name="cl" value="module_sortlist">
                        <input type="hidden" name="fnc" value="remove">
                        <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
                        <input type="hidden" name="aModules" value="">
                        <input type="hidden" name="updatelist" value="1">
                        <input type="submit" name="yesButton" class="saveButton"
                               value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_YES'), $this);?>
">
                        <input type="submit" name="noButton" class="saveButton"
                               value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_NO'), $this);?>
">
                    </form>
                </div>
            </div>
        <?php else: ?>

            <?php if ($this->_tpl_vars['aExtClasses']): ?>
                <ul class="sortable" id="aModulesList">
                    <?php $_from = $this->_tpl_vars['aExtClasses']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sClassName'] => $this->_tpl_vars['aModuleNames']):
?>
                        <li id="<?php echo $this->_tpl_vars['sClassName']; ?>
">
                            <span><?php echo ((is_array($_tmp=$this->_tpl_vars['sClassName'])) ? $this->_run_mod_handler('replace', true, $_tmp, '---', '&#92;') : smarty_modifier_replace($_tmp, '---', '&#92;')); ?>
</span>
                            <ul class="sortable2" id="<?php echo $this->_tpl_vars['sClassName']; ?>
_modules">
                                <?php $_from = $this->_tpl_vars['aModuleNames']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sModule']):
?>
                                    <?php if (is_array ( $this->_tpl_vars['aDisabledModules'] ) && in_array ( $this->_tpl_vars['sModule'] , $this->_tpl_vars['aDisabledModules'] )): ?>
                                        <?php $this->assign('cssDisabled', 'disabled'); ?>
                                    <?php else: ?>
                                        <?php $this->assign('cssDisabled', ""); ?>
                                    <?php endif; ?>
                                    <li id="<?php echo $this->_tpl_vars['sModule']; ?>
"><span class="<?php echo $this->_tpl_vars['cssDisabled']; ?>
"><?php echo $this->_tpl_vars['sModule']; ?>
</span></li>
                                <?php endforeach; endif; unset($_from); ?>
                            </ul>
                        </li>
                    <?php endforeach; endif; unset($_from); ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
    </div>


    <?php echo smarty_function_oxscript(array('add' => "$('#aModulesList').oxModulesList();",'priority' => 10), $this);?>


    <?php echo smarty_function_oxscript(array('include' => "js/libs/jquery.min.js"), $this);?>

    <?php echo smarty_function_oxscript(array('include' => "js/libs/jquery-ui.min.js"), $this);?>

    <?php echo smarty_function_oxscript(array('include' => "js/libs/json2.js"), $this);?>


    <?php echo smarty_function_oxscript(array('include' => "js/widgets/oxmoduleslist.js"), $this);?>


</div>

<?php if (! $this->_tpl_vars['aDeletedExt'] && $this->_tpl_vars['aExtClasses']): ?>
    <div id="footerBox">
        <div class="buttonsBox">
            <form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
                <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

                <input type="hidden" name="cl" value="module_sortlist">
                <input type="hidden" name="fnc" value="save">
                <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
                <input type="hidden" name="aModules" value="">
                <input type="button" name="saveButton" class="saveButton" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
"
                       disabled>
            </form>
        </div>

        <div class="description">
            <p>
                <?php echo smarty_function_oxmultilang(array('ident' => 'MODULE_DRAGANDDROP'), $this);?>


            </p>
        </div>
    </div>
<?php endif; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomnaviitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottomitem.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
