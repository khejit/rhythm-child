<?php
/**
 * Created by PhpStorm.
 * User: khejit
 * Date: 2016-08-03
 * Time: 16:32
 */
?>
<input type="text" class="selected_image" />
<input type="button" class="upload_image_button" value="Upload Image">

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>-->
<script>
    jQuery(document).ready(function($) {
        var custom_uploader;

        var args = top.tinymce.activeEditor.windowManager.getParams();
        var wp = args.wp;

        $('.upload_image_button').click(function (e) {
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
                $upload_button.siblings('input[type="text"]').val(attachment.url);
            });

            //Open the uploader dialog
            custom_uploader.open();

        });
    });
</script>