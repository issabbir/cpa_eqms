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

function datePicker(selector) {
    var elem = $(selector);
    elem.datetimepicker({
        format: 'DD-MM-YYYY',
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
}

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

function selectCpaEmployees(selector, allEmployeesFilterUrl, selectedEmployeeUrl, callback)
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


function dateTimePicker(selector) {
    var elem = $(selector);
    elem.datetimepicker({
        format: 'hh:mm A',
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
        let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD HH:mm").format("YYYY-MM-DD HH:mm A");
        elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
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

function getSysDate() {
    let now = new Date();
    let month = (now.getMonth() + 1);
    let day = now.getDate();
    if (month < 10)
        month = "0" + month;
    if (day < 10)
        day = "0" + day;//YYYY-MM-DD
    let today =  day+ '-'+ month+ '-'+ now.getFullYear();
    return today;
}

function getSysTime() {
    let now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    let ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    let strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

function getSysTimeAdd() {
    let now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    let ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    hours = hours + 1;
    minutes = minutes < 10 ? '0'+minutes : minutes;
    let strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

function maxDateOff($selector, format='DD-MM-YYYY') {
    $($selector).datetimepicker({
        ignoreReadonly: true,
        useCurrent: false,
        format: format,
        maxDate : moment(),
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
    // For edit
    let preDefinedDateMax = $($selector).attr('data-predefined-date');
    console.log(preDefinedDateMax);

    if (preDefinedDateMax) {
        let preDefinedDateMomentFormat = moment(preDefinedDateMax, "DD-MM-YYYY").format("DD-MM-YYYY");
        $($selector).datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
}

function dateRangePicker(Elem1, Elem2){
    let minElem = $(Elem1);
    let maxElem = $(Elem2);

    minElem.datetimepicker({
        format: 'DD-MM-YYYY',
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
    maxElem.datetimepicker({
        useCurrent: false,
        format: 'DD-MM-YYYY',
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
    minElem.on("change.datetimepicker", function (e) {
        maxElem.datetimepicker('minDate', e.date);
    });
    maxElem.on("change.datetimepicker", function (e) {
        minElem.datetimepicker('maxDate', e.date);
    });

    let preDefinedDateMin = minElem.attr('data-predefined-date');
    let preDefinedDateMax = maxElem.attr('data-predefined-date');

    if (preDefinedDateMin) {
        let preDefinedDateMomentFormat = moment(preDefinedDateMin, "DD-MM-YYYY").format("DD-MM-YYYY");
        minElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }

    if (preDefinedDateMax) {
        let preDefinedDateMomentFormat = moment(preDefinedDateMax, "DD-MM-YYYY").format("DD-MM-YYYY");
        maxElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }

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

$('body').on('mouseenter mouseleave','.dropdown',function(e){
    var _d=$(e.target).closest('.dropdown');
    if (e.type === 'mouseenter')_d.addClass('show');
    setTimeout(function(){
        _d.toggleClass('show', _d.is(':hover'));
        $('[data-toggle="dropdown"]', _d).attr('aria-expanded',_d.is(':hover'));
    },300);
});
function hideNseek(formName, buttonName){
    $(buttonName).click(function() {
        $(formName).slideToggle('slow');
    });
}


$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    var filename = fileName.substring(0, 10);
    if (fileName.length > 10) {
        $(this).siblings(".custom-file-label").addClass("selected").html(filename+'...');
    }
});

function maxSysDatePicker(getId) {
    $(getId).datetimepicker({

        format: 'DD-MM-YYYY',
        maxDate: new Date(),
        useCurrent: false,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        // format: 'L',
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

var selectedItem = [];

$(document).ready(function() {
    $val = $('.sequenceData').val();
    if($val)
    {
        selectedItem = $val.split(',');
    }

    //console.log(selectedItem);
});

$('.searchable').multiSelect({
    keepOrder: true,
    selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'> <div class='custom-header'>Team Member</div>",
    selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Search'> <div class='custom-header'>Assign to Review</div>",
    selectableFooter: "<div class='custom-header' ><a class='btn btn-sm btn-primary' id='select-all' href='javascript:void(0)'>Select All</a></div>",
    selectionFooter: "<div class='custom-header'><a class='btn btn-sm btn-danger' id='deselect-all' href='javascript:void(0)'>Deselect All</a></div>",

    afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev().prev(),
            $selectionSearch = that.$selectionUl.prev().prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on('keydown', function(e){
                if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
            });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
            });
    },
    afterSelect: function(value){
        this.qs1.cache();
        let data = value.join(",");
        selectedItem.push(data);
        console.log(selectedItem);
        $('.sequenceData').val(selectedItem);
    },
    afterDeselect: function(value){
        let dataUnselect = value.join(",");
        let selectedItemIndex =  selectedItem.indexOf(dataUnselect);

        selectedItem.splice(selectedItemIndex, 1);
        console.log(value);
        console.log(selectedItem);
        $('.sequenceData').val(selectedItem);
        // console.log(selectedItem);
        this.qs1.cache();
        this.qs2.cache();
    },

});
$('#select-all').click(function(){
    $('.searchable').multiSelect('select_all');
    return false;
});
$('#deselect-all').click(function(){
    $('.searchable').multiSelect('deselect_all');
    return false;
});
$("#local-fob-type-local").click(function() {
    $("#fob_table").css('display','none');
});
$("#local-fob-type-foreign").click(function() {
    $("#fob_table").css('display','block');
});
