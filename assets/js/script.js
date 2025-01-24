/* BW Language Switcher Script */

jQuery(document).ready(function ($) {
    // Toggle the display of the language list on click
    $('#bw-language-switcher .current-language').on('click', function () {
        $(this).siblings('.language-list').toggleClass('hidden');
    });

    // Optional: Hide the language list when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#bw-language-switcher').length) {
            $('#bw-language-switcher .language-list').addClass('hidden');
        }
    });
});
