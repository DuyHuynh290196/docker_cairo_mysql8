/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2022
 * @version   OXID eSales Visual CMS
 */

+function ( $ )
{
    'use strict';

    // VISUAL EDITOR CLASS DEFINITION
    // ==============================

    var VisualEditor = function ( shortcodes, options )
    {
        this.log( 'Visual Editor Version: ' + VisualEditor.VERSION );

        if ( typeof shortcodes === 'undefined' )
        {
            throw new Error( 'no shortcodes given' );
        }

        this.log( 'Initializing Visual Editor' );
        this.log( options );

        // Class properties
        this.shortcodes = shortcodes;
        this.options    = $.extend( {}, VisualEditor.DEFAULTS, typeof options === 'object' && options );
        this.$container = typeof this.options.container === 'string' ? $( this.options.container ) : this.options.container;
        this.$grid      = $( '.grid', this.$container );
        this.$modal     = typeof this.options.modal === 'string' ? $( this.options.modal ) : this.options.modal;

        if ( this.options.defaultWidgetSize > this.options.gridSize )
        {
            this.options.defaultWidgetSize = this.options.gridSize;
        }

        // Calculating column size
        this.options.colSize   = Math.floor( this.$container.width() / this.options.gridSize ) - 12;
        this.options.colWidth  = this.options.colSize;
        this.options.colHeight = this.options.colSize;

        if ( this.options.colSize < 140 )
        {
            this.options.colHeight = 140;
        }
        else
        {
            if ( this.options.colSize > 220 )
            {
                this.options.colHeight = 220;
            }
        }

        // Initialize grid
        this.initializeGrid();

        // Sets widget select events
        this.setWidgetSelector();

        // Initialize 3rd Party plugins
        this.initializePlugins();
    };


    // VISUAL EDITOR MAIN PROPERTIES
    // =============================

    VisualEditor.VERSION = '2.0.0';

    VisualEditor.DEFAULTS = {
        gridSize: 6,
        gridFrontendSize: 12,
        defaultWidgetSize: 1,
        container: '.dd-veditor',
        modal: '.dd-widget-modal',
        actionLink: null,
        resourceLink: null
    };

    VisualEditor.WIDGET_DEFAULTS = {
        col: null,
        row: null,
        size_x: 1,
        size_y: 1
    };

    VisualEditor.DEBUG = false;


    // VISUAL EDITOR METHODS
    // =====================

    // Debugging Methods
    // -----------------

    VisualEditor.prototype.timestamp = function ()
    {
        var ts = new Date();

        return ts.getFullYear() + '-' +
            ( (ts.getMonth() + 1) < 10 ? '0' : '' ) + (ts.getMonth() + 1) + '-' +
            ( ts.getDate() < 10 ? '0' : '' ) + ts.getDate() + ' ' +
            ( ts.getHours() < 10 ? '0' : '' ) + ts.getHours() + ':' +
            ( ts.getMinutes() < 10 ? '0' : '' ) + ts.getMinutes() + ':' +
            ( ts.getSeconds() < 10 ? '0' : '' ) + ts.getSeconds();
    };

    VisualEditor.prototype.log = function ( msg, type, trace )
    {
        if ( VisualEditor.DEBUG )
        {
            if ( typeof type === 'undefined' )
            {
                type = 'log';
            }
            if ( typeof trace === 'undefined' )
            {
                trace = false;
            }

            if ( typeof console !== 'undefined' )
            {
                if ( typeof console[ type ] !== 'undefined' )
                {
                    console[ type ]( '[Visual Editor][' + this.timestamp() + '] ', msg );
                }

                if ( trace )
                {
                    console.trace();
                }
            }
        }
    };

    VisualEditor.prototype.isDebugMode = function ()
    {
        return VisualEditor.DEBUG;
    };


    // Init Methods
    // ------------

    VisualEditor.prototype.initializeGrid = function ()
    {
        this.log( 'Initializing Grid' );
        this.log( this.options );

        // Set grid min height
        this.$grid.css( 'min-height', this.options.colHeight );

        // Initialize gridstack plugin
        this.gridstack = GridStack.init(
            {
                verticalMargin: 10,
                cellHeight: this.options.colHeight,
                column: this.options.gridSize,
                acceptWidgets: false,
                resizable: {
                    autoHide: true,
                    handles: 's, e'
                }
            },
            this.$grid
        );

        this.log( 'Setting Widget events' );

        // Set Widget events
        var self = this;

        // Delete Widget
        this.$grid.on( 'click', '.dd-veditor-widget .dd-widget-action-delete', function ()
            {
                var $widget     = $( this ).closest( '.dd-veditor-widget' );
                var $nestedGrid = $widget.closest( '.nested-grid' );

                if ( $nestedGrid.length )
                {
                    self.nestedgridstack = $nestedGrid.get( 0 ).gridstack;
                }

                ddh.confirm( ddh.translate( ( $widget.hasClass( 'dd-veditor-column' ) ? 'DD_VISUAL_EDITOR_WIDGET_DELETE_NESTED_CONFIRM' : 'DD_VISUAL_EDITOR_WIDGET_DELETE_CONFIRM' ) ), function ()
                    {
                        self.remove( $widget, function ()
                            {
                                self.nestedgridstack = null;
                            }
                        );
                    }
                );
            }

            // Edit Widget
        ).on( 'click', '.dd-veditor-widget .dd-widget-action-edit', function ()
            {
                var $widget     = $( this ).closest( '.dd-veditor-widget' );
                var $nestedGrid = $widget.closest( '.nested-grid' );

                if ( $nestedGrid.length )
                {
                    self.nestedgridstack = $nestedGrid.get( 0 ).gridstack;
                }
                else
                {
                    self.nestedgridstack = null;
                }

                var $typeSelect = $( '.dd-widget-type-select', self.$modal );

                if ( $widget.hasClass( 'dd-veditor-column' ) )
                {
                    $typeSelect[ 0 ].selectize.addOption( { value: 'column', label: "", area: "dd-type-column" } );
                    $typeSelect[ 0 ].selectize.refreshOptions( false );
                    $typeSelect.parent().parent().hide();
                    //$( 'a[href="#tab_widget_design"]', self.$modal ).tab( 'show' );
                }
                else
                {
                    $typeSelect[ 0 ].selectize.removeOption( 'column' );
                    $typeSelect.parent().parent().show();
                }

                self.editWidget( $widget, function ()
                    {
                        self.nestedgridstack = null;
                    }
                );
            }

            // New Nested Widget
        ).on( 'click', '.dd-veditor-widget .dd-widget-action-new-nested', function ()
            {
                var $widget     = $( this ).closest( '.dd-veditor-widget' );
                var $nestedGrid = $widget.find( '.nested-grid' );

                self.nestedgridstack = $nestedGrid.get( 0 ).gridstack;

                if ( !self.nestedgridstack.willItFit( 1, 1, 1, 1, true ) )
                {
                    ddh.alert( ddh.translate( 'DD_VISUAL_EDITOR_NO_SPACE_WARNING' ) );
                }
                else
                {
                    self.newWidget( function ()
                        {
                            self.nestedgridstack = null;
                        }
                    );
                }

            }
        ).on( 'click', '.dd-veditor-widget .dd-widget-action-duplicate', function ()
            {
                var $widget     = $( this ).closest( '.dd-veditor-widget' );
                var $nestedGrid = $widget.closest( '.nested-grid' );

                if ( $nestedGrid.length )
                {
                    self.nestedgridstack = $nestedGrid.get( 0 ).gridstack;
                }
                else
                {
                    self.nestedgridstack = null;
                }

                self.duplicateWidget( $widget, function ()
                    {
                        self.nestedgridstack = null;
                    }
                );
            }
        ).on( 'click', '.dd-veditor-widget .dd-widget-action-move', function ()
           {
               var $widget     = $( this ).closest( '.dd-veditor-widget' );
               var $nestedGrid = $widget.closest( '.nested-grid' );

               if( $widget.length )
               {
                   //check if move mode is already active for source widget
                   if( $widget.hasClass( 'move-source' ) )
                   {
                       // remove move mode
                       self.deactivateMoveMode();
                   }
                   else
                   {
                       // deactivate move mode first if it is already active for another widget
                       if( $('.move-source').length )
                       {
                           self.deactivateMoveMode();
                       }

                       self.log( 'Moving mode on' );

                       $widget.addClass( 'move-source' );
                       self.widgetToMove = $widget;

                       $( '.dd-widget-action-movetarget' ).removeClass( 'in-source-grid' );
                       $( '.dd-veditor-grid' ).addClass( 'move-mode' );

                       if( $nestedGrid.length )
                       {
                           $( $nestedGrid ).prev( '.dd-widget-action-movetarget' ).addClass( 'in-source-grid' );
                           self.addMoveTargetWidget();
                       }
                   }
               }
           }
        );

        this.gridstack.on( 'gsresizestop', function ( e )
                           {
                               var $widget = $( e.target );

                               // Refresh nested grid cell height
                               if ( $widget.hasClass( 'dd-veditor-column' ) )
                               {
                                   var node        = $widget.data( '_gridstack_node' );
                                   var $nestedGrid = $widget.find( '.nested-grid' );

                                   var _cellHeight = Math.floor( ((self.options.colHeight * node.height) - 85) / node.height );

                                   $nestedGrid.get( 0 ).gridstack.cellHeight( _cellHeight );
                               }
                           }
        );

        // New Widget
        $( '.dd-veditor-widget-action', this.$container ).on( 'click', function ()
            {
                var $typeSelect = $( '.dd-widget-type-select', self.$modal );
                $typeSelect[ 0 ].selectize.removeOption( 'column' );
                $typeSelect.parent().parent().show();

                if( !$( this ).hasClass( 'disabled' ) )
                {
                    self.newWidget();
                }
            }
        );

        // New Column
        $( '.dd-veditor-column-action', this.$container ).on( 'click', function ()
            {
                if( !$( this ).hasClass( 'disabled' ) )
                {
                    self.add( 'column' );
                }
            }
        );

        // Clear Widgets
        $( '.dd-veditor-delete-all-action', this.$container ).on( 'click', function ()
            {
                if( !$( this ).hasClass( 'disabled' ) )
                {
                    ddh.confirm( ddh.translate( 'DD_VISUAL_EDITOR_WIDGET_DELETE_ALL_CONFIRM' ), function () { self.clear(); } );
                }
            }
        );

        $( document ).on( 'click', '.dd-widget-action-movetarget, .move-target-widget', function ()
            {
                if( typeof self.widgetToMove != 'undefined' && self.widgetToMove != null )
                {
                    if ( $( this ).hasClass( 'move-target-widget' ) )
                    {
                        self.nestedgridstack = null;
                    }
                    else
                    {
                        self.nestedgridstack = $( this ).next( '.nested-grid' ).get( 0 ).gridstack;
                    }

                    var type = self.widgetToMove.data('widget');
                    var params = self.widgetToMove.data( 'widget-params' );

                    self.log( 'Moving Widget "' + type + '"' );

                    var node = self.widgetToMove.data( '_gridstack_node' );

                    var position = {
                        size_x: node.width,
                        size_y: node.height
                    };

                    if( $( '.move-target-widget' ).length )
                    {
                        self.removeMoveTargetWidget( function ()
                                     {
                                         self.move( type, params, position );
                                     }
                        );
                    }
                    else
                    {
                        self.move( type, params, position );
                    }

                }
            }
        );

        // New Image Widget
        if ( typeof MediaLibrary !== 'undefined' )
        {
            $( '.dd-veditor-media-action', this.$container ).click( function ()
                {
                    if( !$( this ).hasClass( 'disabled' ) )
                    {
                        MediaLibrary.open( /image\/.*/i, true, function ( files )
                            {
                                if ( !files.length )
                                {
                                    return;
                                }

                                $.each( files, function ()
                                    {
                                        self.add( 'image',
                                            {
                                                "image": this.file,
                                                "type": "lightbox",
                                                "thumbnail": 1,
                                                "thumbnail_size": "medium"
                                            }
                                        );
                                    }
                                );
                            }
                        );
                    }
                }
            );
        }
        else
        {
            this.log( 'digidesk Media Library not found, hiding media action...', 'warn' );

            // If not available, hide action button
            $( '.dd-veditor-media-action', this.$container ).hide();
        }

    };

    VisualEditor.prototype.initializeNestedGrid = function ( $nestedGrid, $widget )
    {
        this.log( 'Initializing Nested Grid' );
        this.log( $nestedGrid );
        this.log( this.options );

        var node             = $widget.data( '_gridstack_node' );
        var nestedCellHeight = Math.floor( ( ( this.options.colHeight * node.height ) - 85 ) / node.height );

        // Set grid min height
        //$nestedGrid.css( 'min-height', nestedCellHeight );

        // Initialize gridstack plugin
        GridStack.init(
            {
                verticalMargin: 10,
                cellHeight: nestedCellHeight,
                column: this.options.gridSize,
                //height:          1,
                acceptWidgets: false,
                resizable: {
                    autoHide: true,
                    handles: 'e'
                },
                minRow: 1
            },
            $nestedGrid
        );

        this.nestedgridstack = null;
    };

    VisualEditor.prototype.initializePlugins = function ()
    {
        this.log( 'Initialize 3rd Party plugins' );

        var self = this;

        // digidesk Media Library
        if ( typeof MediaLibrary !== 'undefined' )
        {
            $( '.dd-media-action', this.$modal ).on( 'click', function ()
                {
                    var $target = $( $( this ).data( 'target' ) );

                    MediaLibrary.open( function ( id, file )
                        {
                            $target.val( file ).trigger( 'change' );
                        }
                    );
                }
            );

            $( '.dd-widget-image-add-item', this.$modal ).on( 'click', function ()
                {
                    var $target = $( $( this ).data( 'target' ) );

                    MediaLibrary.open( /image\/.*/i, ( $target.data( 'multi' ) == 1 ), function ( files )
                        {
                            if ( $target.data( 'multi' ) != 1 )
                            {
                                files = [
                                    {
                                        file: arguments[ 1 ]
                                    }
                                ];
                            }

                            if ( files.length )
                            {
                                $.each( files, function ( i, item )
                                    {
                                        if ( !self.addInputImage( item.file, $target ) )
                                        {
                                            return false;
                                        }
                                    }
                                );
                            }
                        }
                    );
                }
            );

            $( '.dd-widget-image-delete-item', this.$modal ).on( 'click', function ()
                {
                    self.removeInputImage( $( this ).closest( '.dd-widget-item-col' ), $( $( this ).data( 'target' ) ) );
                }
            );
        }
        else
        {
            this.log( 'digidesk Media Library not found, disable media actions...', 'warn' );

            $( '.dd-media-action', this.$modal ).attr( 'disabled', 'disabled' ).addClass( 'disabled' );
        }

        // Summernote WYSIWYG Editor
        if ( typeof $().summernote === 'function' )
        {
            this.log( 'Setting summernote editor' );

            $( '.dd-editor', this.$modal ).each( function ()
                {
                    $( this ).summernote(
                        {
                            lang: 'de-DE',
                            minHeight: 100,

                            toolbar: [

                                [ 'style', [ 'style' ] ],
                                [ 'formatting', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'clear' ] ],
                                //[ 'fontname', [ 'fontname' ] ],
                                [ 'fontsize', [ 'fontsize' ] ],
                                [ 'color', [ 'color' ] ],
                                [ 'layout', [ 'ul', 'ol', 'paragraph' ] ],
                                [ 'height', [ 'height' ] ],
                                [ 'table', [ 'table' ] ],
                                [ 'insert', [ 'link', 'videoResponsive', 'hr' ] ],
                                [ 'misc', [ 'codeview' ] ]

                            ],

                            dialogsInBody: true,

                            buttons: {
                                ddmedia: 'ddmedia'
                            },

                            disableDragAndDrop: true,

                            onCreateLink: function ( linkUrl )
                                          {
                                              if ( linkUrl.indexOf( "[{" ) === 0 )
                                              {
                                                  // leave urls beginning with '[{' untouched
                                                  return linkUrl;
                                              }
                                              if( linkUrl )
                                              {
                                                  // summernotes default behaviour:
                                                  linkUrl = /^[A-Za-z][A-Za-z0-9+-.]*\:[\/\/]?/.test( linkUrl ) ? linkUrl : 'http://' + linkUrl;
                                              }
                                              return linkUrl;
                                          },
                            codeviewFilter: true,
                            codeviewIframeFilter: true

                        }
                    );
                }
            );

            // Summernote enter key workaround for dialog windows
            $( '.dd-widget-form', this.$modal ).on( 'keypress', '.note-image-dialog input, .note-link-dialog input', function ( e )
                {
                    if ( e.keyCode === 13 )
                    {
                        e.preventDefault();
                        //e.stopPropagation();
                    }
                }
            );
        }

        // Minicolors Color Picker
        if ( typeof $().minicolors === 'function' )
        {
            this.log( 'Setting minicolors picker' );

            $( '.dd-color-picker', this.$modal ).each( function ()
                {
                    $( this ).minicolors(
                        {
                            theme: 'bootstrap'
                        }
                    );
                }
            );
        }

        // Selectize Selectbox Livesearch
        if ( typeof $().selectize === 'function' )
        {
            this.log( 'Setting selectize picker/boxes' );

            var escapeIcon = function ( text, escape )
            {
                var icon = '';
                var rexp = text.match( /(<i[^>]*class\=('|")fa[^"']*('|")[^>]*>[^<]*<\/i>)(.*)/ );

                if ( rexp && rexp.length === 5 )
                {
                    icon = rexp[ 1 ];
                    text = rexp[ 4 ];
                }

                return icon + escape( text );
            };

            $( '.dd-picker' ).each( function ()
                {
                    $( this ).selectize(
                        {
                            searchField: "label",
                            labelField: "label",
                            render: {
                                option: function ( data, escape )
                                {
                                    return '<div class="option">' + escapeIcon( data[ this.settings.labelField ], escape ) + '</div>';
                                },
                                item: function ( data, escape )
                                {
                                    return '<div class="item">' + escapeIcon( data[ this.settings.labelField ], escape ) + '</div>';
                                }
                            }
                        }
                    );
                }
            );
        }

        // Hint tooltips
        if ( $( '[data-toggle="tooltip"]', this.$modal ).length )
        {
            this.log( 'Setting hint tooltips' );

            $( '[data-toggle="tooltip"]', this.$modal ).tooltip();
        }

        // Area select plugin
        if ( typeof $().areaselect === 'function' )
        {
            this.log( 'Setting areaselect plugin' );

            $( '.dd-area-select' ).areaselect();
        }

        // Fix for multiple Bootstrap Modals
        $( document ).on( 'hidden.bs.modal', function ()
            {
                if ( $( '.modal:visible' ).length )
                {
                    $( 'body' ).addClass( 'modal-open' );
                }
            }
        );
    };

    VisualEditor.prototype.setWidgetSelector = function ()
    {
        this.log( 'Prepare Widget selector' );

        var self = this;

        $( '.dd-widget-select-box', self.$modal ).on( 'mouseenter', function ()
            {
                $( this ).css(
                    {
                        'background-color': $( this ).data( 'color' ),
                        'border-color': $( this ).data( 'color' ),
                        'color': '#fff'
                    }
                );
            }
        ).on( 'mouseleave', function ()
            {
                $( this ).css(
                    {
                        'background-color': '#f7f7f7',
                        'border-color': '#ccc',
                        'color': '#333'
                    }
                );
            }
        ).on( 'click', function ( e )
            {
                $( '.dd-widget-form-select', self.$modal ).hide();
                $( '.dd-widget-form-fields', self.$modal ).show();

                $( '.dd-widget-type-select' )[ 0 ].selectize.setValue( $( this ).data( 'widget' ) );

                if ( $( '.dd-tab-form', self.$modal ).length )
                {
                    $( '.dd-tab-form .nav-tabs li:visible > a', self.$modal ).first().tab( 'show' );
                }

                e.preventDefault();
            }
        );
    };


    // Internal Widget Methods
    // -----------------------

    VisualEditor.prototype.add = function ( type, params, position, callback )
    {
        this.log( 'Adding Widget "' + type + '"' );

        if ( this.nestedgridstack && type === 'column' )
        {
            ddh.alert( ddh.translate( 'DD_VISUAL_EDITOR_NESTED_COLUMN_WARNING' ) );

            typeof callback === 'function' && callback.call( this );

            return;
        }

        var self = this;

        if ( typeof position === 'function' )
        {
            callback = position;
            position = {};
        }

        if ( typeof params === 'undefined' || !params )
        {
            params = {};
        }

        position = $.extend( {}, VisualEditor.WIDGET_DEFAULTS, { size_x: self.options.defaultWidgetSize }, typeof position === 'object' && position );

        var autoPosition = false;

        if ( position.col && position.row )
        {
            position.col -= 1;
            position.row -= 1;
        }
        else
        {
            autoPosition = ( position.col == null || position.row == null );

            if ( autoPosition )
            {
                position.col = 0;
                position.row = 0;
            }
        }

        this.log( params );
        this.log( position );

        var wClass   = 'dd-veditor-widget';
        var wContent = '<div class="dd-widget-' + ( type === 'column' ? 'nested-' : '' ) + 'data">';

        if( type === 'column' )
        {
            wContent += '<a href="javascript:void(null)" class="dd-widget-action-movetarget" title="' + ddh.translate( 'DD_VISUAL_EDITOR_WIDGET_MOVETARGET' ) + '"><i class="fa fa-paste"></i></a>';
        }

        if ( this.shortcodes[ type ] )
        {
            if ( type === 'column' )
            {
                if ( autoPosition )
                {
                    position.size_x = this.options.gridSize;
                }

                wClass += ' dd-veditor-column dd-widget-type-column';
                wContent += '<div class="grid-stack grid-stack-' + this.options.gridSize + ' nested-grid"></div>';

            }
            else
            {
                wClass += ' dd-widget-type-' + type;

                var content = params[ this.shortcodes[ type ].previewParam ];

                var previewText = $.parseHTML(content, false);
                previewText = $(previewText).text();

                if ( previewText.length > 200 )
                {
                    previewText = previewText.substr( 0, 197 ) + '...';
                }

                var _tmp       = document.createElement("DIV");
                _tmp.textContent = previewText;
                var preview = _tmp.innerHTML;

                var icon = params.icon || this.shortcodes[ type ].icon;

                wContent += '<span class="dd-widget-icon"><i class="fa ' + icon + '"></i></span>';
                wContent += '<span class="dd-widget-name">' + this.shortcodes[ type ].name + '</span>';
                wContent += '<span class="dd-widget-preview">' + preview + '</span>';
            }

        }

        wContent += '</div>';

        wContent += '<div class="dd-widget-actions">' +
            ( type === 'column' ? '<a href="javascript:void(null)" class="dd-widget-action-new-nested" title="' + ddh.translate( 'DD_VISUAL_EDITOR_ADD_WIDGET' ) + '"><i class="fa fa-plus"></i></a>' : '' ) +
            '  <a href="javascript:void(null)" class="dd-widget-action-edit" title="' + ddh.translate( 'DD_VISUAL_EDITOR_WIDGET_EDIT' ) + '"><i class="fa fa-edit"></i></a>' +
            ( type === 'column' ? '' : '  <a href="javascript:void(null)" class="dd-widget-action-duplicate" title="' + ddh.translate( 'DD_VISUAL_EDITOR_WIDGET_DUPLICATE' ) + '"><i class="fa fa-copy"></i></a>' ) +
            ( type === 'column' ? '' : '  <a href="javascript:void(null)" class="dd-widget-action-move" title="' + ddh.translate( 'DD_VISUAL_EDITOR_WIDGET_MOVE' ) + '"><i class="fa fa-cut"></i></a>' ) +
            '  <a href="javascript:void(null)" class="dd-widget-action-delete" title="' + ddh.translate( 'DD_VISUAL_EDITOR_WIDGET_DELETE' ) + '"><i class="fa fa-trash"></i></a>' +
            '</div>';

        var $widget = $( '<div class="grid-stack-item ' + wClass + '" data-widget="' + type + '"><div class="grid-stack-item-content dd-widget-inner">' + wContent + '</div></div>' );
        $widget.data( 'widget-params', params );

        if ( this.nestedgridstack )
        {
            var _gridstack = this.nestedgridstack;
        }
        else
        {
            var _gridstack = this.gridstack;
        }

        _gridstack.addWidget( $widget, Math.floor( position.col ), Math.floor( position.row ), Math.floor( position.size_x ), Math.floor( position.size_y ), autoPosition );

        if ( params.col_class )
        {
            self.setGridInfo( $widget, params.col_class );
        }

        if ( type === 'column' )
        {
            this.initializeNestedGrid( $( '.nested-grid', $widget ), $widget );

            if ( typeof position.children === 'object' && position.children.length )
            {
                self.nestedgridstack = $( '.nested-grid', $widget ).get( 0 ).gridstack;

                $.each( position.children, function ( k, item )
                    {
                        self.add( item.widget, item.widget_params, item );
                    }
                );

                self.nestedgridstack = null;
            }
        }

        typeof callback === 'function' && callback.call( this, $widget, type, params );
    };

    VisualEditor.prototype.replace = function ( $widget, type, params, position, callback )
    {
        if ( typeof $widget !== 'object' || !$widget.length )
        {
            return;
        }

        this.log( 'Replacing Widget "' + type + '"' );
        this.log( $widget );

        // If is column, only update params
        if ( $widget.hasClass( 'dd-veditor-column' ) )
        {
            $widget.data( 'widget-params', params );

            if ( params.col_class )
            {
                this.setGridInfo( $widget, params.col_class );
            }
        }
        else
        {
            var node = $widget.data( '_gridstack_node' );

            position = $.extend(
                {
                    col: ( node.x + 1 ),
                    row: ( node.y + 1 ),
                    size_x: node.width,
                    size_y: node.height
                },
                ( typeof position === 'object' && position )
            );

            this.remove( $widget );
            this.add( type, params, position, callback );
        }

    };

    VisualEditor.prototype.remove = function ( $widget, callback )
    {
        this.log( 'Removing Widget' );
        this.log( $widget );

        var _gridstack;

        if ( this.nestedgridstack )
        {
            _gridstack = this.nestedgridstack;
        }
        else
        {
            _gridstack = this.gridstack;
        }

        _gridstack.removeWidget( $widget );

        typeof callback === 'function' && callback.call( this );
    };

    VisualEditor.prototype.serialize = function ( $grid )
    {
        this.log( 'Serializing Widgets' );

        if ( typeof $grid === 'undefined' )
        {
            $grid = this.$grid;
        }

        var data = [];
        var self = this;

        $grid.children( '.grid-stack-item:visible' ).each( function ()
            {
                var node     = $( this ).data( '_gridstack_node' ),
                    type     = $( this ).data( 'widget' ),
                    params   = $( this ).data( 'widget-params' ),
                    children = [];

                if ( type === 'column' )
                {
                    children = self.serialize( $( '.nested-grid', this ) );
                }

                data.push(
                    {
                        row: ( node.y + 1 ),
                        col: ( node.x + 1 ),
                        size_x: node.width,
                        size_y: node.height,
                        widget: type,
                        widget_params: params,
                        children: children
                    }
                );
            }
        );

        data.sort( function ( a, b )
            {
                var asize = ( a.row * 10 ) + a.col;
                var bsize = ( b.row * 10 ) + b.col;

                if ( asize < bsize )
                {
                    return -1;
                }

                if ( asize > bsize )
                {
                    return 1;
                }

                return 0;
            }
        );

        return data;
    };

    VisualEditor.prototype.clear = function ()
    {
        this.log( 'Clearing all Widgets' );

        this.gridstack.removeAll();
    };

    VisualEditor.prototype.setGridInfo = function ( $widget, css )
    {
        if ( $widget.length && css && ( ( typeof css === 'string' && css !== '' ) || css.length ) )
        {
            if ( typeof css === 'string' )
            {
                css = css.split( ' ' );
            }

            this.log( 'Set Grid info' );
            this.log( css );

            if ( $( '.dd-widget-col-css', $widget ).length )
            {
                $( '.dd-widget-col-css', $widget ).remove();
            }

            var infoContent = '<div class="dd-widget-col-css">' +
                '  <ul class="list-inline">';

            var deviceIcons = {
                'xs': 'fa fa-mobile',
                'sm': 'fa fa-tablet',
                'md': 'fa fa-laptop',
                'lg': 'fa fa-desktop'
            };

            var colStatusItems = {};

            $.each( css, function ()
                {
                    var match = this.match( /^col\-([a-z]{2})\-([0-9]+)$/ );

                    if ( match )
                    {
                        if ( colStatusItems[ match[ 1 ] ] )
                        {
                            if ( !colStatusItems[ match[ 1 ] ].match( /text\-danger/ ) )
                            {
                                colStatusItems[ match[ 1 ] ] = match[ 2 ] + ' (Offset ' + colStatusItems[ match[ 1 ] ] + ')';
                            }
                        }
                        else
                        {
                            colStatusItems[ match[ 1 ] ] = match[ 2 ]
                        }

                        return;
                    }

                    match = this.match( /^col\-([a-z]{2})\-offset\-([0-9]+)$/ );

                    if ( match )
                    {
                        if ( colStatusItems[ match[ 1 ] ] )
                        {
                            if ( !colStatusItems[ match[ 1 ] ].match( /text\-danger/ ) )
                            {
                                colStatusItems[ match[ 1 ] ] = colStatusItems[ match[ 1 ] ] + ' (Offset ' + match[ 2 ] + ')';
                            }
                        }
                        else
                        {
                            colStatusItems[ match[ 1 ] ] = match[ 2 ]
                        }

                        return;
                    }

                    match = this.match( /^hidden\-([a-z]{2})$/ );

                    if ( match )
                    {
                        colStatusItems[ match[ 1 ] ] = '<span class="text-danger"><i class="fa fa-ban"></i></span>';
                        return;
                    }
                }
            );

            $.each( colStatusItems, function ( device )
                {
                    infoContent += '<li><i class="' + deviceIcons[ device ] + '"></i> ' + this + '</li>';
                }
            );

            infoContent += '</ul></div>';

            $( '.dd-widget-inner', $widget ).append( infoContent );
        }
    };

    VisualEditor.prototype.addInputImage = function ( file, $target )
    {
        var $addItem = $( '.dd-widget-image-add-item', $target );
        var $newItem = $( '.dd-widget-image-item-helper', $target ).clone( true ).removeClass( 'dd-widget-image-item-helper' );
        var $newCol  = $( '<div class="dd-widget-item-col col-sm-2" />' ).append( $newItem );

        $( 'img', $newItem ).attr( 'src', this.options.resourceLink + file );
        $newItem.data( 'file', file );

        $addItem.parent().before( $newCol );

        if ( $target.data( 'max-length' ) && $( '.dd-widget-item-col', $target ).length >= $target.data( 'max-length' ) )
        {
            $addItem.hide();
            return false;
        }

        return true;
    };

    VisualEditor.prototype.removeInputImage = function ( $image, $target )
    {
        var $addItem = $( '.dd-widget-image-add-item', $target );

        $image.remove();

        if ( !$target.data( 'max-length' ) || $( '.dd-widget-item-col', $target ).length < $target.data( 'max-length' ) )
        {
            $addItem.show();
        }

    };

    VisualEditor.prototype.clearInputImages = function ( $target )
    {
        if ( !$target || !$target.length )
        {
            $target = this.$modal;
        }

        $( '.dd-widget-item-col', $target ).remove();
        $( '.dd-widget-image-add-item', $target ).show();
    };

    VisualEditor.prototype.serializeInputImages = function ( $target )
    {
        var data = [];

        if ( $target.data( 'multi' ) === 1 )
        {
            $( '.dd-widget-item-col', $target ).each( function ()
                {
                    data.push( $( '.dd-widget-image-item', this ).data( 'file' ) );
                }
            );
        }
        else
        {
            data = $( '.dd-widget-item-col .dd-widget-image-item', $target ).first().data( 'file' );
        }

        return data;
    };

    VisualEditor.prototype.addMoveTargetWidget = function ( callback )
    {
        this.log( 'Adding target Widget' );

        var self = this;

        var currentRow = $( '.dd-veditor-grid' ).data( 'gs-current-row' );
        var position = {
            col: 0,
            row: currentRow + 1,
            size_x: this.options.gridSize
        };

        position = $.extend( {}, VisualEditor.WIDGET_DEFAULTS, { size_x: self.options.defaultWidgetSize }, typeof position === 'object' && position );

        var autoPosition = false;

        if ( !position.col || !position.row )
        {
            autoPosition = ( position.col == null || position.row == null );

            if ( autoPosition )
            {
                position.col = 0;
                position.row = 0;
            }
        }

        this.log( position );

        var $widget = $( '<div class="grid-stack-item move-target-widget"><a href="javascript:void(null)" class="grid-stack-item-content dd-widget-inner" title="' + ddh.translate( 'DD_VISUAL_EDITOR_WIDGET_MOVETARGET' ) + '"><i class="fa fa-paste"></i></a></div>' );

        var _gridstack = this.gridstack;

        _gridstack.addWidget( $widget, Math.floor( position.col ), Math.floor( position.row ), Math.floor( position.size_x ), Math.floor( position.size_y ), autoPosition );

        typeof callback === 'function' && callback.call( this, $widget );
    }

    VisualEditor.prototype.removeMoveTargetWidget = function ( callback )
    {
        this.log( 'Removing target Widget' );

        var $widget = $( '.move-target-widget' );
        this.log( $widget );

        var _gridstack = this.gridstack;

        _gridstack.removeWidget( $widget );

        typeof callback === 'function' && callback.call( this );
    };

    VisualEditor.prototype.move = function ( type, params, position )
    {
        this.add( type, params, position, function ()
                  {
                      var $nestedGrid = this.widgetToMove.closest( '.nested-grid' );

                      if ( $nestedGrid.length )
                      {
                          this.nestedgridstack = $nestedGrid.get( 0 ).gridstack;
                      }
                      else
                      {
                          this.nestedgridstack = null;
                      }

                      this.remove( this.widgetToMove, function ()
                                   {
                                       this.nestedgridstack = null;
                                       this.widgetToMove    = null;
                                       $( '.dd-veditor-grid' ).removeClass( 'move-mode' );
                                       this.log( 'Moving mode off' );
                                   }
                      );
                  }
        );
    }

    VisualEditor.prototype.deactivateMoveMode = function ()
    {
        var $widget = $( '.move-source' );

        if( $widget.length )
        {
            this.log( 'Moving mode off' );

            $widget.removeClass( 'move-source' );
            this.widgetToMove = null;

            $( '.dd-veditor-grid' ).removeClass( 'move-mode' );
            if( $( '.move-target-widget' ).length )
            {
                this.removeMoveTargetWidget();
            }
        }
    }


    // External Widget Methods
    // -----------------------

    VisualEditor.prototype.newWidget = function ( callback )
    {
        this.log( 'Triggering new Widget' );

        this.clearModal();
        this.modal( function ( type, params )
            {
                this.log( 'Saving new Widget' );

                this.add( type, params, callback );
            }
        );

        $( '.dd-widget-form-select', this.$modal ).show();
        $( '.dd-widget-form-fields', this.$modal ).hide();
    };

    VisualEditor.prototype.editWidget = function ( $widget, callback )
    {
        if ( typeof $widget !== 'object' || !$widget.length )
        {
            return;
        }

        this.log( 'Triggering edit Widget' );

        this.clearModal();
        this.fillModal( $widget.data( 'widget' ), $widget.data( 'widget-params' ) );

        this.modal( function ( type, params )
            {
                this.log( 'Saving edited Widget' );

                this.replace( $widget, type, params, callback );
            }
        );

        $( '.dd-widget-form-select', this.$modal ).hide();
        $( '.dd-widget-form-fields', this.$modal ).show();
    };

    VisualEditor.prototype.duplicateWidget = function ( $widget, callback )
    {
        if ( typeof $widget !== 'object' || !$widget.length )
        {
            return;
        }


        var type = $widget.data('widget');
        var params = $widget.data( 'widget-params' );

        this.log( 'Duplicating Widget "' + type + '"' );
        this.log( $widget );

        var node = $widget.data( '_gridstack_node' );

        var position = {
            size_x: node.width,
            size_y: node.height
        };

        this.add( type, params, position, callback );
    };


    // Modal Methods
    // -------------

    VisualEditor.prototype.clearModal = function ()
    {
        this.log( 'Clearing modal form' );

        this.clearInputImages();

        $( 'input, textarea, select', this.$modal ).not( '.dd-widget-type-select' ).each( function ()
            {
                if ( $( this ).data( 'editable' ) === false )
                {
                    return;
                }

                if ( $( this ).is( 'input' ) )
                {
                    switch ( $( this ).attr( 'type' ) )
                    {
                        case 'checkbox':
                        case 'radio':
                            $( this ).prop( 'checked', false );
                            break;
                        default:
                            $( this ).val( '' );

                            if ( $( this ).data( 'default-value' ) )
                            {
                                $( this ).val( $( this ).data( 'default-value' ) );
                            }

                            if ( $( this ).data( 'random-value' ) )
                            {
                                $( this ).val( Math.floor( (1 + Math.random()) * 0x100000000 ) );
                            }

                            break;
                    }

                    if ( $( this ).hasClass( 'dd-color-picker' ) && typeof $().minicolors === 'function' )
                    {
                        $( this ).minicolors( 'value', '' );

                        if ( $( this ).data( 'default-value' ) )
                        {
                            $( this ).minicolors( 'value', $( this ).data( 'default-value' ) );
                        }
                    }

                    if ( this.selectize )
                    {
                        this.selectize.setValue( '' );

                        if ( $( this ).data( 'default-value' ) )
                        {
                            this.selectize.setValue( $( this ).data( 'default-value' ) );
                        }
                    }

                }
                else
                {
                    $( this ).val( '' );

                    if ( $( this ).data( 'default-value' ) )
                    {
                        $( this ).val( $( this ).data( 'default-value' ) );
                    }

                    if ( $( this ).data( 'random-value' ) )
                    {
                        $( this ).val( Math.floor( (1 + Math.random()) * 0x100000000 ) );
                    }

                    if ( this.selectize )
                    {
                        this.selectize.setValue( '' );

                        if ( $( this ).data( 'default-value' ) )
                        {
                            this.selectize.setValue( $( this ).data( 'default-value' ) );
                        }
                    }

                    if ( $( this ).hasClass( 'dd-editor' ) && typeof $().summernote === 'function' )
                    {
                        $( this ).summernote( 'code', '' );

                        if ( $( this ).data( 'default-value' ) )
                        {
                            $( this ).summernote( 'code', $( this ).data( 'default-value' ) );
                        }

                        if ( $( this ).summernote( 'codeview.isActivated' ) )
                        {
                            $( this ).summernote( 'codeview.deactivate' );
                        }
                    }
                }
            }
        );
    };

    VisualEditor.prototype.fillModal = function ( type, data )
    {
        var self = this;

        this.log( 'Filling modal form' );
        this.log( type );
        this.log( data );

        // Set widget type
        $( '.dd-widget-type-select', this.$modal )[ 0 ].selectize.setValue( type );

        // Fill widget data
        $.each( data, function ( key, value )
            {
                if ( key === 'class' )
                {
                    var _selectize   = $( '.dd-widget-class', self.$modal )[ 0 ].selectize;
                    var _val_options = value.split( ' ' );

                    for ( var i in _val_options )
                    {
                        _selectize.addOption(
                            {
                                label: _val_options[ i ],
                                value: _val_options[ i ]
                            }
                        );
                    }

                    _selectize.refreshOptions( false );
                    _selectize.setValue( _val_options );

                    return;
                }

                if ( key === 'col_class' && ( ( typeof value === 'string' && value !== '' ) || value.length ) )
                {
                    if ( typeof value === 'string' )
                    {
                        value = value.split( ' ' );
                    }

                    $.each( value, function ()
                        {
                            var match = this.match( /^col\-([a-z]{2})\-([0-9]+)$/ );

                            var _input = null;

                            if ( match )
                            {
                                _input = $( '*[name="col_size[' + match[ 1 ] + ']"]', self.$modal );
                                if( _input.length )
                                {
                                    _input[ 0 ].selectize.setValue( match[ 2 ] );
                                }
                                return;
                            }

                            match = this.match( /^col\-([a-z]{2})\-offset\-([0-9]+)$/ );

                            if ( match )
                            {
                                _input = $( '*[name="col_offset[' + match[ 1 ] + ']"]', self.$modal );
                                if( _input.length )
                                {
                                    _input[ 0 ].selectize.setValue( ( match[ 2 ] === '0' ? 'none' : match[ 2 ] ) );
                                }
                                return;
                            }

                            match = this.match( /^hidden\-([a-z]{2})$/ );

                            if ( match )
                            {
                                _input = $( '*[name="hide_device[' + match[ 1 ] + ']"]', self.$modal );
                                if( _input.length )
                                {
                                    _input.prop( 'checked', true );
                                }
                                return;
                            }
                        }
                    );

                    return;
                }

                var $elm = $( '*[name="' + type + '[' + key + ']' + ( typeof value === 'object' ? '[]' : '' ) + '"]', self.$modal ).last();

                if ( !$elm.length )
                {
                    var $imageList = $( '#elm_widget_images_' + type + '_' + key );

                    if ( $imageList.length )
                    {
                        if ( typeof value === 'string' )
                        {
                            value = [ value ];
                        }

                        $.each( value, function ( i, file )
                            {
                                self.addInputImage( file, $imageList );
                            }
                        );
                    }

                    return;
                }

                /*if( $elm.data( 'editable' ) === false )
                 {
                 return;
                 }*/

                if ( $elm.attr( 'type' ) === 'checkbox' )
                {
                    if ( value )
                    {
                        $elm.prop( 'checked', true );
                    }
                }
                else
                {
                    $elm.val( value );
                }

                if ( $elm.hasClass( 'dd-editor' ) && typeof $().summernote === 'function' )
                {
                    var editorContext = $elm.data( 'summernote' );

                    $elm.summernote( 'code', value );

                    editorContext.invoke( 'codeview.activate' );
                    editorContext.invoke( 'codeview.deactivate' );
                }

                if ( $elm.hasClass( 'dd-color-picker' ) && typeof $().minicolors === 'function' )
                {
                    $elm.minicolors( 'value', value );
                }

                if ( $elm[ 0 ].selectize )
                {
                    if ( $elm.hasClass( 'dd-data-picker' ) )
                    {
                        $elm[ 0 ].selectize.load( function ( callback )
                            {
                                $.ajax( {
                                    url: self.options.actionLink + '&fnc=doShortCodeAction&action=' + $elm.data( 'action' ) + '&shortcode=' + $elm.data( 'shortcode' ) + '&value=' + value,
                                    type: 'GET',
                                    error: function ()
                                    {
                                        callback();
                                    },
                                    success: function ( res )
                                    {
                                        callback( res );
                                        $elm[ 0 ].selectize.setValue( value );
                                    }
                                } );
                            }
                        );
                    }
                    else
                    {
                        $elm[ 0 ].selectize.setValue( value );
                    }
                }

            }
        );

    };

    VisualEditor.prototype.modal = function ( options, callback )
    {
        var self = this;

        this.log( 'Showing modal' );

        if ( typeof options === 'function' )
        {
            callback = options;
            options  = {};
        }

        options = $.extend(
            {
                backdrop: false,
                keyboard: false,
                show: true
            },
            ( typeof options === 'object' && options )
        );

        this.$modal.one( 'show.bs.modal', function ()
            {
                self.log( 'Modal shown' );

                // Save widget form
                $( '.dd-widget-form', self.$modal ).one( 'submit', function ( e )
                    {
                        e.preventDefault();

                        self.log( 'Triggering form submit' );

                        var params  = {};
                        var type    = $( '#elm_widget_type', this ).val();
                        var css     = $( '#elm_widget_class', this ).val();
                        var col_css = [];

                        // Going through type params
                        $.each( $( this ).serializeArray(), function ()
                            {
                                var type_match = this.name.match( new RegExp( "^" + type + "\\\[([^\\\]]*)\\\](\\\[?)" ) );

                                if ( type_match !== null && type_match.length === 3 )
                                {
                                    if ( type_match[ 2 ].length )
                                    {
                                        if ( !params[ type_match[ 1 ] ] )
                                        {
                                            params[ type_match[ 1 ] ] = [];
                                        }

                                        params[ type_match[ 1 ] ].push( this.value );
                                    }
                                    else
                                    {
                                        var $elm = $( '*[name="' + this.name + '"]' );

                                        if( typeof $().summernote === 'function' && $elm.hasClass( 'dd-editor' ) )
                                        {
                                            var editorContext = $elm.data( 'summernote' );

                                            // deactivate codeview before getting value
                                            if( editorContext.invoke('codeview.isActivated') )
                                            {
                                                editorContext.invoke( 'codeview.deactivate' );
                                            }

                                            editorContext.invoke( 'codeview.activate' );

                                            params[ type_match[ 1 ] ] = $elm.summernote( 'code' );
                                        }
                                        else
                                        {
                                            params[ type_match[ 1 ] ] = this.value;
                                        }
                                    }
                                }

                                if ( this.value !== '' )
                                {
                                    var col_match = this.name.match( new RegExp( /^(col_offset|col_size|hide_device)\[([^\]]*)\]/ ) );

                                    if ( col_match !== null && col_match.length === 3 )
                                    {
                                        switch ( col_match[ 1 ] )
                                        {
                                            case 'col_size':
                                                col_css.push( 'col-' + col_match[ 2 ] + '-' + this.value );
                                                break;

                                            case 'col_offset':
                                                if ( col_match[ 2 ] === 'xs' && this.value === 'none' )
                                                {
                                                    break;
                                                }

                                                col_css.push( 'col-' + col_match[ 2 ] + '-offset-' + ( this.value === 'none' ? '0' : this.value ) );
                                                break;

                                            case 'hide_device':
                                                col_css.push( 'hidden-' + col_match[ 2 ] );

                                                break;
                                        }
                                    }
                                }

                            }
                        );

                        params[ 'class' ] = css;

                        if ( col_css.length )
                        {
                            params.col_class = col_css.join( ' ' );
                        }

                        var $imageList = $( '.dd-widget-image-list[data-input-name^="' + type + '"]' );

                        if ( $imageList.length )
                        {
                            $imageList.each( function ()
                                {
                                    var type_match = $( this ).data( 'input-name' ).match( new RegExp( "^" + type + "\\\[([^\\\]]*)\\\](\\\[?)" ) );

                                    if( type_match )
                                    {
                                        params[ type_match[ 1 ] ] = self.serializeInputImages( $( this ) );
                                    }
                                }
                            );
                        }

                        // todo: check required fields

                        if ( typeof callback === 'function' )
                        {
                            // Exec the callback, stops on false
                            if ( callback.call( self, type, params ) === false )
                            {
                                return;
                            }
                        }
                        else
                        {
                            // todo: log
                        }

                        self.$modal.modal( 'hide' );
                    }
                );
            }
        ).one( 'shown.bs.modal', function ()
            {
                if ( $( '.dd-tab-form', self.$modal ).length )
                {
                    $( '.dd-tab-form .nav-tabs li:visible > a', self.$modal ).first().tab( 'show' );
                }
            }
        ).one( 'hidden.bs.modal', function ()
            {
                $( 'a[href="#tab_widget_main"]', self.$modal ).parent().show();
                $( '.dd-widget-form', self.$modal ).off( 'submit' );

                // Clear nested grid property
                self.nestedgridstack = null;
            }
        ).modal( options );
    };


    // Make VisualEditor public
    window.VisualEditor = VisualEditor;

}( jQuery );
