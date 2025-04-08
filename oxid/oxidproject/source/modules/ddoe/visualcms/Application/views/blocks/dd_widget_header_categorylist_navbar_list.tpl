<li [{if $homeSelected == 'true'}]class="active"[{/if}]>
    <a href="[{$oViewConf->getHomeLink()}]">[{oxmultilang ident="HOME"}]</a>
</li>

[{foreach from=$oxcmp_categories item="ocat" key="catkey" name="root"}]
    [{if $ocat->getIsVisible()}]
        [{foreach from=$ocat->getContentCats() item="oTopCont" name="MoreTopCms"}]

            [{assign var=blTopRootContentActive value=false}]
            [{if $oViewConf->getContentId() == $oTopCont->getId()}]
                [{assign var=blTopRootContentActive value=true}]
            [{/if}]

            [{capture name="subContents"}]
                [{if $oTopCont->getSubConts()}]
                    <ul class="dropdown-menu">
                        [{foreach from=$oTopCont->getSubConts() item="osubcont" key="subcontkey" name="SubCont"}]
                            [{if $oViewConf->getContentId() == $osubcont->getId()}]
                                [{assign var=blTopRootContentActive value=true}]
                            [{/if}]
                            <li [{if $oViewConf->getContentId() == $osubcont->getId()}]class="active"[{/if}]>
                                <a href="[{$osubcont->getLink()}]">[{$osubcont->oxcontents__oxtitle->value}]</a>
                            </li>
                        [{/foreach}]
                    </ul>
                [{/if}]
            [{/capture}]

            <li class="[{if $oTopCont->getSubConts()}]dropdown[{/if}] [{if $blTopRootContentActive}]active[{/if}]">
                <a href="[{$oTopCont->getLink()}]" [{if $oTopCont->getSubConts()}] class="dropdown-toggle" data-toggle="dropdown"[{/if}]>
                    [{$oTopCont->oxcontents__oxtitle->value}][{if $oTopCont->getSubConts()}] <i class="fa fa-angle-down"></i>[{/if}]
                </a>

                [{$smarty.capture.subContents}]
            </li>
        [{/foreach}]

        <li class="[{if $homeSelected == 'false' && $ocat->expanded}]active[{/if}][{if $ocat->getSubCats()}] dropdown[{/if}]">
            <a href="[{$ocat->getLink()}]"[{if $ocat->getSubCats()}] class="dropdown-toggle" data-toggle="dropdown"[{/if}]>
                [{$ocat->oxcategories__oxtitle->value}][{if $ocat->getSubCats()}] <i class="fa fa-angle-down"></i>[{/if}]
            </a>

            [{if $ocat->getSubCats()}]
                <ul class="dropdown-menu">
                    [{foreach from=$ocat->getSubCats() item="osubcat" key="subcatkey" name="SubCat"}]
                        [{if $osubcat->getIsVisible()}]
                            [{foreach from=$osubcat->getContentCats() item=ocont name=MoreCms}]
                                <li>
                                    <a href="[{$ocont->getLink()}]">[{$ocont->oxcontents__oxtitle->value}]</a>
                                </li>
                            [{/foreach}]

                            [{if $osubcat->getIsVisible()}]
                                <li [{if $homeSelected == 'false' && $osubcat->expanded}]class="active"[{/if}]>
                                    <a [{if $homeSelected == 'false' && $osubcat->expanded}]class="current"[{/if}] href="[{$osubcat->getLink()}]">[{$osubcat->oxcategories__oxtitle->value}]</a>
                                </li>
                            [{/if}]
                        [{/if}]
                    [{/foreach}]
                </ul>
            [{/if}]
        </li>
    [{/if}]
[{/foreach}]