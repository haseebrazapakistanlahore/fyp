function deleteSubscriber(subscriberId) {
    var result = window.confirm('Are you sure you want to delete this subscriber?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here
        $.ajax({
            method: "POST",
            url: './subscriber/delete',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'subscriber_id': subscriberId
                },
            success: function (response) {
                if(response.status == 1){
                    location.reload();
                }
            }
        });
    }
}

$(document).ready(function () {
    $('#dataTableSubscribers').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [2]
        },{
            "bSearchable": false, 
            'aTargets': [2]
        }],
    });
});