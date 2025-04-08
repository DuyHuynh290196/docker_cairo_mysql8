<?php /* Smarty version 2.6.33, created on 2025-04-03 13:11:57
         compiled from shop_rdfa.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'oxmultilangassign', 'shop_rdfa.tpl', 1, false),array('function', 'oxmultilang', 'shop_rdfa.tpl', 41, false),array('function', 'oxinputhelp', 'shop_rdfa.tpl', 46, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "headitem.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp='GENERAL_ADMIN_TITLE')) ? $this->_run_mod_handler('oxmultilangassign', true, $_tmp) : smarty_modifier_oxmultilangassign($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
function _groupExp(el) {
    var _cur = el.parentNode;

    if (_cur.className == "exp") _cur.className = "";
      else _cur.className = "exp";
}
//-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include/rdfa_script.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['readonly']): ?>
    <?php $this->assign('readonly', 'readonly disabled'); ?>
<?php else: ?>
    <?php $this->assign('readonly', ""); ?>
<?php endif; ?>

<?php $this->assign('aContents', $this->_tpl_vars['oView']->getContentList()); ?>
<?php $this->assign('customers', $this->_tpl_vars['oView']->getCustomers()); ?>

<form name="transfer" id="transfer" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="actshop" value="<?php echo $this->_tpl_vars['oViewConf']->getActiveShopId(); ?>
">
    <input type="hidden" name="cl" value="shop_rdfa">
    <input type="hidden" name="editlanguage" value="<?php echo $this->_tpl_vars['editlanguage']; ?>
">
</form>
<form name="myedit" id="myedit" action="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
" method="post">
    <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

    <input type="hidden" name="cl" value="shop_rdfa">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="<?php echo $this->_tpl_vars['oxid']; ?>
">
    <input type="hidden" name="editval[oxshops__oxid]" value="<?php echo $this->_tpl_vars['oxid']; ?>
">


    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_TECH_CONFIG'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name="confbools[blRDFaEmbedding]" value="false">
                    <input type=checkbox name="confbools[blRDFaEmbedding]" value="true"  <?php if (( $this->_tpl_vars['confbools']['blRDFaEmbedding'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_EMBEDDING'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_EMBEDDING'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[sRDFaBusinessEntityLoc]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php $_from = $this->_tpl_vars['aContents']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                               <option value="<?php echo $this->_tpl_vars['item']->oxcontents__oxloadid->value; ?>
" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessEntityLoc'] == $this->_tpl_vars['item']->oxcontents__oxloadid->value )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']->oxcontents__oxtitle->value; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_CONTENT_OFFERER'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_CONTENT_OFFERER'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <div class="spacer"></div>
                <div><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_ASSIGN_PAYMENT'), $this);?>
</div>
                <dt>
                    <select name="confstrs[sRDFaPaymentChargeSpecLoc]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php $_from = $this->_tpl_vars['aContents']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                               <option value="<?php echo $this->_tpl_vars['item']->oxcontents__oxloadid->value; ?>
" <?php if (( $this->_tpl_vars['confstrs']['sRDFaPaymentChargeSpecLoc'] == $this->_tpl_vars['item']->oxcontents__oxloadid->value )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']->oxcontents__oxtitle->value; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_CONTENT_PAYMENT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_CONTENT_PAYMENT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <div class="spacer"></div>
                <div><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_ASSIGN_DELIVERY'), $this);?>
</div>
                <dt>
                    <select name="confstrs[sRDFaDeliveryChargeSpecLoc]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <?php $_from = $this->_tpl_vars['aContents']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                               <option value="<?php echo $this->_tpl_vars['item']->oxcontents__oxloadid->value; ?>
" <?php if (( $this->_tpl_vars['confstrs']['sRDFaDeliveryChargeSpecLoc'] == $this->_tpl_vars['item']->oxcontents__oxloadid->value )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']->oxcontents__oxtitle->value; ?>
</option>
                        <?php endforeach; endif; unset($_from); ?>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_CONTENT_DELIVERY'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_CONTENT_DELIVERY'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[iRDFaVAT]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="0" <?php if (( $this->_tpl_vars['confstrs']['iRDFaVAT'] == 0 )): ?>selected<?php endif; ?>>-</option>
                        <option value="1" <?php if (( $this->_tpl_vars['confstrs']['iRDFaVAT'] == 1 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_VAT_INC'), $this);?>
</option>
                        <option value="2" <?php if (( $this->_tpl_vars['confstrs']['iRDFaVAT'] == 2 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_VAT_EX'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_VAT'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_VAT'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[iRDFaPriceValidity]" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="0" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 0 )): ?>selected<?php endif; ?>>-</option>
                        <option value="1" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 1 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_1_DAY'), $this);?>
</option>
                        <option value="3" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 3 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_3_DAYS'), $this);?>
</option>
                        <option value="7" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 7 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_7_DAYS'), $this);?>
</option>
                        <option value="14" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 14 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_14_DAYS'), $this);?>
</option>
                        <option value="30" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 30 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_30_DAYS'), $this);?>
</option>
                        <option value="178" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 178 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_178_DAYS'), $this);?>
</option>
                        <option value="356" <?php if (( $this->_tpl_vars['confstrs']['iRDFaPriceValidity'] == 356 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_356_DAYS'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_DURATION_PRICES'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_DURATION_PRICES'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>



    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_DATA_OFFERER'), $this);?>
</b></a>
            <dl>
                <div><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_DATA_MASTER'), $this);?>
</div>
                <dt>
                    <input type="text" class="editinput" size="35" maxlength="128" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxcompany->value; ?>
" style="color: #777777;" readonly>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_MAIN_COMPANY'), $this);?>

                </dd>
                <div class="spacer"></div>
                <dt>
                    <input type="text" class="editinput" size="35" maxlength="128" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxstreet->value; ?>
" style="color: #777777;" readonly>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_STREETNUM'), $this);?>

                </dd>
                <div class="spacer"></div>
                <dt>
                    <input type="text" class="editinput" size="5" maxlength="255" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxzip->value; ?>
" style="color: #777777;" readonly>
                    <input type="text" class="editinput" size="26" maxlength="255" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxcity->value; ?>
" style="color: #777777;" readonly>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ZIPCITY'), $this);?>

                </dd>
                <div class="spacer"></div>
                <dt>
                    <input type="text" class="editinput" size="35" maxlength="128" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxcountry->value; ?>
" style="color: #777777;" readonly>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_COUNTRY'), $this);?>

                </dd>
                <div class="spacer"></div>
                <dt>
                    <input type="text" class="editinput" size="35" maxlength="128" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxtelefon->value; ?>
" style="color: #777777;" readonly>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_TELEPHONE'), $this);?>

                </dd>
                <div class="spacer"></div>
                <dt>
                    <input type="text" class="editinput" size="35" maxlength="128" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxtelefax->value; ?>
" style="color: #777777;" readonly>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_FAX'), $this);?>

                </dd>
                <div class="spacer"></div>
                <dt>
                    <input type="text" class="editinput" size="35" maxlength="128" value="<?php echo $this->_tpl_vars['edit']->oxshops__oxurl->value; ?>
" style="color: #777777;" readonly>
                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_URL'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <div><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_DATA_EXTENDED'), $this);?>
</div>
                <dt>
                    <input type=text class="txt" name=confstrs[sRDFaLogoUrl] value="<?php echo $this->_tpl_vars['confstrs']['sRDFaLogoUrl']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_LOGO_URL'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_LOGO_URL'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sRDFaLongitude] value="<?php echo $this->_tpl_vars['confstrs']['sRDFaLongitude']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_GEO_LONGITUDE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_GEO_LONGITUDE'), $this);?>

                </dd>
                <div class="spacer"></div>
                <dt>
                    <input type=text class="txt" name=confstrs[sRDFaLatitude] value="<?php echo $this->_tpl_vars['confstrs']['sRDFaLatitude']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_GEO_LATITUDE'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_GEO_LATITUDE'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sRDFaGLN] value="<?php echo $this->_tpl_vars['confstrs']['sRDFaGLN']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_GLN'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_GLN'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sRDFaNAICS] value="<?php echo $this->_tpl_vars['confstrs']['sRDFaNAICS']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_NAICS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_NAICS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sRDFaISIC] value="<?php echo $this->_tpl_vars['confstrs']['sRDFaISIC']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_ISIC'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_ISIC'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[sRDFaDUNS] value="<?php echo $this->_tpl_vars['confstrs']['sRDFaDUNS']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_DUNS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_DUNS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>


         </div>
    </div>



    <div class="groupExp">
        <div>
            <a href="#" onclick="_groupExp(this);return false;" class="rc"><b><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_GLOBAL_OFFERING_DATA'), $this);?>
</b></a>
            <dl>
                <dt>
                    <input type=hidden name="confbools[blShowRDFaProductStock]" value="false">
                    <input type=checkbox name="confbools[blShowRDFaProductStock]" value="true"  <?php if (( $this->_tpl_vars['confbools']['blShowRDFaProductStock'] )): ?>checked<?php endif; ?> <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_SHOW_PRODUCTSTOCK'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_SHOW_PRODUCTSTOCK'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>
            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRDFaMinRating] value="<?php echo $this->_tpl_vars['confstrs']['iRDFaMinRating']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_RATING_MIN'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_RATING_MIN'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <input type=text class="txt" name=confstrs[iRDFaMaxRating] value="<?php echo $this->_tpl_vars['confstrs']['iRDFaMaxRating']; ?>
" <?php echo $this->_tpl_vars['readonly']; ?>
>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_RATING_MAX'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_RATING_MAX'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[iRDFaCondition]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="" <?php if (( $this->_tpl_vars['confstrs']['iRDFaCondition'] == "" ) || ! $this->_tpl_vars['confstrs']['iRDFaCondition']): ?>selected<?php endif; ?>>-</option>
                        <option value="new" <?php if (( $this->_tpl_vars['confstrs']['iRDFaCondition'] == 'new' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COND_NEW'), $this);?>
</option>
                        <option value="used" <?php if (( $this->_tpl_vars['confstrs']['iRDFaCondition'] == 'used' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COND_USED'), $this);?>
</option>
                        <option value="refurbished" <?php if (( $this->_tpl_vars['confstrs']['iRDFaCondition'] == 'refurbished' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COND_REFURBISHED'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_COND'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COND'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[sRDFaBusinessFnc]" class="select" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == "" )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_NONE'), $this);?>
</option>
                        <option value="Sell" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == 'Sell' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_SELL'), $this);?>
</option>
                        <option value="LeaseOut" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == 'LeaseOut' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_LEASEOUT'), $this);?>
</option>
                        <option value="Repair" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == 'Repair' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_REPAIR'), $this);?>
</option>
                        <option value="Maintain" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == 'Maintain' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_MAINTAIN'), $this);?>
</option>
                        <option value="ConstructionInstallation" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == 'ConstructionInstallation' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_CONSTINST'), $this);?>
</option>
                        <option value="ProvideService" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == 'ProvideService' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_SERVICE'), $this);?>
</option>
                        <option value="Dispose" <?php if (( $this->_tpl_vars['confstrs']['sRDFaBusinessFnc'] == 'Dispose' )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC_DISPOSE'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_FNC'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_FNC'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select class="select" multiple size="4" name=confarrs[aRDFaCustomers][] <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="Enduser" <?php if ($this->_tpl_vars['customers']['Enduser'] == 1): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COSTUMER_ENDUSER'), $this);?>
</option>
                        <option value="Reseller" <?php if ($this->_tpl_vars['customers']['Reseller'] == 1): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COSTUMER_RESELLER'), $this);?>
</option>
                        <option value="Business" <?php if ($this->_tpl_vars['customers']['Business'] == 1): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COSTUMER_BUSINESS'), $this);?>
</option>
                        <option value="PublicInstitution" <?php if ($this->_tpl_vars['customers']['PublicInstitution'] == 1): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COSTUMER_PUBLIC'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_COSTUMER'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_COSTUMER'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

            <dl>
                <dt>
                    <select name="confstrs[iRDFaOfferingValidity]" <?php echo $this->_tpl_vars['readonly']; ?>
>
                        <option value="0" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 0 )): ?>selected<?php endif; ?>>-</option>
                        <option value="1" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 1 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_1_DAY'), $this);?>
</option>
                        <option value="3" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 3 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_3_DAYS'), $this);?>
</option>
                        <option value="7" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 7 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_7_DAYS'), $this);?>
</option>
                        <option value="14" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 14 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_14_DAYS'), $this);?>
</option>
                        <option value="30" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 30 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_30_DAYS'), $this);?>
</option>
                        <option value="178" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 178 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_178_DAYS'), $this);?>
</option>
                        <option value="356" <?php if (( $this->_tpl_vars['confstrs']['iRDFaOfferingValidity'] == 356 )): ?>selected<?php endif; ?>><?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_356_DAYS'), $this);?>
</option>
                    </select>
                    <?php echo smarty_function_oxinputhelp(array('ident' => 'HELP_SHOP_RDFA_DURATION_OFFERINGS'), $this);?>

                </dt>
                <dd>
                    <?php echo smarty_function_oxmultilang(array('ident' => 'SHOP_RDFA_DURATION_OFFERINGS'), $this);?>

                </dd>
                <div class="spacer"></div>
            </dl>

         </div>
    </div>


<input type="submit" class="edittext" name="save" value="<?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_SAVE'), $this);?>
" onClick="Javascript:document.myedit.fnc.value='save'" <?php echo $this->_tpl_vars['readonly']; ?>
>

</form>
<br><br>




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