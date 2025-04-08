<?php /* Smarty version 2.6.33, created on 2025-04-03 13:12:12
         compiled from widget/reviews/reviews.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'oxscript', 'widget/reviews/reviews.tpl', 1, false),array('function', 'oxmultilang', 'widget/reviews/reviews.tpl', 9, false),array('function', 'oxgetseourl', 'widget/reviews/reviews.tpl', 11, false),array('function', 'oxid_include_dynamic', 'widget/reviews/reviews.tpl', 29, false),array('modifier', 'cat', 'widget/reviews/reviews.tpl', 11, false),array('modifier', 'truncate', 'widget/reviews/reviews.tpl', 90, false),array('modifier', 'date_format', 'widget/reviews/reviews.tpl', 98, false),array('modifier', 'default', 'widget/reviews/reviews.tpl', 113, false),)), $this); ?>
<?php echo smarty_function_oxscript(array('include' => "js/libs/jqBootstrapValidation.min.js",'priority' => 10), $this);?>

<?php echo smarty_function_oxscript(array('add' => "$('input,select,textarea').not('[type=submit]').jqBootstrapValidation();"), $this);?>

<div id="review">
    <div class="panel-group" id="review_form_accordion">
        <div class="panel panel-default">
            <div class="panel-heading toggle-accordion">
                <div class="h4 panel-title">
                    <?php if ($this->_tpl_vars['oxcmp_user']): ?>
                        <i class="fa fa-pencil"></i> <a id="writeNewReview" data-toggle="collapse" data-parent="#review_form_accordion" href="#review_form"><?php echo smarty_function_oxmultilang(array('ident' => 'WRITE_REVIEW'), $this);?>
</a>
                    <?php else: ?>
                        <i class="fa fa-user"></i> <a id="reviewsLogin" rel="nofollow" href="<?php echo smarty_function_oxgetseourl(array('ident' => ((is_array($_tmp=$this->_tpl_vars['oViewConf']->getSelfLink())) ? $this->_run_mod_handler('cat', true, $_tmp, "cl=account") : smarty_modifier_cat($_tmp, "cl=account")),'params' => ((is_array($_tmp=((is_array($_tmp="anid=".($this->_tpl_vars['oDetailsProduct']->oxarticles__oxnid->value))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;sourcecl=details") : smarty_modifier_cat($_tmp, "&amp;sourcecl=details")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()) : smarty_modifier_cat($_tmp, $this->_tpl_vars['oViewConf']->getNavUrlParams()))), $this);?>
"><?php echo smarty_function_oxmultilang(array('ident' => 'MESSAGE_LOGIN_TO_WRITE_REVIEW'), $this);?>
</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($this->_tpl_vars['oxcmp_user']): ?>
                <div id="review_form" class="panel-collapse collapse">
                    <div class="panel-body">
                        
                            <form action="<?php echo $this->_tpl_vars['oViewConf']->getSelfActionLink(); ?>
" method="post" id="rating" class="form-horizontal" novalidate="novalidate">
                                <div id="writeReview">
                                    <div class="hidden">
                                        <?php if ($this->_tpl_vars['oView']->canRate()): ?>
                                            <input id="productRating" type="hidden" name="artrating" value="0">
                                            <input id="recommListRating" type="hidden" name="recommlistrating" value="0">
                                        <?php endif; ?>
                                        <?php echo $this->_tpl_vars['oViewConf']->getHiddenSid(); ?>

                                        <?php echo $this->_tpl_vars['oViewConf']->getNavFormParams(); ?>

                                        <?php echo smarty_function_oxid_include_dynamic(array('file' => "form/formparams.tpl"), $this);?>

                                        <input type="hidden" name="fnc" value="savereview">
                                        <input type="hidden" name="cl" value="<?php echo $this->_tpl_vars['oViewConf']->getTopActiveClassName(); ?>
">
                                        <?php if ($this->_tpl_vars['oDetailsProduct']): ?>
                                            <input type="hidden" name="anid" value="<?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxid->value; ?>
">
                                        <?php else: ?>
                                            <?php $this->assign('_actvrecommlist', $this->_tpl_vars['oView']->getActiveRecommList()); ?>
                                            <input type="hidden" name="recommid" value="<?php echo $this->_tpl_vars['_actvrecommlist']->oxrecommlists__oxid->value; ?>
">
                                        <?php endif; ?>

                                        <?php if ($this->_tpl_vars['sReviewUserHash']): ?>
                                            <input type="hidden" name="reviewuserhash" value="<?php echo $this->_tpl_vars['sReviewUserHash']; ?>
">
                                        <?php endif; ?>
                                    </div>

                                    
                                        <?php if ($this->_tpl_vars['oView']->canRate()): ?>
                                            <ul id="reviewRating" class="rating list-inline">
                                                <?php unset($this->_sections['star']);
$this->_sections['star']['name'] = 'star';
$this->_sections['star']['start'] = (int)1;
$this->_sections['star']['loop'] = is_array($_loop=6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['star']['show'] = true;
$this->_sections['star']['max'] = $this->_sections['star']['loop'];
$this->_sections['star']['step'] = 1;
if ($this->_sections['star']['start'] < 0)
    $this->_sections['star']['start'] = max($this->_sections['star']['step'] > 0 ? 0 : -1, $this->_sections['star']['loop'] + $this->_sections['star']['start']);
else
    $this->_sections['star']['start'] = min($this->_sections['star']['start'], $this->_sections['star']['step'] > 0 ? $this->_sections['star']['loop'] : $this->_sections['star']['loop']-1);
if ($this->_sections['star']['show']) {
    $this->_sections['star']['total'] = min(ceil(($this->_sections['star']['step'] > 0 ? $this->_sections['star']['loop'] - $this->_sections['star']['start'] : $this->_sections['star']['start']+1)/abs($this->_sections['star']['step'])), $this->_sections['star']['max']);
    if ($this->_sections['star']['total'] == 0)
        $this->_sections['star']['show'] = false;
} else
    $this->_sections['star']['total'] = 0;
if ($this->_sections['star']['show']):

            for ($this->_sections['star']['index'] = $this->_sections['star']['start'], $this->_sections['star']['iteration'] = 1;
                 $this->_sections['star']['iteration'] <= $this->_sections['star']['total'];
                 $this->_sections['star']['index'] += $this->_sections['star']['step'], $this->_sections['star']['iteration']++):
$this->_sections['star']['rownum'] = $this->_sections['star']['iteration'];
$this->_sections['star']['index_prev'] = $this->_sections['star']['index'] - $this->_sections['star']['step'];
$this->_sections['star']['index_next'] = $this->_sections['star']['index'] + $this->_sections['star']['step'];
$this->_sections['star']['first']      = ($this->_sections['star']['iteration'] == 1);
$this->_sections['star']['last']       = ($this->_sections['star']['iteration'] == $this->_sections['star']['total']);
?>
                                                    <li class="s<?php echo $this->_sections['star']['index']; ?>
" data-rate-value="<?php echo $this->_sections['star']['index']; ?>
">
                                                        <a class="ox-write-review ox-rateindex-<?php echo $this->_sections['star']['index']; ?>
 text-warning" title="<?php echo $this->_sections['star']['index']; ?>
 <?php if ($this->_sections['star']['index'] == 1): ?><?php echo smarty_function_oxmultilang(array('ident' => 'STAR'), $this);?>
<?php else: ?><?php echo smarty_function_oxmultilang(array('ident' => 'STARS'), $this);?>
<?php endif; ?>">
                                                            <i class="fa fa-star-o"></i>
                                                        </a>
                                                    </li>
                                                <?php endfor; endif; ?>
                                            </ul>
                                        <?php endif; ?>
                                    

                                    
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <textarea  rows="15" name="rvw_txt" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    

                                    
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <button id="reviewSave" type="submit" class="submitButton btn btn-primary"><?php echo smarty_function_oxmultilang(array('ident' => 'SAVE_RATING_AND_REVIEW'), $this);?>
</button>
                                            </div>
                                        </div>
                                    
                                </div>
                            </form>
                        
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($this->_tpl_vars['oView']->getReviews()): ?>
        <div class="spacer"></div>
        <div class="reviews-landscape">
            <?php $_from = $this->_tpl_vars['oView']->getReviews(); if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['ReviewsCounter'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ReviewsCounter']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['review']):
        $this->_foreach['ReviewsCounter']['iteration']++;
?>
                <div class="panel panel-default" id="reviewName_<?php echo $this->_foreach['ReviewsCounter']['iteration']; ?>
" itemprop="review" itemscope itemtype="http://schema.org/Review">
                                        <div class="hidden">
                        <span itemprop="itemreviewed"><?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxtitle->value; ?>
 <?php echo $this->_tpl_vars['oDetailsProduct']->oxarticles__oxvarselect->value; ?>
</span>
                        <span itemprop="name"><?php echo ((is_array($_tmp=$this->_tpl_vars['review']->oxreviews__oxtext->value)) ? $this->_run_mod_handler('truncate', true, $_tmp, 100) : smarty_modifier_truncate($_tmp, 100)); ?>
</span>
                    </div>
                    
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-6 writer">
                                    <span itemprop="author"><?php echo $this->_tpl_vars['review']->oxuser__oxfname->value; ?>
</span> <?php echo smarty_function_oxmultilang(array('ident' => 'WRITES'), $this);?>

                                    <span>
                                        <time itemprop="datePublished" datetime="<?php echo ((is_array($_tmp=$this->_tpl_vars['review']->oxreviews__oxcreate->value)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['review']->oxreviews__oxcreate->value)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
</time>
                                    </span>
                                </div>
                                <div class="col-md-6 rating">
                                    <?php if ($this->_tpl_vars['review']->oxreviews__oxrating->value): ?>
                                        <div class="pull-right text-warning" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                            <?php unset($this->_sections['starRatings']);
$this->_sections['starRatings']['name'] = 'starRatings';
$this->_sections['starRatings']['start'] = (int)0;
$this->_sections['starRatings']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['starRatings']['show'] = true;
$this->_sections['starRatings']['max'] = $this->_sections['starRatings']['loop'];
$this->_sections['starRatings']['step'] = 1;
if ($this->_sections['starRatings']['start'] < 0)
    $this->_sections['starRatings']['start'] = max($this->_sections['starRatings']['step'] > 0 ? 0 : -1, $this->_sections['starRatings']['loop'] + $this->_sections['starRatings']['start']);
else
    $this->_sections['starRatings']['start'] = min($this->_sections['starRatings']['start'], $this->_sections['starRatings']['step'] > 0 ? $this->_sections['starRatings']['loop'] : $this->_sections['starRatings']['loop']-1);
if ($this->_sections['starRatings']['show']) {
    $this->_sections['starRatings']['total'] = min(ceil(($this->_sections['starRatings']['step'] > 0 ? $this->_sections['starRatings']['loop'] - $this->_sections['starRatings']['start'] : $this->_sections['starRatings']['start']+1)/abs($this->_sections['starRatings']['step'])), $this->_sections['starRatings']['max']);
    if ($this->_sections['starRatings']['total'] == 0)
        $this->_sections['starRatings']['show'] = false;
} else
    $this->_sections['starRatings']['total'] = 0;
if ($this->_sections['starRatings']['show']):

            for ($this->_sections['starRatings']['index'] = $this->_sections['starRatings']['start'], $this->_sections['starRatings']['iteration'] = 1;
                 $this->_sections['starRatings']['iteration'] <= $this->_sections['starRatings']['total'];
                 $this->_sections['starRatings']['index'] += $this->_sections['starRatings']['step'], $this->_sections['starRatings']['iteration']++):
$this->_sections['starRatings']['rownum'] = $this->_sections['starRatings']['iteration'];
$this->_sections['starRatings']['index_prev'] = $this->_sections['starRatings']['index'] - $this->_sections['starRatings']['step'];
$this->_sections['starRatings']['index_next'] = $this->_sections['starRatings']['index'] + $this->_sections['starRatings']['step'];
$this->_sections['starRatings']['first']      = ($this->_sections['starRatings']['iteration'] == 1);
$this->_sections['starRatings']['last']       = ($this->_sections['starRatings']['iteration'] == $this->_sections['starRatings']['total']);
?>
                                                <?php if ($this->_tpl_vars['review']->oxreviews__oxrating->value >= $this->_sections['starRatings']['iteration']): ?>
                                                    <i class="fa fa-star"></i>
                                                <?php else: ?>
                                                    <i class="fa fa-star-o"></i>
                                                <?php endif; ?>
                                            <?php endfor; endif; ?>
                                            <div class="hidden">
                                                <span itemprop="worstRating">1</span>
                                                <span itemprop="ratingValue"><?php echo ((is_array($_tmp=@$this->_tpl_vars['review']->oxreviews__oxrating->value)) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</span>
                                                <span itemprop="bestRating">5</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body" id="reviewText_<?php echo $this->_foreach['ReviewsCounter']['iteration']; ?>
" itemprop="description"><?php echo $this->_tpl_vars['review']->oxreviews__oxtext->value; ?>
</div>
                    
                </div>
            <?php endforeach; endif; unset($_from); ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <?php echo smarty_function_oxmultilang(array('ident' => 'NO_REVIEW_AVAILABLE'), $this);?>

        </div>
    <?php endif; ?>

</div>