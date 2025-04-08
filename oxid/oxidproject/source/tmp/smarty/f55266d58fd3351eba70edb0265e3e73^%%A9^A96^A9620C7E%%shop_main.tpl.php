<?php /* Smarty version 2.6.33, created on 2025-04-01 12:23:23
         compiled from shop_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'shop_main.tpl', 1, false),array('modifier', 'count_characters', 'shop_main.tpl', 15, false),array('function', 'oxmultilang', 'shop_main.tpl', 84, false),array('function', 'oxinputhelp', 'shop_main.tpl', 109, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
function loadLang(obj)
{
    var langvar = document.getElementById("agblang");
    if (langvar != null )
        langvar.value = obj.value;
    document.myedit.submit();
}
function setSmtpField()
{
    var sPass = '';
    for ( var i = 0; i < <?php echo ((is_array($_tmp=$this->_tpl_vars['edit']->oxshops__oxsmtppwd->value)) ? $this->_run_mod_handler('count_characters', true, $_tmp) : smarty_modifier_count_characters($_tmp)); ?>
; i++ ) {
        sPass += ' ';
    }
    document.getElementsByName( 'oxsmtppwd' )[0].value = sPass;
    document.getElementsByName( 'oxsmtppwd' )[0].userValueSet = false;
}
function unsetSmtpField()
{
    if ( !document.getElementsByName( 'oxsmtppwd' )[0].userValueSet ) {
        document.getElementsByName( 'oxsmtppwd' )[0].value = '';
    }
}

function modSmtpField()
{
    if ( !document.getElementsByName( 'oxsmtppwd' )[0].userValueSet ) {
        document.getElementsByName( 'oxsmtppwd' )[0].value = '';
        document.getElementsByName( 'oxsmtppwd' )[0].userValueSet = true;
    }
}
function editThis(sID)
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = '';
    oTransfer.cl.value = top.oxid.admin.getClass( sID );

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.updatenav.value = 1;
    oSearch.submit();
}
window.onload = function ()
{
    <?php if ($this->_tpl_vars['updatelist'] == 1): ?>
        top.oxid.admin.updateList('<?php echo $this->_tpl_vars['oxid']; ?>
');
    <?php endif; ?>

    setSmtpField();

    <?php if ($this->_tpl_vars['updatenav']): ?>
        top.oxid.admin.reloadNavigation('<?php echo $this->_tpl_vars['oxid']; ?>
');
    <?php endif; ?>

    var oField = top.oxid.admin.getLockTarget();
    oField.onchange = oField.onkeyup = oField.onmouseout = top.oxid.admin.unlockSave;
}
//-->
</script>

<?php if ($this->_tpl_vars['readonly']): ?>
    <?php $this->assign('readonly', 'readonly disabled'); ?>
<?php else: ?>
    <?php $this->assign('readonly', ""); ?>
<?php endif; ?>

<form name="transfer" id="transfer" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="cl" value="shop_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="<?php echo $this->_tpl_vars['oViewConf']->getActiveShopId(); ?>
">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="<?php echo $this->_tpl_vars['editlanguage']; ?>
">
</form>

<?php if ($this->_tpl_vars['sMandateWarning']): ?>
  <div class="errorbox"><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_MANDATE_WARNING'), $this);?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['sMaxShopWarning']): ?>
   <div class="errorbox"><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_MAXSHOP_WARNING'), $this);?>
</div>
<?php endif; ?>

<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post" onSubmit="unsetSmtpField()">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="cl" value="shop_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="editval[oxshops__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">

<table border="0" width="98%">
<tr>
    <td valign="top" class="edittext">
        <table cellspacing="0" cellpadding="0" border="0">
        
            <tr>
             <td class="edittext"  <?php if (! ( $this->_tpl_vars['edit']->oxshops__oxproductive->value )): ?>style="border: 3px Red; border-style: solid none solid solid;"<?php endif; ?>>
                <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_PRODUCTIVE'), $this);?>

             </td>
             <td class="edittext" <?php if (! ( $this->_tpl_vars['edit']->oxshops__oxproductive->value )): ?>style="border: 3px Red; border-style: solid solid solid none;"<?php endif; ?>>
                <input type=checkbox name=editval[oxshops__oxproductive] value=true  <?php if (( $this->_tpl_vars['edit']->oxshops__oxproductive->value )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_PRODUCTIVE'), $this);?>

             </td>
            </tr>
            <tr>
             <td class="edittext" >
                <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ACTIVE'), $this);?>

             </td>
             <td class="edittext" >
                <input type=checkbox name=editval[oxshops__oxactive] value=true  <?php if (( $this->_tpl_vars['edit']->oxshops__oxactive->value )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ACTIVE'), $this);?>

             </td>
            </tr>
            <tr>
                <td class="edittext" >
                   <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_COMPANY'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxcompany->fldmax_length; ?>
" name="editval[oxshops__oxcompany]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxcompany->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_COMPANY'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" width="100">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_NAME'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="10" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxfname->fldmax_length; ?>
" name="editval[oxshops__oxfname]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxfname->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <input type="text" class="editinput" size="21" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxlname->fldmax_length; ?>
" name="editval[oxshops__oxlname]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxlname->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_NAME'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_STREET'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxstreet->fldmax_length; ?>
" name="editval[oxshops__oxstreet]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxstreet->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_STREET'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ZIPCITY'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="5" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxzip->fldmax_length; ?>
" name="editval[oxshops__oxzip]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxzip->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <input type="text" class="editinput" size="26" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxcity->fldmax_length; ?>
" name="editval[oxshops__oxcity]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxcity->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_ZIPCITY'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_COUNTRY'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxcountry->fldmax_length; ?>
" name="editval[oxshops__oxcountry]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxcountry->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_COUNTRY'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TELEPHONE'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxtelefon->fldmax_length; ?>
" name="editval[oxshops__oxtelefon]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxtelefon->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_TELEPHONE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FAX'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxtelefax->fldmax_length; ?>
" name="editval[oxshops__oxtelefax]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxtelefax->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_FAX'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext">
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_URL'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxurl->fldmax_length; ?>
" name="editval[oxshops__oxurl]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxurl->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_GENERAL_URL'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_BANKNAME'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxbankname->fldmax_length; ?>
" name="editval[oxshops__oxbankname]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxbankname->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_BANKNAME'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_BANKCODE'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxbankcode->fldmax_length; ?>
" name="editval[oxshops__oxbankcode]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxbankcode->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_BANKCODE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_BANKNUMBER'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxbanknumber->fldmax_length; ?>
" name="editval[oxshops__oxbanknumber]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxbanknumber->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_BANKNUMBER'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_BICCODE'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxbiccode->fldmax_length; ?>
" name="editval[oxshops__oxbiccode]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxbiccode->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_BICCODE'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_IBANNUMBER'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxibannumber->fldmax_length; ?>
" name="editval[oxshops__oxibannumber]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxibannumber->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_IBANNUMBER'), $this);?>

                </td>
            </tr>

            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_VATNUMBER'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" name="editval[oxshops__oxvatnumber]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxvatnumber->value; ?>
" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxvatnumber->fldmax_length; ?>
" class="editinput" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_VATNUMBER'), $this);?>

                </td>
            </tr>

            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_TAXNUMBER'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" name="editval[oxshops__oxtaxnumber]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxtaxnumber->value; ?>
" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxtaxnumber->fldmax_length; ?>
" class="editinput" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_TAXNUMBER'), $this);?>

                </td>
            </tr>

            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_HRBNR'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxhrbnr->fldmax_length; ?>
" name="editval[oxshops__oxhrbnr]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxhrbnr->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_HRBNR'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_COURT'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxcourt->fldmax_length; ?>
" name="editval[oxshops__oxcourt]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxcourt->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_COURT'), $this);?>

                </td>
            </tr>
        
        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left">
        <table cellspacing="0" cellpadding="0" border="0">
        
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include/shop_information.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <tr>
                <td class="edittext" >
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_SHOPNAME'), $this);?>

                </td>
                <td class="edittext">
                    <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxname->fldmax_length; ?>
" name="editval[oxshops__oxname]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxname->value; ?>
" id="oLockTarget" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_SHOPNAME'), $this);?>

                </td>
            </tr>
            <?php if (! $this->_tpl_vars['IsOXDemoShop']): ?>
            <tr>
                <td class="edittext" >
                            <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_SMTPSERVER'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxsmtp->fldmax_length; ?>
" name="editval[oxshops__oxsmtp]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxsmtp->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_SMTPSERVER'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                            <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_SMTPUSER'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxsmtpuser->fldmax_length; ?>
" name="editval[oxshops__oxsmtpuser]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxsmtpuser->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_SMTPUSER'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                            <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_SMTPPASSWORD'), $this);?>

                </td>
                <td class="edittext">
                <input type="password" name="oxsmtppwd" size="35" maxlength="50" class="editinput" <?php echo $this->_tpl_vars['readonly']; ?>
 onfocus="modSmtpField()" onChange="modSmtpField()">
                <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_SMTPPWUNSET'), $this);?>

                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_SMTPPASSWORD'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                            <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_INFOEMAIL'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxinfoemail->fldmax_length; ?>
" name="editval[oxshops__oxinfoemail]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxinfoemail->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_INFOEMAIL'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                            <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_ORDEREMAIL'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxorderemail->fldmax_length; ?>
" name="editval[oxshops__oxorderemail]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxorderemail->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_ORDEREMAIL'), $this);?>

                </td>
            </tr>
            <tr>
                <td class="edittext" >
                            <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_OWNEREMAIL'), $this);?>

                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxowneremail->fldmax_length; ?>
" name="editval[oxshops__oxowneremail]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxowneremail->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_OWNEREMAIL'), $this);?>

                </td>
            </tr>
            <?php endif; ?>
        

          <tr>
            <td colspan="2">
              <FIELDSET id=fldLayout>
                <LEGEND id=lgdLayout>
                  <?php if ($this->_tpl_vars['languages']): ?>
                  <select name="subjlang" class="editinput" onchange="Javascript:loadLang(this)" <?php echo $this->_tpl_vars['readonly']; ?>
>
                      <?php $_from = $this->_tpl_vars['languages']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                        <option value="<?php echo $this->_tpl_vars['key']; ?>
"<?php if ($this->_tpl_vars['subjlang'] == $this->_tpl_vars['key']): ?> SELECTED<?php endif; ?>><?php echo $this->_tpl_vars['item']->name; ?>
</option>
                      <?php endforeach; endif; unset($_from); ?>
                  </select>
                  <?php endif; ?>
                </LEGEND>

              <table cellspacing="0" cellpadding="1" border="0">
                
                    <tr>
                      <td class="edittext" >
                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_ORDERSUBJECT'), $this);?>

                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxordersubject->fldmax_length; ?>
" name="editval[oxshops__oxordersubject]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxordersubject->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_ORDERSUBJECT'), $this);?>

                      </td>
                    </tr>
                    <tr>
                      <td class="edittext" >
                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_REGISTERSUBJECT'), $this);?>

                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxregistersubject->fldmax_length; ?>
" name="editval[oxshops__oxregistersubject]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxregistersubject->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_REGISTERSUBJECT'), $this);?>

                      </td>
                    </tr>
                    <tr>
                      <td class="edittext" >
                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_FORGOTPWDSUBJECT'), $this);?>

                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxforgotpwdsubject->fldmax_length; ?>
" name="editval[oxshops__oxforgotpwdsubject]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxforgotpwdsubject->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_FORGOTPWDSUBJECT'), $this);?>

                      </td>
                    </tr>
                    <tr>
                      <td class="edittext" >
                        <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_SENT_NOW_SUBJECT'), $this);?>

                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="35" maxlength="<?php echo $this->_tpl_vars['edit']->oxshops__oxsendednowsubject->fldmax_length; ?>
" name="editval[oxshops__oxsendednowsubject]" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxsendednowsubject->value; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_MAIN_NOWSENDEDSUBJECT'), $this);?>

                    </tr>
                
              </table>
              </FIELDSET>
            </td>
          </tr>
          <tr>
            <td class="edittext"></td>
            <td class="edittext"><br>
              <input type="submit" class="edittext" id="oLockButton" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'" <?php if ($this->_tpl_vars['oxid'] == -1): ?>disabled<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
            </td>
          </tr>
        </table>

    </td>
    </tr>
</table>

</form>

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