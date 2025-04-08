[{capture append="oxidBlock_content"}]
    [{assign var="oContent" value=$oView->getContent()}]
    [{assign var="template_title" value=$oView->getTitle()}]
    [{assign var="tpl" value=$oViewConf->getActTplName()}]
    [{assign var="oxloadid" value=$oViewConf->getActContentLoadId()}]

    <div class="dd-ve-content dd-ve-content-plain">
        [{if !$oContent->oxcontents__ddhidetitle->value}]
            <h1 class="page-header">[{$template_title}]</h1>
        [{/if}]
        [{$oView->getParsedContent()}]
    </div>

    [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]
[{include file="layout/popup.tpl"}]