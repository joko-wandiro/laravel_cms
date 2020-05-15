function MediaManager(options) {
    defaultOptions = {
        // Selector
        selector: null,
        // Selector Editor
        selector_editor: null,        
        // Target
        target: null,
        // Template
        template: null,
    }
    parameters = $.extend({}, defaultOptions, options);
    instance = this;
    $.each(defaultOptions, function (index, property) {
        instance[index] = parameters[index];
    })
    instance.init();
}

MediaManager.prototype = {
    // Current selector
    is_editor: false,
    // Current selector
    current: null,
    // Status
    status: false,
    show_link: function (e) {
        e.preventDefault();
        $elem = $(this);
        me = e.data.me;
        me.is_editor = false;
        // Set current selector
        me.current = $elem;
        me.show();
    },
    show_editor: function (e) {
        e.preventDefault();
        $elem = $(this);
        me = e.data.me;
        me.is_editor = true;
        // Set current selector
        me.current = $elem;
        me.show();
    },
    show: function () {
        // Remove active class on medias
        $(this.template.selector + ' .media').removeClass('active');
        // Is callback prepareMessage valid
        if (typeof (this.prepareMessage) == "function") {
            // Prepare message
            this.prepareMessage();
        }
        // Show Modal Dialog
        this.template.modal('show');
    },
    hide: function (e) {
        this.template.modal('hide');
    },
    select_image: function (e) {
        $elem = $(this);
        $(me.template.selector + ' .media').removeClass('active');
        $elem.addClass('active');
    },
    remove_image: function (e) {
        e.preventDefault();
        me = e.data.me;
        $('#feature-image-preview').remove();
        me.target.val('');
    },
    init: function () {
        this.template.modal({backdrop: 'static', keyboard: false}).modal('hide');
        // Attach Event Handler click to selector
        $(document).on('click', this.selector.selector, {me: this}, this.show_link);
        // Attach Event Handler click for editor
        $(document).on('click', this.selector_editor.selector, {me: this}, this.show_editor);
        // Attach Event Handler click to medias gallery
        $(document).on('click', this.template.selector + ' .media', {me: this}, this.select_image);
        // Attach Event Handler click to medias gallery
        $(document).on('click', '#feature-image-btn-remove', {me: this}, this.remove_image);
        // Attach Event Handler click to buttons selector
        $(document).on('click', this.template.selector + ' .btn-action', {me: this}, this.select);
    },
    select: function (e) {
        $elem = $(this);
        status = $elem.val();
        me = e.data.me;
        me.status = status;
        $media = $(me.template.selector + ' .media.active');
        if ($media.length) {
            id = $media.attr('data-id');
            src = $media.attr('data-src');
            name = $media.attr('data-name');
            if (me.is_editor) {
                me.editor.insertContent('<img src="' + src + '" class="img-responsive" alt="'+name+'" title="'+name+'" />');
            } else {
                me.target.val(id);
                $('#feature-image-preview').remove();
                $p = $('<p>').attr('id', 'feature-image-preview');
                $a = $('<a>').attr({'id': 'feature-image-btn-remove', 'href': '#'}).html('Remove');
                $img = $('<img/>').attr({'src': src, 'height': 200});
                $p.append($img).append($a);
                me.target.after($p);
            }
            // Hide Modal Dialog
            me.hide(e);
        }
    },
}