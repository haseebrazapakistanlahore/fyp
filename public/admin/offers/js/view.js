
function deleteOffer(offerId) {
    var result = window.confirm('Are you sure you want to delete this offer?  This action cannot be undone. Proceed?');
    if (result == false) {
        e.preventDefault();
    }else{

        $.ajax({
            method: "POST",
            url: './offer/deactivate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'offer_id': offerId 
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
};

$(document).ready(function () {
    $('#dataTableOffers').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [8]
        },
         {
            "bSearchable": false,
            "aTargets": [8]
        }]

    });
});