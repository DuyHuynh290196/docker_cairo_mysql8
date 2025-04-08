[{if $edit->oxuser__oxldapkey->value}]
    <tr>
        <td class="edittext"><br>
            [{oxmultilang ident="USER_MAIN_LDAP"}]
        </td>
        <td class="edittext"><br>
            [{$edit->oxuser__oxldapkey->value}]
        </td>
    </tr>
[{/if}]