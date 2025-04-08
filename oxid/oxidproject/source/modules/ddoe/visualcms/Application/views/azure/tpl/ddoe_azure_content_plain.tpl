[{capture append="oxidBlock_content"}]
    [{assign var="oContent" value=$oView->getContent()}]
    [{assign var="tpl" value=$oViewConf->getActTplName()}]
    [{assign var="oxloadid" value=$oViewConf->getActContentLoadId()}]

    <div class="dd-ve-content dd-ve-content-plain">
        [{if !$oContent->oxcontents__ddhidetitle->value}]
            <h1 class="pageHead">[{$oView->getTitle()}]</h1>
        [{/if}]
        [{$oView->getParsedContent()}]
    </div>

[{/capture}]
[{include file="layout/popup.tpl"}]