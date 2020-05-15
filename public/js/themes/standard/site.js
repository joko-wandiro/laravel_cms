function SiteFeedback() {
}
SiteFeedback.prototype = {
    'selector': $('body'),
    'default': function (data) {
        console.log(data);
    },
    bulkAction: function (response) {
        htmlAlert = '<p class="alert alert-success fade in">' + response.message + '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>';
        $DKScaffolding = $('#dk-scaffolding-comments');
        $('.page-header', $DKScaffolding).after(htmlAlert);
        $('form', $DKScaffolding).trigger('submit');
    },
    handleValidationFailure_: function (response) {
        $.each(response, function (errorsIndex, errors) {
            $.each(errors, function (errorIndex, error) {
                $span = $('<span/>').addClass('error');
                $span.html(error);
                $input = $(':input[name="' + errorsIndex + '"]:enabled');
                $span.insertAfter($input);
            });
        })
    },
    handleValidationFailure: function (response) {
        $.each(response, function (fieldId, messages) {
            $span = $('<label/>').addClass('error');
            $span.html(messages.join("<br/>"));
            inputName = '';
            fields = fieldId.split(".");
            ct = 0;
            while (segment = fields.shift()) {
                if (!ct) {
                    inputName += segment;
                } else {
                    inputName += '[' + segment + ']';
                }
                ct++;
            }
            $input = $(':input[name="' + inputName + '"]:enabled');
            if ($input.is(':radio, :checkbox')) {
                $input = $input.parent();
            }
            $span.insertAfter($input);
        })
    },
    handleError: function (response) {
        $element = $('<p class="alert alert-danger"/>').html(response);
        this.selector.prepend($element);
    },
    contact: function (response) {
        $element = $('<p class="alert alert-success"/>').html(response);
        this.selector.prepend($element);
    },
    menu: function (response) {
        this.contact(response);
    },
    settings: function (response) {
        this.contact(response);
    }
}
function SiteAjax(options) {
    options = typeof (options) == "undefined" ? {} : options;
    parameters = typeof (options.parameters) == "undefined" ? {} : options.parameters;
    instance = this;
    instance.parameters = $.extend({}, instance.parameters, parameters);
}
SiteAjax.prototype = {
    feedback: new SiteFeedback(),
    parameters: {
        loaderContent: 'Loading...',
        showLoader: function () {
            $.blockUI({
                message: this.loaderContent,
                baseZ: 99999
            });
        },
        hideLoader: function () {
            $.unblockUI();
        },
    },
    send: function (url, data, dataType, type) {
        dataType = typeof (dataType) == "undefined" ? "html" : dataType;
        type = typeof (type) == "undefined" ? "GET" : type;
        mySiteAjax = this;
        $.ajax({
            // Start keep reference variable for specific AJAX Request
            my_site_ajax: mySiteAjax,
            // End keep reference variable for specific AJAX Request
            dataType: dataType,
            type: type,
            url: url,
            data: data,
            beforeSend: function () {
                $('.alert, .error').remove();
                this.my_site_ajax.parameters.showLoader();
            },
            finish: function () {
                this.my_site_ajax.parameters.hideLoader();
            },
            success: function (response, type, xhr) {
                this.finish();
                switch (xhr.status) {
                    case 200:
                    case 201:
                        myFeedback = this.my_site_ajax.feedback;
                        type = myFeedback.type;
                        myFeedback[type](response);
                        break;
                }
            },
            error: function (xhr, type, statusCode) {
                this.finish();
                myFeedback = this.my_site_ajax.feedback;
                switch (xhr.status) {
                    case 400:
                        myFeedback.handleValidationFailure(xhr.responseJSON);
                        break;
                    default:
                        myFeedback.handleError(xhr.responseJSON);
                }
            }
        });
    }
}