$(document).ready(function(){
    var isTableLoaded = false;
    var i;
    var oTable = $(".dataTables-example").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/ajax/api/',
            "dataSrc": function(json) {
                if(json.data == 'not_authorized') {
                    document.location.href = '/auth';
                } else {
                    return json.data;
                }
            },
            "data": {controller: apiController, action: apiAction},
            "type": "POST"
        },
        "dom": "<'html5buttons'B>lTfgitp",
        "pageLength": 50,
        "language": {
            //"url": "/js/plugins/dataTables/Russian.json"
            "url": "/modules/admin/plugins/datatables/russian.json"
        },
        "order": [[ 0, "asc" ]],
        "bAutoWidth": false,
        "bDeferRender": true,
        "fnDrawCallback": function( oSettings ){
            if( !isTableLoaded){
                isTableLoaded = true;
                this.fnAdjustColumnSizing();
            }
        },
//        "oSearch": {
//            "sSearch": "{{ appName }}"
//        },
        "aoColumnDefs": datatablesColumns,
        "buttons": [
            {"extend": "copy"},
            {"extend": "csv"},
            {"extend": "excel", title: "ExampleFile"},
            {"extend": "pdf", title: "ExampleFile"},
            {"extend": "print",
                "customize": function (win){
                    $(win.document.body).addClass("white-bg");
                    $(win.document.body).css("font-size", "10px");
                    $(win.document.body).find("table")
                        .addClass("compact")
                        .css("font-size", "inherit");
                }
            }
        ]
    });

    if(datatablesFilters.length > 0) {
        for(i = 0; i < datatablesFilters.length; i++) {
            $('#' + datatablesFilters[i]).change(function() {
                //oTable.draw();
                oTable.ajax.reload();
            });
        }
    }

    $('.dataTables-example tbody').on( 'click', 'button', function () {
        //$.deleteId = $('#appDelete').attr("value");
        $.deleteId = $(this).val();
        var data = oTable.row( $(this).parents('tr') ).data();
        $('.modal-title').empty().append(data[1]);
        $('.modal-body').empty().append("<p>" + data[2] + "</p>");
    } );
    $("#appDeleteId").click(function () {
        $.get(datatablesDeleteUrl + $.deleteId, function() {
            oTable.row().draw();
        });
    });
});

/*$(function(){
    // add multiple select / deselect functionality
    $("#selectall").click(function () {
        $(".case").prop("checked", this.checked);
        $(".case").attr("checked", this.checked);
    });

    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".case").click(function(){
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });
});*/