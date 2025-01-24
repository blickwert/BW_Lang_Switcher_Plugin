jQuery(document).ready(function ($) {
    $('.bw-upload-flag').on('click', function (e) {
        e.preventDefault();
        let button = $(this);
        let targetInput = $(button.data('target'));
        let frame = wp.media({
            title: 'Flagge auswählen',
            button: {
                text: 'Flagge verwenden'
            },
            multiple: false
        });

        frame.on('select', function () {
            let attachment = frame.state().get('selection').first().toJSON();
            targetInput.val(attachment.url);
            button.siblings('.flag-preview').html('<img src="' + attachment.url + '" alt="Flagge" style="max-height: 30px;" />');
        });

        frame.open();
    });
});
