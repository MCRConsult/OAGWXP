/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [{
            name: 'clipboard',
            groups: ['clipboard', 'undo']
        },
        // {
        //     name: 'insert',
        //     groups: ['table', 'insert']
        // },
        {
            name: 'forms',
            groups: ['forms']
        },
        {
            name: 'tools',
            groups: ['tools']
        },
        {
            name: 'document',
            groups: ['mode', 'document', 'doctools']
        },
        {
            name: 'others',
            groups: ['others']
        },
        '/',
        {
            name: 'basicstyles',
            groups: ['basicstyles', 'cleanup']
        },
        {
            name: 'paragraph',
            groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
        },
        {
            name: 'styles',
            groups: ['styles']
        },
        {
            name: 'colors',
            groups: ['colors']
        }
    ];

    config.removeButtons = 'Subscript,Superscript,Insert Special Character,Image,SpecialChar,HorizontalRule,Blockquote,Source';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';

    config.extraPlugins = 'pastefromword';

	config.replaceClass = 'ckeditor';
    config.extraPlugins = 'lineheight';
    // config.tabSpaces = 6;

};
