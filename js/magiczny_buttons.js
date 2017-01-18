/**
 * Created by khejit on 2016-08-03.
 */

jQuery(document).ready(function($) {


    tinymce.create('tinymce.plugins.magiczny_buttons_plugin', {
        init : function(ed, url) {
            // Register command for when button is clicked

            ed.addCommand('wypunktowanie_ozdobne_insert_shortcode', function() {
                var selected = tinyMCE.activeEditor.selection.getContent();
                if(selected){
                    var content = '[wypunktowanie_shortcode]';
                    content += selected;
                    content += '[/wypunktowanie_shortcode]'
                }

                tinymce.execCommand('mceInsertContent', false, content);
            });

            ed.addCommand('plussign_insert_shortcode', function() {
                var selected = tinyMCE.activeEditor.selection.getContent();
                if(selected){
                    var content = '[plussign_shortcode]';
                    content += selected;
                    content += '[/plussign_shortcode]'
                } else {
                    content = '[plussign_shortcode]'
                }

                tinymce.execCommand('mceInsertContent', false, content);
            });

            ed.addCommand('minussign_insert_shortcode', function() {
                var selected = tinyMCE.activeEditor.selection.getContent();
                if(selected){
                    var content = '[minussign_shortcode]';
                    content += selected;
                    content += '[/minussign_shortcode]'
                } else {
                    content = '[minussign_shortcode]'
                }

                tinymce.execCommand('mceInsertContent', false, content);
            });

            ed.addCommand('lista_tresci_insert_shortcode', function(atts) {
                //var selected = tinyMCE.activeEditor.selection.getContent();
                var content = '[rs_lista_tresci content_list="'+atts.content_list+'"]';

                tinymce.execCommand('mceInsertContent', false, content);
            });

            ed.addCommand('ciekawostka_insert_shortcode', function(atts) {
                var selected = tinyMCE.activeEditor.selection.getContent();
                if(selected){content = selected} else {content = atts.content}
                var output = '[rs_ciekawostka title="'+atts.title+'" insides="'+content+'"]';

                tinymce.execCommand('mceInsertContent', false, output);
            });

            ed.addCommand('sekcja_insert_shortcode', function(atts) {
                //var selected = tinyMCE.activeEditor.selection.getContent();
                var content = '[rs_sekcja content_sect="'+atts.content_sect+'"]';

                tinymce.execCommand('mceInsertContent', false, content);
            });

            ed.addCommand('blockquote_insert_shortcode', function(atts) {
                var content2 = '[rs_blockquote_for_tinymce content="'+atts.content+'" cite="'+atts.cite+'" image_link="'+atts.image_link+'"]';

                tinymce.execCommand('mceInsertContent', false, content2);
            });

            // Register buttons - trigger above command when clicked
            ed.addButton('magiczny_buttons',
                {
                    title : 'Insert facebook form',
                    type : 'menubutton',
                    tooltip: tinymce.translate('Magiczny Buttons'),
                    image: url + '/../img/magiczny-icon.png',
                    menu: [
                        {
                            text: 'Lista treści',
                            onclick: function(){
                                ed.windowManager.open( {
                                    title: 'Lista treści',
                                    body: [
                                        {
                                            type: 'textbox',
                                            label: 'Podaj nazwy sekcji oddzielone przecinkami',
                                            name: 'content_list'
                                        }
                                    ],
                                    onsubmit: function(e){
                                        var atts = {content_list: e.data.content_list};
                                        ed.execCommand('lista_tresci_insert_shortcode', atts)
                                    }
                                })
                            }
                        },
                        {
                            text: 'Sekcja',
                            onclick: function(){
                                ed.windowManager.open( {
                                    title: 'Sekcja',
                                    body: [
                                        {
                                            type: 'textbox',
                                            label: 'Podaj nazwę sekcji',
                                            name: 'content_sect'
                                        }
                                    ],
                                    onsubmit: function(e){
                                        var atts = {content_sect: e.data.content_sect};
                                        ed.execCommand('sekcja_insert_shortcode', atts)
                                    }
                                })
                            }
                        },
                        {
                            text: 'Ciekawostka',
                            onclick: function(){
                                ed.windowManager.open( {
                                    title: 'Ciekawostka',
                                    body: [
                                        {
                                            type: 'textbox',
                                            name: 'title',
                                            label: 'Tytuł'
                                        },
                                        {
                                            type: 'textbox',
                                            name: 'content',
                                            label: 'Treść'
                                        },
                                    ],
                                    onsubmit: function (e){
                                        var atts = {content: e.data.content, title: e.data.title};
                                        ed.execCommand('ciekawostka_insert_shortcode', atts)
                                    }
                                })
                            }
                        },
                        {
                            text: 'Blok cytatu',
                            onclick: function(){
                                ed.windowManager.open( {
                                    title: 'Cytat',
                                    body: [
                                        {
                                            type: 'textbox',
                                            name: 'content',
                                            label: 'Treść'
                                        },
                                        {
                                            type: 'textbox',
                                            name: 'cite',
                                            label: 'Autor'
                                        },
                                        {
                                            type: 'filepicker',
                                            name: 'image_link',
                                            label: 'Link zdjęcia',
                                            id: 'selected_image'
                                        },
                                        {
                                            type: 'button',
                                            name: 'upload_image_button',
                                            id: 'upload_image_button',
                                            text: 'Wybierz zdjęcie',
                                            onclick: function(e){
                                                e.preventDefault();

                                                var $upload_button = $(this);

                                                //Extend the wp.media object
                                                custom_uploader = wp.media.frames.file_frame = wp.media({
                                                    title: 'Choose Image',
                                                    button: {
                                                        text: 'Choose Image'
                                                    },
                                                    multiple: false
                                                });

                                                //When a file is selected, grab the URL and set it as the text field's value
                                                custom_uploader.on('select', function () {
                                                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                                                    $('#selected_image > input').val(attachment.url);
                                                });

                                                //Open the uploader dialog
                                                custom_uploader.open();
                                            }
                                        }
                                    ],
                                    onsubmit: function (e){
                                        var atts = {content: e.data.content, cite: e.data.cite, image_link: e.data.image_link};
                                        ed.execCommand('blockquote_insert_shortcode', atts)
                                    }
                                })
                            }
                        },
                        {
                            text: 'Wypunktowanie ozdobne',
                            onclick: function(){
                                ed.execCommand('wypunktowanie_ozdobne_insert_shortcode')
                            }
                        },
                        {
                            text: 'Znak plus',
                            onclick: function(){
                                ed.execCommand('plussign_insert_shortcode')
                            }
                        },
                        {
                            text: 'Znak minus',
                            onclick: function(){
                                ed.execCommand('minussign_insert_shortcode')
                            }
                        },
                    ]
                });
        }
    });




    tinymce.PluginManager.add('magiczny_buttons', tinymce.plugins.magiczny_buttons_plugin);

});