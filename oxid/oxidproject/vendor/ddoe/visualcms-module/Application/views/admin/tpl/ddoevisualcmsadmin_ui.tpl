<!DOCTYPE html>
<html>
    <head>
        [{block name="visualcms_head"}]

            <title>[{$title}]</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">

            <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>

            [{assign var="oViewConf" value=$oView->getViewConfig()}]
            [{assign var="oConf" value=$oView->getConfig()}]

            [{block name="visualcms_styles"}]

                [{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/bootstrap.min.css')}]
                [{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/font-awesome.min.css')}]
                [{oxstyle include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/css/admin.min.css')}]

                [{oxstyle}]

                <style type="text/css">
                    main {
                        bottom: [{if $footer}]51px[{else}]0[{/if}];
                        background: [{if $background}][{$background}][{else}]#f5f5f5[{/if}];
                    }
                </style>

            [{/block}]

        [{/block}]
    </head>
    <body>

        [{block name="visualcms"}]

            [{block name="visualcms_header"}]

                <nav class="navbar navbar-default">
                    <div class="container-fluid">

                        [{block name="visualcms_header_navbar"}]

                            <div class="navbar-header">
                                <a class="navbar-brand" href="javascript:void(null);">
                                    [{if $icon}]<img src="[{$icon}]" style="height: 100%; display: inline-block;" />[{/if}] [{if $title}][{$title}][{else}]OXID eSales AG[{/if}]
                                </a>
                            </div>

                            [{if !$smarty.get.popout}]
                                <ul class="nav navbar-nav navbar-right hidden-xs">
                                    <li><a href="[{$oViewConf->getSelfLink()}]cl=[{$oViewConf->getActiveClassName()}]&popout=1" target="_blank" class="dd-admin-popout-action"><i class="fa fa-expand"></i></a></li>
                                </ul>
                            [{/if}]

                            [{foreach from=$header item="_block"}]
                                [{$_block}]
                            [{/foreach}]

                        [{/block}]

                    </div>
                </nav>

            [{/block}]

            [{block name="visualcms_main"}]

                <main>

                    <div class="container-fluid">

                        <div class="dd-content">

                            [{block name="visualcms_content"}]

                                [{foreach from=$content item="_block"}]
                                    [{$_block}]
                                [{/foreach}]

                            [{/block}]

                        </div>

                    </div>

                </main>

            [{/block}]

            [{block name="visualcms_footer"}]

                [{if $footer}]

                    <nav class="navbar navbar-default navbar-fixed-bottom">
                        <div class="container-fluid">

                            [{block name="visualcms_footer_navbar"}]

                                [{foreach from=$footer item="_block"}]
                                    [{$_block}]
                                [{/foreach}]

                            [{/block}]

                        </div>
                    </nav>

                [{/if}]

            [{/block}]

        [{/block}]

        [{block name="visualcms_modals"}]

            [{foreach from=$modal item="_block"}]
                [{$_block}]
            [{/foreach}]

        [{/block}]

        [{block name="visualcms_scripts"}]

            [{oxscript include=$oViewConf->getSelfLink()|cat:'cl=ddoevisualcmslangjs' priority=1}]

            [{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/jquery-backend.min.js') priority=1}]
            [{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/jquery-ui-backend.min.js') priority=1}]
            [{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/bootstrap.min.js') priority=1}]
            [{oxscript include=$oViewConf->getModuleUrl('ddoevisualcms','out/src/js/admin.min.js') priority=10}]

            [{assign var="sActionLink" value=$oViewConf->getSelfLink()|html_entity_decode}]
            [{assign var="sMediaLink" value=$oConf->getCurrentShopUrl(true)|regex_replace:'/([^\/])(\/admin)/':'$1'|regex_replace:'/http(s)?\:/':''|rtrim:'/'|cat:'/out/pictures/ddmedia/'|html_entity_decode}]

            [{oxscript add="MediaLibrary.setActionLink('`$sActionLink`');" priority=10}]
            [{oxscript add="MediaLibrary.setResourceLink('`$sMediaLink`');" priority=10}]

            [{oxscript}]

        [{/block}]

    </body>
</html>
