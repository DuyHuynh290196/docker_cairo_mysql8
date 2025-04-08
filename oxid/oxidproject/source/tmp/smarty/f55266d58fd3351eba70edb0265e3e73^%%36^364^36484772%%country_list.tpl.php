<?php /* Smarty version 2.6.33, created on 2025-04-01 12:23:54
         compiled from country_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'country_list.tpl', 1, false),array('modifier', 'oxaddslashes', 'country_list.tpl', 124, false),array('function', 'oxmultilang', 'country_list.tpl', 63, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)),'box' => 'list')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('where', $this->_tpl_vars['oView']->getListFilter()); ?>

<?php if ($this->_tpl_vars['readonly']): ?>
    <?php $this->assign('readonly', 'readonly disabled'); ?>
<?php else: ?>
    <?php $this->assign('readonly', ""); ?>
<?php endif; ?>

<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    <?php if ($this->_tpl_vars['updatelist'] == 1): ?>
        top.oxid.admin.updateList('<?php echo $this->_tpl_vars['oxid']; ?>
');
    <?php endif; ?>
}
//-->
</script>

<div id="liste">


<form name="search" id="search" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_formparams.tpl", 'smarty_include_vars' => array('cl' => 'country_list','lstrt' => $this->_tpl_vars['lstrt'],'actedit' => $this->_tpl_vars['actedit'],'oxid' => $this->_tpl_vars['oxid'],'fnc' => "",'language' => $this->_tpl_vars['actlang'],'editlanguage' => $this->_tpl_vars['actlang'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <colgroup>
        
            <col width="4%">
            <col width="40%">
            <col width="35%">
            <col width="20%">
            <col width="1%">
        
    </colgroup>
    <tr class="listitem">
        
            <td valign="top" class="listfilter first" align="center">
                <div class="r1"><div class="b1">
                <input class="listedit" type="text" size="3" maxlength="128" name="where[oxcountry][oxactive]" value="<?php echo $this->_tpl_vars['where']['oxcountry']['oxactive']; ?>
">
                </div></div>
            </td>
            <td valign="top" class="listfilter">
                <div class="r1"><div class="b1">
                <input class="listedit" type="text" size="50" maxlength="128" name="where[oxcountry][oxtitle]" value="<?php echo $this->_tpl_vars['where']['oxcountry']['oxtitle']; ?>
">
                </div></div>
            </td>
            <td valign="top" class="listfilter">
                <div class="r1"><div class="b1">
                <input class="listedit" type="text" size="50" maxlength="128" name="where[oxcountry][oxshortdesc]" value="<?php echo $this->_tpl_vars['where']['oxcountry']['oxshortdesc']; ?>
">
                </div></div>
            </td>
            <td valign="top" class="listfilter" colspan="2">
                <div class="r1"><div class="b1">

                <div class="find">
                    <select name="changelang" class="editinput" onChange="Javascript:top.oxid.admin.changeLanguage();">
                    <?php $_from = $this->_tpl_vars['languages']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lang']):
?>
                    <option value="<?php echo $this->_tpl_vars['lang']->id; ?>
" <?php if ($this->_tpl_vars['lang']->selected): ?>SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['lang']->name; ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                    </select>
                    <input class="listedit" type="submit" name="submitit" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SEARCH'), $this);?>
">
                </div>

                <input class="listedit" type="text" size="5" maxlength="128" name="where[oxcountry][oxisoalpha3]" value="<?php echo $this->_tpl_vars['where']['oxcountry']['oxisoalpha3']; ?>
">
                </div></div>
            </td>
        
    </tr>

    <tr>
        
            <td class="listheader first" height="15" align="center"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'oxcountry', 'oxactive', 'asc');document.search.submit();" class="listheader"><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ARTICLE_OXACTIVE'), $this);?>
</a></td>
            <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'oxcountry', 'oxtitle', 'asc');document.search.submit();" class="listheader"><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_COUNTRY'), $this);?>
</a></td>
            <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'oxcountry', 'oxshortdesc', 'asc');document.search.submit();" class="listheader"><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SHORTDESC'), $this);?>
</a></td>
            <td class="listheader" height="15" colspan="2"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'oxcountry', 'oxisoalpha3', 'asc');document.search.submit();" class="listheader"><?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_LIST_ISO'), $this);?>
</a></td>
        
    </tr>

<?php $this->assign('blWhite', ""); ?>
<?php $this->assign('_cnt', 0); ?>
<?php $_from = $this->_tpl_vars['mylist']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listitem']):
?>
    <?php $this->assign('_cnt', $this->_tpl_vars['_cnt']+1); ?>
    <tr id="row.<?php echo $this->_tpl_vars['_cnt']; ?>
">
        
            <?php if ($this->_tpl_vars['listitem']->blacklist == 1): ?>
                <?php $this->assign('listclass', 'listitem3'); ?>
            <?php else: ?>
                <?php $this->assign('listclass', "listitem".($this->_tpl_vars['blWhite'])); ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['listitem']->getId() == $this->_tpl_vars['oxid']): ?>
                <?php $this->assign('listclass', 'listitem4'); ?>
            <?php endif; ?>
            <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
<?php if ($this->_tpl_vars['listitem']->oxcountry__oxactive->value == 1): ?> active<?php endif; ?>" height="15"><div class="listitemfloating">&nbsp;<a href="Javascript:top.oxid.admin.editThis('<?php echo $this->_tpl_vars['listitem']->oxcountry__oxid->value; ?>
');" class="<?php echo $this->_tpl_vars['listclass']; ?>
">
             &nbsp;
            </a></div></td>
            <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('<?php echo $this->_tpl_vars['listitem']->oxcountry__oxid->value; ?>
');" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo $this->_tpl_vars['listitem']->oxcountry__oxtitle->value; ?>
</a></div></td>
            <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('<?php echo $this->_tpl_vars['listitem']->oxcountry__oxid->value; ?>
');" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo $this->_tpl_vars['listitem']->oxcountry__oxshortdesc->value; ?>
</a></div></td>
            <td valign="top" class="<?php echo $this->_tpl_vars['listclass']; ?>
" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('<?php echo $this->_tpl_vars['listitem']->oxcountry__oxid->value; ?>
');" class="<?php echo $this->_tpl_vars['listclass']; ?>
"><?php echo $this->_tpl_vars['listitem']->oxcountry__oxisoalpha3->value; ?>
</a></div></td>
            <td align="right" class="<?php echo $this->_tpl_vars['listclass']; ?>
">
            <?php if (! $this->_tpl_vars['readonly']): ?>
            <a href="Javascript:top.oxid.admin.deleteThis('<?php echo $this->_tpl_vars['listitem']->oxcountry__oxid->value; ?>
');" class="delete" id="del.<?php echo $this->_tpl_vars['_cnt']; ?>
" title="" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'item_delete')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>></a>
            <?php endif; ?>
            </td>
        
    </tr>
<?php if ($this->_tpl_vars['blWhite'] == '2'): ?>
<?php $this->assign('blWhite', ""); ?>
<?php else: ?>
<?php $this->assign('blWhite', '2'); ?>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagenavisnippet.tpl", 'smarty_include_vars' => array('colspan' => '5')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</table>
</form>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagetabsnippet.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "<?php echo ((is_array($_tmp=$this->_tpl_vars['actshopobj']->oxshops__oxname->getRawValue())) ? $this->_run_mod_handler('oxaddslashes', true, $_tmp) : smarty_modifier_oxaddslashes($_tmp)); ?>
";
    parent.parent.sMenuItem    = "<?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_LIST_MENUITEM'), $this);?>
";
    parent.parent.sMenuSubItem = "<?php echo smarty_function_oxmultilang(array('ident' => 'COUNTRY_LIST_MENUSUBITEM'), $this);?>
";
    parent.parent.sWorkArea    = "<?php echo $this->_tpl_vars['_act']; ?>
";
    parent.parent.setTitle();
}
</script>
</body>
</html>