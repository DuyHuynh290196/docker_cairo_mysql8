[{$smarty.block.parent}]
[{assign var="oConf" value=$oView->getConfig()}]

[{if $oConf->getConfigParam('blEnableFontAwesome')}]
    [{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/font-awesome.min.css')}]
[{/if}]

[{if !$oViewConf->isRoxiveTheme()}]
    [{if !$oConf->getConfigParam( 'blCustomGridFramework' ) && !$oConf->getConfigParam('blDisableBootstrap')}]
        [{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/bootstrap-custom.min.css')}]
    [{/if}]
[{/if}]

[{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/photoswipe.min.css')}]
[{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/style.min.css')}]