<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:43
         compiled from nav_frame.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'nav_frame.tpl', 5, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
    <title><?php echo smarty_function_oxmultilang(array('ident' => 'GENERAL_ADMIN_TITLE'), $this);?>
</title>
</head>

<!-- frames -->
<frameset rows="*" border="0">
    <frame src="<?php echo $this->_tpl_vars['oViewConf']->getSelfLink(); ?>
&cl=navigation&item=navigation.tpl" name="adminnav" id="adminnav" frameborder="0" scrolling="auto" noresize marginwidth="0" marginheight="0">
</frameset>

</html>