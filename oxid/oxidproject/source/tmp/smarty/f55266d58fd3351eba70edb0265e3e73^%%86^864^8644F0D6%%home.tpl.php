<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:43
         compiled from home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'home.tpl', 5, false),array('modifier', 'lower', 'home.tpl', 7, false),array('modifier', 'oxaddslashes', 'home.tpl', 14, false),array('modifier', 'round', 'home.tpl', 45, false),array('modifier', 'default', 'home.tpl', 54, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    
        <title><?php echo smarty_function_oxmultilang(array('ident' => 'MAIN_TITLE'), $this);?>
</title>
        <link rel="stylesheet" href="<?php echo $this->_tpl_vars['oViewConf']->getResourceUrl(); ?>
main.css">
        <link rel="stylesheet" href="<?php echo $this->_tpl_vars['oViewConf']->getResourceUrl(); ?>
colors_<?php echo ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getEdition())) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.css">
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
">
    
</head>
<body>

<script type="text/javascript">
    parent.sShopTitle = "<?php echo ((is_array($_tmp=$this->_tpl_vars['actshop'])) ? $this->_run_mod_handler('oxaddslashes', true, $_tmp) : smarty_modifier_oxaddslashes($_tmp)); ?>
";
    parent.setTitle();
</script>

<h1><?php echo smarty_function_oxmultilang(array('ident' => 'NAVIGATION_HOME'), $this);?>
</h1>
<p class="desc">
    <b><?php echo smarty_function_oxmultilang(array('ident' => 'HOME_DESC'), $this);?>
</b>
</p>
<hr>

<?php if ($this->_tpl_vars['aMessage']): ?>
    <div class="messagebox">
        <?php echo smarty_function_oxmultilang(array('ident' => 'MAIN_INFO'), $this);?>
:<br>
        <?php $_from = $this->_tpl_vars['aMessage']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['class'] => $this->_tpl_vars['sMessage']):
?>
            <p class="<?php echo $this->_tpl_vars['class']; ?>
"><?php echo $this->_tpl_vars['sMessage']; ?>
</p>
        <?php endforeach; endif; unset($_from); ?>
    </div>
    <hr>
<?php endif; ?>



    <table width="100%" height="84%">
    <?php $this->assign('shMen', 1); ?>

    <?php $_from = $this->_tpl_vars['menustructure']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menuholder']):
?>
    <?php if ($this->_tpl_vars['shMen'] && $this->_tpl_vars['menuholder']->nodeType == XML_ELEMENT_NODE && $this->_tpl_vars['menuholder']->childNodes->length): ?>

        <?php $this->assign('nrCol', 1); ?>
        <?php $this->assign('ttCol', 1); ?>
        <?php $this->assign('mxCol', 3); ?>
        <?php $this->assign('inCol', ((is_array($_tmp=$this->_tpl_vars['menuholder']->childNodes->length/$this->_tpl_vars['mxCol'])) ? $this->_run_mod_handler('round', true, $_tmp) : round($_tmp))); ?>
        <?php $this->assign('shMen', 0); ?>
        <?php $this->assign('mn', 1); ?>
            <tr>
            <td valign="top" width="30%">
            <?php $_from = $this->_tpl_vars['menuholder']->childNodes; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menuitem']):
?>
            <?php if ($this->_tpl_vars['menuitem']->nodeType == XML_ELEMENT_NODE && $this->_tpl_vars['menuitem']->childNodes->length): ?>
                <?php $this->assign('sb', 1); ?>
                <dl <?php if ($this->_tpl_vars['nrCol'] == 1): ?>class="first"<?php endif; ?>>
                    <dt><?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp=@$this->_tpl_vars['menuitem']->getAttribute('name'))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['menuitem']->getAttribute('id')) : smarty_modifier_default($_tmp, @$this->_tpl_vars['menuitem']->getAttribute('id')))), $this);?>
</dt>
                    <dd>
                        <ul>
                        <?php echo ''; ?><?php $_from = $this->_tpl_vars['menuitem']->childNodes; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['submenuitem']):
?><?php echo ''; ?><?php if ($this->_tpl_vars['submenuitem']->nodeType == XML_ELEMENT_NODE): ?><?php echo '<li><a href="'; ?><?php echo $this->_tpl_vars['submenuitem']->getAttribute('link'); ?><?php echo '" onclick="_homeExpAct(\'nav-1-'; ?><?php echo $this->_tpl_vars['mn']; ?><?php echo '\',\'nav-1-'; ?><?php echo $this->_tpl_vars['mn']; ?><?php echo '-'; ?><?php echo $this->_tpl_vars['sb']; ?><?php echo '\');" target="basefrm"><b>'; ?><?php echo smarty_function_oxmultilang(array('ident' => ((is_array($_tmp=@$this->_tpl_vars['submenuitem']->getAttribute('name'))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['submenuitem']->getAttribute('id')) : smarty_modifier_default($_tmp, @$this->_tpl_vars['submenuitem']->getAttribute('id')))), $this);?><?php echo '</b></a></li>'; ?><?php $this->assign('sb', $this->_tpl_vars['sb']+1); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php endforeach; endif; unset($_from); ?><?php echo ''; ?>

                        </ul>
                    </dd>
                </dl>
                <?php $this->assign('mn', $this->_tpl_vars['mn']+1); ?>
                <?php if ($this->_tpl_vars['nrCol'] == $this->_tpl_vars['inCol'] && $this->_tpl_vars['ttCol'] < $this->_tpl_vars['mxCol']): ?>
                    </td><td width="5%"></td><td valign="top" width="30%">
                    <?php $this->assign('nrCol', 1); ?>
                    <?php $this->assign('ttCol', $this->_tpl_vars['ttCol']+1); ?>
                <?php else: ?>
                    <?php $this->assign('nrCol', $this->_tpl_vars['nrCol']+1); ?>
                <?php endif; ?>

            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
            </td>
            </tr>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>

</table>
<script type="text/javascript">
    <!--
    function _homeExpAct(mnid,sbid){
        top.document.getElementById('navigation').contentWindow.adminnav._navExtExpAct(mnid,sbid);
    }
    //-->
    </script>
</body>
</html>