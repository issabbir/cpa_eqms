/*$(".alert").fadeTo(2000, 2000).slideUp(500, function(){
    $(".alert").slideUp(500);
});*/

/*--Text Editor Start---*/
//editor Option
var toolbarOptions = [
    ['bold', 'italic', 'underline'],        // toggled buttons
    [{'list': 'ordered'}, {'list': 'bullet'}],
    [{'align': []}],
    [{'size': ['small', false, 'large', 'huge']}]  // custom dropdown
];

var quill1 = new Quill('#editor1', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow',
});
var quill2 = new Quill('#editor2', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});
var quill3 = new Quill('#editor3', {
    modules: {
        toolbar: toolbarOptions,
    },
    theme: 'snow'
});
var quill4 = new Quill('#editor4', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow',
});

var incidenceDescriptionEditor = new Quill('#incidence_description_editor', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});

var incidenceDescriptionBnEditor = new Quill('#incidence_description_bn_editor', {
    modules: {
        toolbar: toolbarOptions
    },
    theme: 'snow'
});

function buttonShowClearTextAreaAndEditor() {
    $(".ql-toolbar").append('<span class="ql-formats bx-pull-right"><button type="button" style="width:50px;" class="clearDescription">Clear</button></span>');
}

$(document).ready(function () {
    buttonShowClearTextAreaAndEditor();
    jQueryValidateFileSizeValidator();
});

function replacePtagToBrTag(editor) {
    $('p').each(function () {
        editor = editor.replace("<p>", " ");
        editor = editor.replace("</p>", "<br>");
    });
    return editor;
}

/*Text Editor Start*/

/*function datePicker(selector) {
    var elem = $(selector);
    elem.datetimepicker({
        format: 'YYYY-MM-DD',
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    let preDefinedDate = elem.attr('data-predefined-date');

    if (preDefinedDate) {
        let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD").format("YYYY-MM-DD");
        elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
}*/

function districts(elem, container, url, decendentElem)
{
    $(elem).on('change', function() {
        let divisionId = $(this).val();
        if( ((divisionId !== undefined) || (divisionId != null)) && divisionId) {
            $.ajax({
                type: "GET",
                url: url+divisionId,
                success: function (data) {
                    $(container).html(data.html);
                    $(decendentElem).html('');
                },
                error: function (data) {
                    alert('error');
                }
            });
        } else {
            $(container).html('');
            $(decendentElem).html('');
        }
    });
}

function thanas(elem, url, container)
{
    $(elem).on('change', function() {
        let districtId = $(this).val();

        if( ((districtId !== undefined) || (districtId != null)) && districtId) {
            $.ajax({
                type: "GET",
                url: url+districtId,
                success: function (data) {
                    $(container).html(data.html);
                },
                error: function (data) {
                    alert('error');
                }
            });
        } else {
            $(container).html('');
        }
    });
}

/*function selectCpaEmployees(selector, allEmployeesFilterUrl, selectedEmployeeUrl)
{
    $(selector).select2({
        placeholder: "Select",
        allowClear: false,
        ajax: {
            url: allEmployeesFilterUrl, // '/ajax/employees'
            data: function (params) {
                if(params.term) {
                    if (params.term.trim().length  < 1) {
                        return false;
                    }
                } else {
                    return false;
                }

                return params;
            },
            dataType: 'json',
            processResults: function(data) {
                var formattedResults = $.map(data, function(obj, idx) {
                    obj.id = obj.emp_id;
                    obj.text = obj.emp_code+' ('+obj.emp_name+')';
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            },
        }
    });

    if(
        ($(selector).attr('data-emp-id') !== undefined) && ($(selector).attr('data-emp-id') !== null) && ($(selector).attr('data-emp-id') !== '')
    ) {
        selectDefaultCpaEmployee($(selector), selectedEmployeeUrl, $(selector).attr('data-emp-id'));
    }

    $(selector).on('select2:select', function (e) {
        var selectedEmployee = e.params.data;
        var that = this;

        if(selectedEmployee.emp_code) {
            $.ajax({
                type: "GET",
                url: selectedEmployeeUrl+selectedEmployee.emp_id, // '/ajax/employee/'
                success: function (data) {},
                error: function (data) {
                    alert('error');
                }
            });
        }
    });
}*/

