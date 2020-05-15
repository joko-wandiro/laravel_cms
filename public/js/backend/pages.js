tinymce.init({
    selector: '#content',
    height: 400,
    plugins: ['lists', 'link', 'image', 'textcolor', 'code', 'fullscreen'],
    menubar: false,
    toolbar: 'undo redo | removeformat | formatselect | fontselect | fontsizeselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | bold italic underline strikethrough | blockquote | bullist numlist | link image | code fullscreen | mediamanager',
    setup: function (editor) {
        editor.addButton('mediamanager', {
            text: 'Media',
            icon: false,
            onclick: function () {
                $MediaManager.editor = editor;
                $('#media-btn').trigger('click');
            }
        });
    }
});
$(document).ready(function () {
    $(':input[name="title"]').keyup(function () {
        $elem = $(this);
        title = $elem.val();
        $(':input[name="url"]').val(title.toLowerCase().replace(/\s+/ig, "-"));
    })
    // Implement Media Manager Box
    MediaManagerOpts = {
        selector: $('#feature-image'),
        selector_editor: $('#media-btn'),
        target: $(':input[name="id_featured_image"]'),
        template: $('#media-manager-box'),
    }
    $MediaManager = new MediaManager(MediaManagerOpts);
});