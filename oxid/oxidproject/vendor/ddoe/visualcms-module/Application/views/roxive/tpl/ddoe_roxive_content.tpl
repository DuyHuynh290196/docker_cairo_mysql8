[{assign var="oContent" value=$oView->getContent()}]
[{assign var="tpl" value=$oViewConf->getActTplName()}]
[{assign var="oxloadid" value=$oViewConf->getActContentLoadId()}]
[{assign var="template_title" value=$oView->getTitle()}]

[{if $oContent->oxcontents__ddislanding->value}]
    [{assign var="sContentBlock" value="oxidBlock_pageBody"}]
[{else}]
    [{assign var="sContentBlock" value="oxidBlock_content"}]
[{/if}]

[{capture append=$sContentBlock}]

    <div class="dd-ve-content[{if $oContent->oxcontents__ddislanding->value}] dd-ve-landing[{/if}]">

        [{if $oContent->oxcontents__ddislanding->value}]
            <div class="container">
        [{/if}]

        [{if !$oContent->oxcontents__ddhidetitle->value}]
            <h1 class="page-header">[{$template_title}]</h1>
        [{/if}]

        <article class="cmsContent">
            [{$oView->getParsedContent()}]
        </article>

        [{if $oContent->oxcontents__ddislanding->value}]
            </div>
        [{/if}]

    </div>

    [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]

[{if $oContent->oxcontents__ddislanding->value}]
    [{capture append="oxidBlock_head"}]
        <style type="text/css">
            html, body {
                width: 100%;
                height: 100%;
                background: #fff;
            }
        </style>
    [{/capture}]

    [{include file="layout/base.tpl"}]
[{else}]
    [{include file="layout/page.tpl"}]
[{/if}]
