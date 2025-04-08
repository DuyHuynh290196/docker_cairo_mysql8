<?php /* Smarty version 2.6.33, created on 2025-03-28 03:25:43
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxmultilang', 'header.tpl', 5, false),array('modifier', 'lower', 'header.tpl', 7, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html id="top">
<head>
    
        <title><?php echo smarty_function_oxmultilang(array('ident' => 'NAVIGATION_TITLE'), $this);?>
</title>
        <link rel="stylesheet" href="<?php echo $this->_tpl_vars['oViewConf']->getResourceUrl(); ?>
nav.css">
        <link rel="stylesheet" href="<?php echo $this->_tpl_vars['oViewConf']->getResourceUrl(); ?>
colors_<?php echo ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getEdition())) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
.css">
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['charset']; ?>
">
    
</head>
<body>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'include/header_links.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <div class="version">
        <b>
            <?php echo $this->_tpl_vars['oView']->getShopFullEdition(); ?>

            <?php echo $this->_tpl_vars['oView']->getShopVersion(); ?>

        </b>
    </div>
</body>
</html>