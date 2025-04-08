[{if $orderitem->getStatus()}]
    <tr>
        <td><strong>[{ oxmultilang ident="DELIVERY_STATUS" suffix="COLON" }]</strong></td>
        <td colspan="2">
            [{foreach from=$orderitem->getStatus() item=aStatus }]
            <strong>[{if $aStatus->STATUS == "ANG"}]
                [{ oxmultilang ident="DELIVERY_STATUS_ANG" }]
                [{ elseif $aStatus->STATUS == "HAL"}]
                [{ oxmultilang ident="DELIVERY_STATUS_HAL" }]
                [{ elseif $aStatus->STATUS == "BES"}]
                [{ oxmultilang ident="DELIVERY_STATUS_BES" }]
                [{ elseif $aStatus->STATUS == "EIN"}]
                [{ oxmultilang ident="DELIVERY_STATUS_EIN" }]
                [{ elseif $aStatus->STATUS == "AUS"}]
                [{ oxmultilang ident="DELIVERY_STATUS_AUS" }]
                [{ elseif $aStatus->STATUS == "STO"}]
                [{ oxmultilang ident="DELIVERY_STATUS_STO" }]
                [{ elseif $aStatus->STATUS == "NLB"}]
                [{ oxmultilang ident="DELIVERY_STATUS_NLB" }]
                [{else}]
                [{ $aStatus->STATUS }]
                [{/if}]</strong>([{ $aStatus->date|date_format:"%d.%m.%Y %H:%M" }])<br>
            [{/foreach}]
        </td>
    </tr>
    [{if $aStatus->trackingid }]
    <tr>
        <td><strong>[{ oxmultilang ident="TRACKING_ID" suffix="COLON" }]</strong></td>
        <td colspan="2">
            [{ $aStatus->trackingid }]
        </td>
    </tr>
    [{/if}]
[{/if}]
