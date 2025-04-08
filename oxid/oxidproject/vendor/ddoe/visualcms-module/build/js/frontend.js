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

;(function ( $ )
{
    'use strict';

    var setFullWidthContainer = function ()
    {
        var $fullwidth = $( '.dd-fullwidth' );
        $fullwidth.closest( 'div[class^="col-"]' ).css( 'position', 'static' ); // Chrome Fix

        /*var position = $fullwidth.offset();
         $fullwidth.after( '<div class="dd-fullwidth-helper" style="height: ' + $fullwidth.height() + '"></div>' );
         $fullwidth.parents( '.container' ).last().after( $fullwidth );
         $fullwidth.css(
         {
         position: 'absolute',
         top: position.top,
         left: position.left
         }
         );*/

        var gap = Math.floor( ( $( 'body' ).width() - $( '.dd-ve-container' ).first().width() ) / 2 );

        $fullwidth.each( function ()
            {
                $( this ).css( 'margin', '0 -' + gap + 'px' );

                if ( !$( this ).find( '.dd-hero-box, .dd-image-box' ).length )
                {
                    $( this ).css( 'padding', '0 ' + gap + 'px' );
                }
            }
        );


    };

    if ( !$( '.dd-boxed-container' ).length )
    {
        $( window ).on('load', setFullWidthContainer ).on( 'resize', setFullWidthContainer );
    }

    $( '.dd-tabs .tab-select' ).change( function ()
        {
            var $parent = $( this ).closest( '.dd-tabs' );
            $( '.nav a', $parent ).eq( $( 'option:selected', this ).index() ).tab( 'show' );
        }
    );

    var $heroContainer = $( '.dd-hero-fixed' );

    if ( $heroContainer.length )
    {
        if ( $( window ).height() > $( '.dd-hero-inner', $heroContainer ).height() )
        {
            $heroContainer.height( $( window ).height() );
        }
        else
        {
            $heroContainer.height( $( '.dd-hero-inner', $heroContainer ).height() );
        }
    }

    var $psElements = $( '.dd-image-box.dd-image-lightbox' );

    if ( $psElements.length )
    {
        var psItems = { 'other': [] };
        var aIndex = { 'other': 0 };

        $psElements.each( function ()
            {
                var currentItem = {
                    src: $( this ).data( 'original-image' ),
                    w: $( this ).data( 'image-width' ),
                    h: $( this ).data( 'image-height' )
                };

                if ( $( 'a', this ).attr( 'title' ) )
                {
                    currentItem.title = $( 'a', this ).attr( 'title' );
                }

                currentItem.el = this;

                var galleryID = $( this ).data( 'gallery-id' );

                if( galleryID )
                {
                    if( typeof psItems[ galleryID ] === 'undefined' )
                    {
                        psItems[ galleryID ] = [];
                        aIndex[ galleryID ] = 0;
                    }

                    psItems[ galleryID ].push( currentItem );
                }
                else
                {
                    psItems.other.push( currentItem );
                }

            }
        );

        var psOptions = {
            index: 0,
            history: false,
            mainClass: 'pswp--minimal--dark',
            barsSize: {
                top: 0,
                bottom: 0
            },
            //captionEl: false,
            fullscreenEl: false,
            shareEl: false,
            bgOpacity: 0.85,
            tapToClose: true,
            tapToToggleControls: false
        };

        $psElements.each( function ()
            {
                var galleryID = $( this ).data( 'gallery-id' ) || 'other';

                $( 'a', this ).data( 'image-index', aIndex[ galleryID ] ).on( 'click', function ( e )
                    {
                        e.preventDefault();

                        psOptions.index            = $( this ).data( 'image-index' );
                        psOptions.getThumbBoundsFn = function ( index )
                        {
                            // See Options -> getThumbBoundsFn section of documentation for more info
                            var thumbnail   = $( 'img', psItems[ galleryID ][ index ].el )[ 0 ], // find thumbnail
                                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                                rect        = thumbnail.getBoundingClientRect();

                            return {
                                x: rect.left,
                                y: rect.top + pageYScroll,
                                w: rect.width,
                                h: rect.height
                            };
                        };

                        var psGallery = new PhotoSwipe( $( '.pswp' )[ 0 ], PhotoSwipeUI_Default, psItems[ galleryID ], psOptions );
                        psGallery.init();
                    }
                );

                aIndex[ galleryID ]++;
            }
        );


    }

})( jQuery );