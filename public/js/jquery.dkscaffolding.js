(function ($) {
    function DKFeedback() {
    }
    DKFeedback.prototype = {
        'selector': $('body'),
        'default': function (data) {
            console.log(data);
        },
        sortColumns: function (response) {
//	        // Hooks Action afterUpdateSortingLinks
//	        this.afterUpdateSortingLinks(response);
            myDKFeedback = this;
            ct = 0;
            $.each(response.orders, function (columnName, record) {
                orderClass = (record.orderType == 'ASC') ? 'headerSortUp' :
                        'headerSortDown';
                activeClass = (record.active) ? ' active' : '';
                $a = $('table thead tr.dk-table-row-order th a.sortable:eq(' + ct + ')', myDKFeedback.selector);
                $th = $a.parent();
                $a.attr({'href': record.url});
                $th.attr({'class': orderClass + activeClass});
                ct++;
            })
        },
        content: function (response) {
            // Pagination
            $('.dk-container-pagination', this.selector).html(response.pagination);
            // Pagination info
            $('.dk-container-pagination-info', this.selector).html(response.paginationInfo);
            // Sort columns
            this.sortColumns(response);
            // Records
            $tbody = $('table tbody', this.selector);
            $tbody.empty();
            records = response.records;
            if (typeof records != "string") {
                $.each(records, function (index, record) {
                    $tr = $('<tr/>');
                    $.each(record, function (columnIndex, column) {
                        $td = $('<td/>')
                        $td.html(column);
                        $tr.append($td);
                    });
                    $tbody.append($tr);
                })
            } else {
                columnLength = $('table thead tr:first th', this.selector).length;
                $tr = $('<tr/>');
                $td = $('<td/>')
                $td.attr({'colspan': columnLength}).html(records);
                $tr.append($td);
                $tbody.append($tr);
            }
        },
    }
    function DKAjax(options) {
        options = typeof (options) == "undefined" ? {} : options;
        parameters = typeof (options.parameters) == "undefined" ? {} : options.parameters;
        instance = this;
        instance.parameters = $.extend({}, instance.parameters, parameters);
    }
    DKAjax.prototype = {
        feedback: new DKFeedback(),
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
            myDKAjax = this;
            $.ajax({
                // Start keep reference variable for specific AJAX Request
                theobject: myDKAjax,
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
                    switch (xhr.status) {
                        default:
                            alert("There's something error occurs. Please try again !");
                    }
                }
            });
        }
    }
    function DKScaffolding(options) {
        parameters = typeof (options.parameters) == "undefined" ? {} : options.parameters;
        instance = this;
        instance.selector = options.selector;
        instance.parameters = $.extend({}, instance.parameters, parameters);
        instance.init();
    }
    DKScaffolding.prototype = {
        // Selector
        selector: null,
        // Parameters
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
        getAjax: function (Feedback) {
            myAjax = new DKAjax();
            myAjax.parameters.loaderContent = this.parameters.loaderContent;
            myAjax.parameters.showLoader = this.parameters.showLoader;
            myAjax.parameters.hideLoader = this.parameters.hideLoader;
            myAjax.feedback = Feedback;
            return myAjax;
        },
        pagination: function () {
            this.selector.on('click', '.dk-section-pagination .pagination li a',
                    {DKScaffolding: this}, function (e) {
                e.preventDefault();
                myDKScaffolding = e.data.DKScaffolding;
                $elem = $(this);
                url = $elem.attr('href');
                data = {};
                // Send AJAX Request
                myFeedback = new DKFeedback();
                myFeedback.selector = myDKScaffolding.selector;
                myFeedback.type = "content";
                myAjax = myDKScaffolding.getAjax(myFeedback);
                myAjax.send(url, data, "json");
            })
        },
        sorting: function () {
            this.selector.on('click', 'table thead tr th a.sortable', {DKScaffolding: this}, function (e) {
                e.preventDefault();
                myDKScaffolding = e.data.DKScaffolding;
                $elem = $(this);
                url = $elem.attr('href');
                data = {};
                // Send AJAX Request
                myFeedback = new DKFeedback();
                myFeedback.selector = myDKScaffolding.selector;
                myFeedback.type = "content";
                myAjax = myDKScaffolding.getAjax(myFeedback);
                myAjax.send(url, data, "json");
            })
        },
        formButtons: function () {
            this.selector.on('click', 'form :reset', {DKScaffolding: this}, function (e) {
                e.preventDefault();
                myDKScaffolding = e.data.DKScaffolding;
                $form = $('form', myDKScaffolding.selector);
                $form[0].reset();
                $form.trigger('submit');
            })
            this.selector.on('submit', 'form', {DKScaffolding: this}, function (e) {
                e.preventDefault();
                myDKScaffolding = e.data.DKScaffolding;
                $elem = $(this);
                url = $elem.attr('action');
                data = $elem.serialize();
                // Send AJAX Request
                myFeedback = new DKFeedback();
                myFeedback.selector = myDKScaffolding.selector;
                myFeedback.type = "content";
                myAjax = myDKScaffolding.getAjax(myFeedback);
                myAjax.send(url, data, "json");
            })
        },
        paging: function () {
            this.selector.on('change', 'form :input[name="recordsPerPage"]',
                    {DKScaffolding: this}, function (e) {
                myDKScaffolding = e.data.DKScaffolding;
                $form = $('form', myDKScaffolding.selector);
                $form.trigger('submit');
            })
        },
        init: function () {
            action = this.selector.attr('data-action');
            if (action == "form") {
                validationOptions = {
                    rules: validationRules,
                };
                if (typeof (this.parameters.modifyValidationOptions) == "function") {
                    // Hooks Filter modifyValidationOptions
                    validationOptions = this.parameters.modifyValidationOptions(validationOptions);
                }
                $('form', this.selector).validate(validationOptions);
            } else if (action == "list") {
                this.pagination();
                this.sorting();
                this.paging();
                this.formButtons();
            }
        }
    }

    $.fn.DKScaffolding = function (options) {
        $elements = this;
        $elements.DKScaffolding = [];
        options = typeof (options) == "undefined" ? {} : options;
        $elements.each(function () {
            $element = $(this);
            DKScaffoldingOptions = {
                selector: $element,
                parameters: options,
            }
            myDKScaffolding = new DKScaffolding(DKScaffoldingOptions);
            $elements.DKScaffolding.push(myDKScaffolding);
        })
        return $elements;
    };
})(jQuery);