[{include file="include/editor.tpl" checkrights="oxlongdesc" right=$smarty.const.RIGHT_VIEW}]

<table>
    <tr>
        <td valign="top" class="edittext">
            [{if $languages}]<b>[{oxmultilang ident="GENERAL_LANGUAGE"}]</b>
                <select name="catlang" class="editinput" onchange="Javascript:loadLang(this)" [{$readonly}]>
                    [{foreach key=key item=item from=$languages}]
                        <option value="[{$key}]"[{if $catlang == $key}] SELECTED[{/if}]>[{$item->name}]</option>
                    [{/foreach}]
                </select>
            [{/if}]
        </td>
    </tr>
    <tr>
        <td>
            [{oxhasrights object=$edit field='oxlongdesc' readonly=$readonly right=$smarty.const.RIGHT_VIEW }]
                <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="CATEGORY_TEXT_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]>
            [{/oxhasrights}]
        </td>
    </tr>
</table>