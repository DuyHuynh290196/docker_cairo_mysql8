[{if $checkrights}]
    [{oxhasrights object=$edit field=$checkrights readonly=$readonly right=$right}]
        [{$editor}]
        <div class="messagebox">[{oxmultilang ident="EDITOR_PLAINTEXT_HINT"}]</div>
    [{/oxhasrights}]
[{else}]
    [{$editor}]
    <div class="messagebox">[{oxmultilang ident="EDITOR_PLAINTEXT_HINT"}]</div>
[{/if}]
