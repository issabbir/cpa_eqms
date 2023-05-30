(function($) {

    var filter_data = [];

    $(document).ready(function () {
        const data_tables = $(document).find('.datatable');
        if (data_tables.length > 1) {
            data_tables.each(function (i,t) {
                    loadTable($(t),  $(document).find("#datatable_filter_form"));
             });
        }
        else {
            loadTable(data_tables,  $(document).find("#datatable_filter_form"));
        }


        $(document).find("#datatable_filter_form").on('submit', function (e) {
            e.preventDefault();
            filter_data = serializeForm($(this).serializeArray());
            window.oTable.draw();
        });
    });

    /**
     * Datatable render with ajax paging
     *
     * @param data_tables
     */
    var loadTable = function(data_tables, filter_form) {
        const page = data_tables.data('page');
        const url = data_tables.data('url');
        const csrf = data_tables.data('csrf');
        const columns = [];
        data_tables.find('tr th').each(function(i,th) {
            //console.log(th);
            const column = {
                "data" : $(th).data('col'),
                "name":$(th).data('col'),
                "orderable": ($(th).data('col') == 'action')?false:true,
                "searchable": ($(th).data('col') == 'action')?false:true,
            };
            columns.push(column);
        });



        window.oTable = data_tables.DataTable({
            processing: true,
            serverSide: true,
            pageLength: page ,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
            initComplete: function(settings, json) {
                $('body').find('.dataTables_scrollBody').css("height", "auto");
                $('body').find('.dataTables_scrollBody').css("max-height", "300px");
            },
            ajax: {
                url: url,
                type:'POST',
                'headers': {
                    'X-CSRF-TOKEN': csrf
                },
                data : function (data) {
                    data.filter_data = filter_data;
                },
            },
            "columns": columns,
            language: {
                paginate: {
                    next: '<i class="bx bx-chevron-right">',
                    previous: '<i class="bx bx-chevron-left">'
                }
            }
        });

    };

    function serializeForm( form_data ) {
        var fd = form_data;
        var d = {};
        $(fd).each(function() {
            if (d[this.name] !== undefined){
                if (!Array.isArray(d[this.name])) {
                    d[this.name] = [d[this.name]];
                }
                d[this.name].push(this.value);
            }else{
                d[this.name] = this.value;
            }
        });
        return d;
    }


    $(document).ready(function () {
        $(".emp_id").select2({
            minimumInputLength: 1,
            dropdownPosition: 'below',
            allowClear: true,
            ajax: {
                url: '/employee-list-by-code',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (obj) {
                            return {
                                id: obj.emp_id,
                                text: obj.emp_name,
                            };
                        })
                    };
                },
                cache: false
            },
        });
    });

})(jQuery);
