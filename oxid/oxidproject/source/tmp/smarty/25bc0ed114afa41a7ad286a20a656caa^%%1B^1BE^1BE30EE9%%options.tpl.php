<?php /* Smarty version 2.6.33, created on 2025-04-03 13:14:38
         compiled from page/checkout/inc/options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxscript', 'page/checkout/inc/options.tpl', 2, false),array('function', 'oxmultilang', 'page/checkout/inc/options.tpl', 26, false),array('block', 'oxifcontent', 'page/checkout/inc/options.tpl', 62, false),)), $this); ?>

    <?php echo smarty_function_oxscript(array('include' => "js/widgets/oxequalizer.min.js",'priority' => 10), $this);?>

    <?php echo smarty_function_oxscript(array('add' => "$(window).load(function(){ if( !isMobileDevice() ) { oxEqualizer.equalHeight( $( '.checkoutOptions .panel .panel-body' ) ); } });"), $this);?>


    <?php $this->assign('sColClass', "col-lg-6"); ?>
    <?php if ($this->_tpl_vars['oView']->getShowNoRegOption()): ?>
        <?php $this->assign('sColClass', "col-lg-4"); ?>
    <?php endif; ?>

    <div class="checkoutOptions">
		<div class="row">
			
				<?php if ($this->_tpl_vars['oView']->getShowNoRegOption()): ?>
					<div class="col-md-12 <?php echo $this->_tpl_vars['sColClass']; ?>
">
						<form action="<?php echo $this->_tpl_vars['oViewConf']->getSslSelfLink(); ?>
" method="post">
							<div class="hidden">
								<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

								<?php echo $this->_tpl_vars['oViewConf']->getNavFormParams(); ?>

								<input type="hidden" name="cl" value="user">
								<input type="hidden" name="fnc" value="">
								<input type="hidden" name="option" value="1">
							</div>

							<div class="panel panel-default" id="optionNoRegistration">
								<div class="panel-heading">
									<h3 class="panel-title"><?php echo smarty_function_oxmultilang(array('ident' => 'PURCHASE_WITHOUT_REGISTRATION'), $this);?>
</h3>
								</div>
								<div class="panel-body">
									
										<p><?php echo smarty_function_oxmultilang(array('ident' => 'DO_NOT_WANT_CREATE_ACCOUNT'), $this);?>
</p>
										<?php if ($this->_tpl_vars['oView']->isDownloadableProductWarning()): ?>
											<p class="errorMsg"><?php echo smarty_function_oxmultilang(array('ident' => 'MESSAGE_DOWNLOADABLE_PRODUCT'), $this);?>
</p>
										<?php endif; ?>
									
								</div>
								<div class="panel-footer text-right">
									<button class="btn btn-primary submitButton nextStep" type="submit"><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
 <i class="fa fa-caret-right"></i></button>
								</div>
							</div>
						</form>
					</div>
				<?php endif; ?>
			

			
				<div class="col-md-12 <?php echo $this->_tpl_vars['sColClass']; ?>
">
					<form action="<?php echo $this->_tpl_vars['oViewConf']->getSslSelfLink(); ?>
" method="post">
						<div class="hidden">
							<?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

							<?php echo $this->_tpl_vars['oViewConf']->getNavFormParams(); ?>

							<input type="hidden" name="cl" value="user">
							<input type="hidden" name="fnc" value="">
							<input type="hidden" name="option" value="3">
						</div>

						<div class="panel panel-default" id="optionRegistration">
							<div class="panel-heading">
								<h3 class="panel-title"><?php echo smarty_function_oxmultilang(array('ident' => 'OPEN_ACCOUNT'), $this);?>
</h3>
							</div>
							<div class="panel-body">
								
									<?php $this->_tag_stack[] = array('oxifcontent', array('ident' => 'oxregistrationdescription','object' => 'oCont')); $_block_repeat=true;smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
										<?php echo $this->_tpl_vars['oCont']->oxcontents__oxcontent->value; ?>

									<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_oxifcontent($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								
							</div>
							<div class="panel-footer text-right">
								<button class="btn btn-primary submitButton nextStep" type="submit"><?php echo smarty_function_oxmultilang(array('ident' => 'NEXT'), $this);?>
 <i class="fa fa-caret-right"></i></button>
							</div>
						</div>
					</form>
				</div>
			

			
				<div class="col-md-12 <?php echo $this->_tpl_vars['sColClass']; ?>
">
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form/login.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</div>
			
		</div>
    </div>
