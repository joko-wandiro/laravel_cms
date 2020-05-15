jQuery(document).ready(function ($) {
    $('#nestable3').nestable();
    // Scaffolding
    $('form').submit(function (e) {
        e.preventDefault();
        $form = $(this);
        url = $form.attr('action');
        // Send AJAX Request
        myFeedback = new SiteFeedback();
        myFeedback.selector = $form;
        myFeedback.type = "menu";
        myAjax = new SiteAjax();
        myAjax.feedback = myFeedback;
        data = $form.serialize();
        data += '&menu=' + encodeURIComponent(JSON.stringify($('#nestable3').nestable('serialize')));
        myAjax.send(url, data, "json", 'POST');
    })
    $(document).on('click', '#btn-add-to-menu', function (e) {
        $element = $(this);
        $pages = $(':input[name="pages[]"]:checked');
        $.each($pages, function (i, elem) {
            $elem = $(elem);
            $label = $elem.parent();
            $li = $('<li class="dd-item">');
            $li.attr({'data-id': $elem.val()})
            $button = $('<button class="btn-menu-remove">');
            $button.html(Site.text_remove);
            $handle = $('<div class="dd-handle dd3-handle">');
            $content = $('<div class="dd3-content">');
            $content.html($elem.attr('data-title'));
            $content.append($button);
            $li.append($handle).append($content);
            $('#nestable3 > .dd-list').append($li);
            console.log();
        })
    });
    $(document).on('click', '.btn-menu-remove', function (e) {
        $elem = $(this);
        $li = $elem.parent().parent();
        $li.remove();
    });
})