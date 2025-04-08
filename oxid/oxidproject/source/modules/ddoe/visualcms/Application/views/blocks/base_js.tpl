[{assign var="oConf" value=$oView->getConfig()}]

[{if !$oConf->getConfigParam('blDisableJQuery')}]
    [{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/jquery.min.js') priority=1}]
    [{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/jquery-ui.min.js') priority=1}]
[{/if}]
[{if !$oViewConf->isRoxiveTheme()}]
    [{if !$oConf->getConfigParam( 'blCustomGridFramework' ) && !$oConf->getConfigParam('blDisableBootstrap')}]
        [{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/bootstrap-custom.min.js') priority=99}]
    [{/if}]
[{/if}]

[{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/photoswipe.min.js')}]
[{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/scripts.min.js')}]

[{include file="ddoevisualcms_photoswipe.tpl"}]

[{$smarty.block.parent}]
