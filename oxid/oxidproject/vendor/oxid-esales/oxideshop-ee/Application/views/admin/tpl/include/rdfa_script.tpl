<script type="text/javascript">
    function editThis( sID )
    {
        var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
        oTransfer.oxid.value = '';
        oTransfer.cl.value = top.oxid.admin.getClass( sID );

        //forcing edit frame to reload after submit
        top.forceReloadingEditFrame();

        var oSearch = top.basefrm.list.document.getElementById( "search" );
        oSearch.oxid.value = sID;
        oSearch.updatenav.value = 1;
        oSearch.submit();
    }
</script>
