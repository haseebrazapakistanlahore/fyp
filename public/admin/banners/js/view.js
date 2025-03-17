function deleteBanner(bannerId) {
    var result = window.confirm('Are you sure you want to delete this banner?  This action cannot be undone. Proceed?');
    if (result == false) {
        event.preventDefault();
    } else {
        $.ajax({
            method: "POST",
            url: './banner/delete',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'banner_id': bannerId 
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
};

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image').attr('src', e.target.result);
            $('#image').removeClass("hidden");
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#image_url").change(function () {
    readURL(this);
});

$(document).ready(function () {
    $('#dataTableBanners').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [2,4]
        },{
            "bSearchable": false, 
            'aTargets': [2,4]
        }],
    });
}); 