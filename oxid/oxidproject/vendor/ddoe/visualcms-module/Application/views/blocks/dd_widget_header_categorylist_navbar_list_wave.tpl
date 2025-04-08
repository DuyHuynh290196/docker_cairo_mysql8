<li class="nav-item[{if $homeSelected == 'true'}] active[{/if}]">
    <a href="[{$oViewConf->getHomeLink()}]" class="nav-link">[{oxmultilang ident="HOME"}]</a>
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
                        <li class="dropdown-item [{if $oViewConf->getContentId() == $osubcont->getId()}]active[{/if}]">
                            <a class="nav-link" href="[{$osubcont->getLink()}]">[{$osubcont->oxcontents__oxtitle->value}]</a>
                        </li>
                    [{/foreach}]
                </ul>
            [{/if}]
        [{/capture}]

        <li class="nav-item[{if $oTopCont->getSubConts()}] dropdown[{/if}] [{if $blTopRootContentActive}]active[{/if}]">
            <a href="[{$oTopCont->getLink()}]"  class="nav-link" [{if $oTopCont->getSubConts()}] data-toggle="dropdown"[{/if}]>
                [{$oTopCont->oxcontents__oxtitle->value}][{if $oTopCont->getSubConts()}] <i class="fa fa-angle-down"></i>[{/if}]
            </a>

            [{$smarty.capture.subContents}]
        </li>
    [{/foreach}]

    <li class="nav-item [{if $homeSelected == 'false' && $ocat->expanded}]active[{/if}][{if $ocat->getSubCats()}] dropdown[{/if}]">
        <a class="nav-link" href="[{$ocat->getLink()}]"[{if $ocat->getSubCats()}] class="dropdown-toggle" data-toggle="dropdown"[{/if}]>
            [{$ocat->oxcategories__oxtitle->value}][{if $ocat->getSubCats()}] <i class="fa fa-angle-down"></i>[{/if}]
        </a>

        [{if $ocat->getSubCats()}]
        <ul class="dropdown-menu">
            [{foreach from=$ocat->getSubCats() item="osubcat" key="subcatkey" name="SubCat"}]
            [{if $osubcat->getIsVisible()}]
            [{foreach from=$osubcat->getContentCats() item=ocont name=MoreCms}]
            <li class="dropdown-item">
                <a class="dropdown-link" href="[{$ocont->getLink()}]">[{$ocont->oxcontents__oxtitle->value}]</a>
            </li>
            [{/foreach}]

            [{if $osubcat->getIsVisible()}]
            <li class="dropdown-item[{if $homeSelected == 'false' && $osubcat->expanded}] active[{/if}]">
                <a class="dropdown-link[{if $homeSelected == 'false' && $osubcat->expanded}] current[{/if}]" href="[{$osubcat->getLink()}]">[{$osubcat->oxcategories__oxtitle->value}]</a>
            </li>
            [{/if}]
            [{/if}]
            [{/foreach}]
        </ul>
        [{/if}]
    </li>
    [{/if}]
    [{/foreach}]
