jQuery(document).ready(function($) {

    prettyPrint();
    $('form').formErrorWarning();

    $('textarea.tinymce').tinymce({
        theme: 'modern',

        toolbar1: "+ - × / % | < > <= >= == != ∧ ∨ ¬ | ternary | min max | const type",

        setup: function(editor) {
            const allowedKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', 'Backspace'];

            editor.on('keydown', e => {
                if (!allowedKeys.includes(e.key)){
                    e.preventDefault();
                }
            });

            const mathOper = ['+', '-', '×', '/', '%'];
            mathOper.forEach(e => {
                editor.addButton(e, {
                    text: e,
                    onclick: function(_) {
                        editor.insertContent('( _ ' + e + ' _ )');
                    }
                })
            });

            const logicOper = ['<', '>', '<=', '>=', '==', '!=', '∧', '∨'];
            logicOper.forEach(e => {
                editor.addButton(e, {
                    text: e,
                    onclick: function(_) {
                        editor.insertContent('( _ ' + e + ' _ )');
                    }
                })
            });

            editor.addButton('¬', {
                text: '¬',
                onclick: function(_) {
                    editor.insertContent('( ¬ _ )');
                }
            })

            editor.addButton('ternary', {
                text: 'ternary',
                onclick: function(_) {
                    editor.insertContent('( _ ? _ : _ )');
                }
            });

            editor.addButton('min', {
                text: 'min( )',
                onclick: function(_) {
                    editor.insertContent('MIN( _ , _ )');
                }
            });

            editor.addButton('max', {
                text: 'max( )',
                onclick: function(_) {
                    editor.insertContent('MAX( _ , _ )');
                }
            });

            var menuItems = [];
            identifiers.forEach(identifier => {
                menuItems.push({
                    text: identifier,
                    onclick: function () {
                        editor.insertContent('~' + identifier.replaceAll(' ', '_'));
                    }
                });
            });

            editor.addButton('type', {
                type: 'menubutton',
                text: 'Type',
                menu: menuItems
            });

        },

        menubar: false,
        toolbar_items_size: 'large',
        entity_encoding: 'raw',
        document_base_url: global_base_url,
        convert_urls: false,
        relative_urls: false,
        resize: false,
        autoresize_max_height: 400,
        autoresize_min_height: 150
    });
});