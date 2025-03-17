
$(document).ready(function () {
    $('#dataTableOrders').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{ 
            "bSortable": false,
            "aTargets": [0,4,9]
        },{
            "bSearchable": false, 
            "aTargets": [0,4,9]
        }]
    });
});