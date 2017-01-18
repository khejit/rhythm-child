/**
 * Created by khejit on 2016-08-03.
 */
jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.someplugin', {

        init : function(ed, url) {
            ed.addCommand('mcebutton', function () {
                ed.windowManager.open(
                    {
                        file: url + '/../editor_button.php', // file that contains HTML for our modal window
                        width: 800 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
                        height: 600 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
                        inline: 1
                    },
                    {
                        plugin_url: url,
                        wp: wp
                    }
                );
            });

            // Register buttons
            ed.addButton('someplugin_button', {title : 'Insert Seomthing', cmd : 'mcebutton', image: url + '/images/some_button.gif' });

        }
    });

    tinymce.PluginManager.add('someplugin_button', tinymce.plugins.someplugin);

});
