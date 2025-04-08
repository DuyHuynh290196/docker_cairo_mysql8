/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version   OXID eSales Visual CMS
 */

;( function( $ )
{
    $.extend( $.summernote.plugins,
        {
            ddmedia: function( context )
            {
                var layoutInfo = context.layoutInfo;
                var $toolbar   = layoutInfo.toolbar;

                var ui = $.summernote.ui;

                this.initialize = function ()
                {
                    // create button
                    var button = ui.button(
                        {
                            className: 'btn-info',
                            contents: '<i class="fa fa-file-image-o"></i>',
                            click: function ( e )
                            {
                                MediaLibrary.open( /image\/.*/i, function( id, file, fullpath )
                                    {
                                        context.invoke('editor.insertImage', fullpath, function( $image )
                                            {
                                                $image.css( 'max-width', '100%' );
                                                $image.attr( 'data-filename', file );
                                                $image.attr( 'data-filepath', fullpath );
                                                $image.attr( 'data-source', 'media' );
                                                $image.addClass( 'dd-wysiwyg-media-image' );
                                            }
                                        );
                                    }
                                );
                            }
                        }
                    );

                    // generate jQuery element from button instance.
                    this.$button = button.render();

                    if( $toolbar.find( '.note-btn-group.note-insert' ).length )
                    {
                        $toolbar.find( '.note-btn-group.note-insert' ).append( this.$button );
                    }
                    else
                    {
                        $toolbar.append( $( '<div class="note-btn-group btn-group" />' ).append( this.$button ) );
                    }
                };

                this.destroy = function ()
                {
                    this.$button.remove();
                    this.$button = null;
                };
            }
        }
    );

} )( jQuery );