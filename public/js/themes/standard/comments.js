jQuery(document).ready(function ($) {
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
        handleValidationFailure: function () {
//            if (this.type == "bulkAction") {
//                alert(Site.validationFailure);
//            }
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
                theobject: mySiteAjax,
                // End keep reference variable for specific AJAX Request
                dataType: dataType,
                type: type,
                url: url,
                data: data,
                beforeSend: function () {
                    this.theobject.parameters.showLoader();
                },
                finish: function () {
                    this.theobject.parameters.hideLoader();
                },
                success: function (response, type, xhr) {
                    this.finish();
                    switch (xhr.status) {
                        case 200:
                            myFeedback = this.theobject.feedback;
                            type = myFeedback.type;
                            myFeedback[type](response);
                            break;
                    }
                },
                error: function (xhr, type, statusCode) {
                    this.finish();
                    myFeedback = this.theobject.feedback
                    switch (xhr.status) {
                        case 400:
                            myFeedback.handleValidationFailure(xhr.responseJSON);
                            break;
                        default:
                            alert("There's something error occurs. Please try again !");
                    }
                }
            });
        }
    }

    // Send Request Bulk Action
    $('#btn-apply-bulk-action').on('click', function () {
        action = $(':input[name="xbulkaction"]').val();
        if (action) {
            // Send AJAX Request
            myFeedback = new SiteFeedback();
            myFeedback.selector = myDKScaffolding.selector;
            myFeedback.type = "bulkAction";
            myAjax = new SiteAjax();
            myAjax.feedback = myFeedback;
            $DKScaffolding = $('#dk-scaffolding-comments');
            data = $(':input[name="_token"], :input[name^="xselection"], :input[name="xbulkaction"]', $DKScaffolding).serialize();
            myAjax.send(Site.urlBulk, data, "json", 'POST');
        }
    })

    // Multiple checkbox selection
    $(document).on('change', '#dk-scaffolding-comments :input[name="xmultiselection"]', function () {
        $element = $(this);
        if ($element.is(':checked')) {
            $('#dk-scaffolding-comments :input[name^="xselection"]').prop('checked', true);
        } else {
            $('#dk-scaffolding-comments :input[name^="xselection"]').prop('checked', false);
        }
    })

    // Single checkbox selection
    $(document).on('change', '#dk-scaffolding-comments :input[name^="xselection"]', function () {
        $element = $(this);
        selection = $('#dk-scaffolding-comments :input[name^="xselection"]:checked').length;
        if (!selection) {
            $('#dk-scaffolding-comments :input[name="xmultiselection"]').prop('checked', false);
        }
    })

})