function selectDefaultCpaEmployee(selector, selectedEmployeeUrl, empId)
{
    $.ajax({
        type: 'GET',
        url: selectedEmployeeUrl+empId, //  '/ajax/employee/'
    }).then(function (data) {
        // create the option and append to Select2
        var option = new Option(data.emp_code+' ('+data.emp_name+')', data.emp_id, true, true);
        selector.append(option).trigger('change');

        // manually trigger the `select2:select` event
        selector.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}


// Delete
    /*$(document).on("click", '.confirm-delete', function (e) {
        e.preventDefault();
        let that = $(this);
        let link = that.attr("href");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#dd3333",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function (e) {
            if (e.value === true) {
                $.ajax({
                    type: 'DELETE',
                    url: link,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            let table = that.closest('div').find('.datatable').DataTable();
                            table.ajax.reload(null, false); // user paging is not reset on reload
                            swal("Done!", results.message, "success");
                        } else {
                            swal("Error!", results.message, "error");
                        }
                    }
                });

            } else {
                e.dismiss;
            }

        }, function (dismiss) {
            return false;
        })
    });*/




    function datePickerUsingDiv(divSelector) { // divSelector is the targeted parent div of date input field
    var elem = $(divSelector);
    elem.datetimepicker({
        format: 'YYYY-MM-DD',
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
}

function branches(elem, url, container)
{
    $(elem).on('change', function() {
        let branchId = $(this).val();

        if( ((branchId !== undefined) || (branchId != null)) && branchId) {
            $.ajax({
                type: "GET",
                url: url+branchId,
                success: function (data) {
                    $(container).html(data.html);
                },
                error: function (data) {
                    alert('error');
                }
            });
        } else {
            $(container).html('');
        }
    });
}

function selectBookings(selector, allBookingsFilterUrl, selectedBookingUrl, callback, excludesCallback)
{
    $(selector).select2({
        placeholder: "Select",
        allowClear: false,
        ajax: {
            url: allBookingsFilterUrl,
            data: function (params) {
                var query = {
                    term: params.term,
                    exclude: excludesCallback
                }

                return query;
            },
            dataType: 'json',
            processResults: function(data) {
                var formattedResults = $.map(data, function(obj, idx) {
                    obj.id = obj.booking_mst_id;
                    obj.text = obj.booking_no;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            },
        }
    });

    if(
        ($(selector).attr('data-booking-id') !== undefined) && ($(selector).attr('data-booking-id') !== null) && ($(selector).attr('data-booking-id') !== '')
    ) {
        selectDefaultBooking($(selector), selectedBookingUrl, $(selector).attr('data-booking-id'));
    }

    $(selector).on('select2:select', function (e) {
        var selectedBooking = e.params.data;
        var that = this;

        if(selectedBooking.booking_no) {
            $.ajax({
                type: "GET",
                url: selectedBookingUrl+selectedBooking.booking_mst_id,
                success: function (data) {
                    callback(that, data);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }
    });
}

function selectDefaultBooking(selector, selectedBookingUrl, bookingId)
{
    $.ajax({
        type: 'GET',
        url: selectedBookingUrl+bookingId,
    }).then(function (data) {
        var info = data.booking;
        // create the option and append to Select2
        var option = new Option(info.booking_no, info.booking_mst_id, true, true);
        selector.append(option).trigger('change');

        // manually trigger the `select2:select` event
        selector.trigger({
            type: 'select2:select',
            params: {
                data: info
            }
        });
    });
}

function formSubmission(formElem, clickedElem, callback, message)
{
    $(clickedElem).click(function(e) {
        e.preventDefault();
        callback(formElem);
        var isValid = $(formElem).valid();

        if(isValid) {
            var confirmation = confirm(message);
            if(confirmation == true) {
                $(formElem).submit();
            }
        }
    });
}

$('.mobile-validation').on('keypress', function(e) {
    // e is event.
    var keyCode = e.which;
    /*
      8 - (backspace)
      32 - (space)
      48-57 - (0-9)Numbers
    */

    if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) {
        return false;
    }
});

function errorPlacement(error, element)
{
    if(element.attr('type') == 'radio') {
        error.insertAfter(element.parent().parent());
    } else if(element.hasClass('select2-hidden-accessible')) {
        error.insertAfter(element.next());
    } else {
        error.insertAfter(element);
    }
}

function jQueryValidateFileSizeValidator()
{
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, 'File size must be less than {0}');
}

function setFileName()
{
    $('input[type="file"]').on('change', function(e) {
        var fieldVal = $(this).val();

        fieldVal = fieldVal.replace("C:\\fakepath\\", "");

        if (fieldVal != undefined || fieldVal != "") {
            $(this).siblings(".custom-file-label").attr('data-content', fieldVal);
            $(this).siblings(".custom-file-label").text(fieldVal);
        }
    });
}

function removeRow()
{
    $('form.remove-row button[type="submit"]').on('click', function(e) {
        var that = this;
        var shouldRemove = window.confirm('Are you sure you want to remove?');

        if(shouldRemove) {
            $(that).parent('form').submit();
        } else {
            e.preventDefault();
        }
    });
}

$(".dynamicModal").on("click", function () {

    var news_id=this.getAttribute('news_id');
    $.ajax(
        {
            type: 'GET',
            url: '/get-top-news',
            data: {news_id:news_id},
            dataType: "json",
            success: function (data) {
                $("#dynamicNewsModalContent").html(data.newsView);
                $('#dynamicNewsModal').modal('show');
            }
        }
    );

});
