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

// VISUAL EDITOR - TEST SUITE
// ==========================

+function()
{
    'use strict';

    describe( 'Visual Editor', function()
        {
            // Create VE Container
            // -------------------

            $( 'body' ).append( '<div class="dd-veditor"><div class="grid"></div></div>' );


            // Basic Tests
            // -----------

            it( 'calls the constructor without shortcodes and should throw an error', function()
                {
                    expect( function() { new VisualEditor() } ).toThrowError( 'no shortcodes given' );
                }
            );

            it( 'calls the constructor without options and should\'nt throw an error', function()
                {
                    var oPseudoShortcodes = { 'text': { name: 'Text' } };

                    expect( function() { new VisualEditor( oPseudoShortcodes ) } ).not.toThrow();
                }
            );

            it( 'calls the constructor with options and should\'nt throw an error', function()
                {
                    var oPseudoShortcodes = { 'text': { name: 'Text' } };

                    var oPseudoOptions = {
                        gridSize: 12
                    };

                    expect( function() { new VisualEditor( oPseudoShortcodes, oPseudoOptions ) } ).not.toThrow();
                }
            );

            it( 'sets the debug mode to true and checks if the debug mode is enabled', function()
                {
                    var oPseudoShortcodes = { 'text': { name: 'Text' } };

                    VisualEditor.DEBUG = true;

                    var oVisualEditor = new VisualEditor( oPseudoShortcodes );

                    expect( oVisualEditor.isDebugMode() ).toBeTruthy();
                }
            );

            it( 'sets the debug mode to false and checks if the debug mode is disabled', function()
                {
                    var oPseudoShortcodes = { 'text': { name: 'Text' } };

                    VisualEditor.DEBUG = false;

                    var oVisualEditor = new VisualEditor( oPseudoShortcodes );

                    expect( oVisualEditor.isDebugMode() ).not.toBeTruthy();
                }
            );

            /*it( 'removes the grid container and schould throw an error', function()
                {
                    $( '.dd-veditor' ).remove();

                    expect( function() { new VisualEditor( { 'text': { name: 'Text' } } ) } ).toThrowError( 'cannot find grid ul element' );
                }
            );*/


            // Widget Tests
            // ------------

            it( 'adds one text widget and checks if the widget is readable', function()
                {
                    var oPseudoShortcodes = { 'text': { name: 'Text', previewParam: 'content' } };
                    var oPseudoWidget = { type: 'text', params: { content: 'Test Content...' } };

                    VisualEditor.DEBUG = false;

                    var oVisualEditor = new VisualEditor( oPseudoShortcodes );
                    oVisualEditor.add( oPseudoWidget.type, oPseudoWidget.params );

                    expect( oVisualEditor.serialize().length ).toBe( 1 );
                }
            );
        }
    );

}();