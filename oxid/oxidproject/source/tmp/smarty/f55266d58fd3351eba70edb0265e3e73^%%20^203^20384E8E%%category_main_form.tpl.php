<?php /* Smarty version 2.6.33, created on 2025-04-03 08:56:55
         compiled from include/category_main_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'include/category_main_form.tpl', 5, false),array('function', 'oxinputhelp', 'include/category_main_form.tpl', 11, false),array('modifier', 'oxtruncate', 'include/category_main_form.tpl', 39, false),array('modifier', 'oxupper', 'include/category_main_form.tpl', 130, false),)), $this); ?>
<table cellspacing="0" cellpadding="0" border="0">
    
        <tr>
            <td class="edittext" width="120">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_ACTIVE'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input class="edittext" type="checkbox" name="editval[oxcategories__oxactive]" value='1' <?php if ($this->_tpl_vars['edit']->oxcategories__oxactive->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>&nbsp;&nbsp;&nbsp;
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_HIDDEN'), $this);?>
&nbsp;&nbsp;&nbsp;
                <input class="edittext" type="checkbox" name="editval[oxcategories__oxhidden]" value='1' <?php if ($this->_tpl_vars['edit']->oxcategories__oxhidden->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_ACTIVE'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_TITLE'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxtitle->fldmax_length; ?>
" name="editval[oxcategories__oxtitle]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxtitle->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_TITLE'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_DESCRIPTION'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxdesc->fldmax_length; ?>
" name="editval[oxcategories__oxdesc]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxdesc->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_DESCRIPTION'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_PARENTID'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <select name="editval[oxcategories__oxparentid]" class="editinput" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php $_from = $this->_tpl_vars['cattree']->aList; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pcat']):
?>
                        <option value="<?php if ($this->_tpl_vars['pcat']->oxcategories__oxid->value): ?><?php echo $this->_tpl_vars['pcat']->oxcategories__oxid->value; ?>
<?php else: ?>oxrootid<?php endif; ?>" <?php if ($this->_tpl_vars['pcat']->selected): ?>SELECTED<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['pcat']->oxcategories__oxtitle->getRawValue())) ? $this->_run_mod_handler('oxtruncate', true, $_tmp, 33, "..", true) : smarty_modifier_oxtruncate($_tmp, 33, "..", true)); ?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_PARENTID'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_SORT'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input type="text" class="editinput" size="25" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxsort->fldmax_length; ?>
" name="editval[oxcategories__oxsort]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxsort->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_SORT'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_THUMB'), $this);?>

            </td>
            <td class="edittext">
                <input id="oxthumb" type="text" class="editinput" size="42" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxthumb->fldmax_length; ?>
" name="editval[oxcategories__oxthumb]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxthumb->value; ?>
">
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_THUMB'), $this);?>

            </td>
            <td class="edittext">
                <?php if (( ! ( $this->_tpl_vars['edit']->oxcategories__oxthumb->value == "nopic.jpg" || $this->_tpl_vars['edit']->oxcategories__oxthumb->value == "" || $this->_tpl_vars['edit']->oxcategories__oxthumb->value == "nopic_ico.jpg" ) )): ?>
                    <a href="Javascript:DeletePic('oxthumb');" class="delete left" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'item_delete')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>></a>
                <?php endif; ?>
                <input class="editinput" name="myfile[TC@oxcategories__oxthumb]" type="file"  size="26" <?php echo $this->_tpl_vars['readonly']; ?>
>
                (<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_FILE_UPLOAD'), $this);?>
 <?php echo $this->_tpl_vars['sMaxFormattedFileSize']; ?>
, <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_PICTURE_DIMENSIONS'), $this);?>
)
            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_ICON'), $this);?>

            </td>
            <td class="edittext">
                <input id="oxicon" type="text" class="editinput" size="42" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxicon->fldmax_length; ?>
" name="editval[oxcategories__oxicon]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxicon->value; ?>
">
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_ICON'), $this);?>

            </td>
            <td class="edittext">
                <?php if (( ! ( $this->_tpl_vars['edit']->oxcategories__oxicon->value == "nopic.jpg" || $this->_tpl_vars['edit']->oxcategories__oxicon->value == "" || $this->_tpl_vars['edit']->oxcategories__oxicon->value == "nopic_ico.jpg" ) )): ?>
                    <a href="Javascript:DeletePic('oxicon');" class="delete left" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'item_delete')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>></a>
                <?php endif; ?>
                <input class="editinput" name="myfile[CICO@oxcategories__oxicon]" type="file" size="26" >
                (<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_FILE_UPLOAD'), $this);?>
 <?php echo $this->_tpl_vars['sMaxFormattedFileSize']; ?>
, <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_PICTURE_DIMENSIONS'), $this);?>
)
            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_PROMOTION_ICON'), $this);?>

            </td>
            <td class="edittext">
                <input id="oxpromoicon" type="text" class="editinput" size="42" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxpromoicon->fldmax_length; ?>
" name="editval[oxcategories__oxpromoicon]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxpromoicon->value; ?>
">
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_PROMOTION_ICON'), $this);?>

            </td>
            <td class="edittext">
                <?php if (( ! ( $this->_tpl_vars['edit']->oxcategories__oxpromoicon->value == "nopic.jpg" || $this->_tpl_vars['edit']->oxcategories__oxpromoicon->value == "" || $this->_tpl_vars['edit']->oxcategories__oxpromoicon->value == "nopic_ico.jpg" ) )): ?>
                    <a href="Javascript:DeletePic('oxpromoicon');" class="delete left" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'item_delete')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>></a>
                <?php endif; ?>
                <input class="editinput" name="myfile[PICO@oxcategories__oxpromoicon]" type="file" size="26" >
                (<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_FILE_UPLOAD'), $this);?>
 <?php echo $this->_tpl_vars['sMaxFormattedFileSize']; ?>
, <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_MAX_PICTURE_DIMENSIONS'), $this);?>
)
            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_EXTLINK'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input type="text" class="editinput" size="42" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxextlink->fldmax_length; ?>
" name="editval[oxcategories__oxextlink]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxextlink->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_EXTLINK'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_TEMPLATE'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input type="text" class="editinput" size="42" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxtemplate->fldmax_length; ?>
" name="editval[oxcategories__oxtemplate]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxtemplate->value; ?>
" <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help.tpl", 'smarty_include_vars' => array('helpid' => 'article_template')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_TEMPLATE'), $this);?>

            </td>
        </tr>

        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_DEFSORT'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <select name="editval[oxcategories__oxdefsort]" class="editinput" onChange="JavaScript:SchnellSortManager(this);">
                    <option value=""><?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_NONE'), $this);?>
</option>
                    <?php $_from = $this->_tpl_vars['sortableFields']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field'] => $this->_tpl_vars['desc']):
?>
                        <?php $this->assign('ident', "GENERAL_ARTICLE_".($this->_tpl_vars['desc'])); ?>
                        <?php $this->assign('ident', ((is_array($_tmp=$this->_tpl_vars['ident'])) ? $this->_run_mod_handler('oxupper', true, $_tmp) : smarty_modifier_oxupper($_tmp))); ?>
                        <option value="<?php echo $this->_tpl_vars['desc']; ?>
" <?php if ($this->_tpl_vars['defsort'] == $this->_tpl_vars['desc']): ?>SELECTED<?php endif; ?>><?php echo ((is_array($_tmp=smarty_function_oxmultilang(array('ident' => $this->_tpl_vars['ident']), $this))) ? $this->_run_mod_handler('oxtruncate', true, $_tmp, 20, "..", true) : smarty_modifier_oxtruncate($_tmp, 20, "..", true));?>
</option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
                <input type="radio" class="editinput" name="editval[oxcategories__oxdefsortmode]" <?php if (! $this->_tpl_vars['defsort']): ?>disabled<?php endif; ?> value="0" <?php if ($this->_tpl_vars['edit']->oxcategories__oxdefsortmode->value == '0'): ?>checked<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_ASC'), $this);?>

                <input type="radio" class="editinput" name="editval[oxcategories__oxdefsortmode]" <?php if (! $this->_tpl_vars['defsort']): ?>disabled<?php endif; ?> value="1" <?php if ($this->_tpl_vars['edit']->oxcategories__oxdefsortmode->value == '1'): ?>checked<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_DESC'), $this);?>

                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_DEFSORT'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_PRICEFROMTILL'), $this);?>
 (<?php echo $this->_tpl_vars['oActCur']->sign; ?>
)
            </td>
            <td class="edittext" colspan="2">
                <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxpricefrom->fldmax_length; ?>
" name="editval[oxcategories__oxpricefrom]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxpricefrom->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>&nbsp;
                <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxpriceto->fldmax_length; ?>
" name="editval[oxcategories__oxpriceto]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxpriceto->value; ?>
" onchange="JavaScript:LockAssignment(this);" onkeyup="JavaScript:LockAssignment(this);" onmouseout="JavaScript:LockAssignment(this);" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_PRICEFROMTILL'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_VAT'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxcategories__oxvat->fldmax_length; ?>
" name="editval[oxcategories__oxvat]" value="<?php echo $this->_tpl_vars['edit']->oxcategories__oxvat->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_VAT'), $this);?>

            </td>
        </tr>
        <tr>
            <td class="edittext">
                <?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_SKIPDISCOUNTS'), $this);?>

            </td>
            <td class="edittext" colspan="2">
                <input type="hidden" name="editval[oxcategories__oxskipdiscounts]" value='0' <?php echo $this->_tpl_vars['readonly_fields']; ?>
>
                <input class="edittext" type="checkbox" name="editval[oxcategories__oxskipdiscounts]" value='1' <?php if ($this->_tpl_vars['edit']->oxcategories__oxskipdiscounts->value == 1): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly_fields']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_CATEGORY_MAIN_SKIPDISCOUNTS'), $this);?>

            </td>
        </tr>
    
    <tr>
        <td class="edittext">
        </td>
        <td class="edittext" colspan="2"><br>
            <input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'CATEGORY_MAIN_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'" <?php echo $this->_tpl_vars['readonly']; ?>
><br>
        </td>
    </tr>
    <tr>
        <td class="edittext">
        </td>
        <td class="edittext" colspan="2"><br>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "language_edit.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </td>
    </tr>
</table>

</td>
<!-- Anfang rechte Seite -->
<td valign="top" class="edittext" align="left" width="50%">
    
        <?php if ($this->_tpl_vars['oxid'] != "-1"): ?>
            <input <?php echo $this->_tpl_vars['readonly']; ?>
 type="button" name="assignArticle" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ASSIGNARTICLES'), $this);?>
" class="edittext" onclick="JavaScript:showDialog('&cl=category_main&aoc=1&oxid=<?php echo $this->_tpl_vars['oxid']; ?>
');" <?php if ($this->_tpl_vars['edit']->oxcategories__oxpriceto->value > 0): ?> disabled <?php endif; ?>>
        <?php endif; ?>
    