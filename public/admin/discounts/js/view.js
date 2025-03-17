function deleteDiscount(discountId) {
    var result = window.confirm('Are you sure you want to delete this discount slab?  This action cannot be undone. Proceed?');
    if (result == false) {
        e.preventDefault();
    }else{

        $.ajax({
            method: "POST",
            url: './discount/delete',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'discount_id': discountId 
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
};

$(document).ready(function () {
    $('#dataTableDiscounts').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [3,8]
        }, {
            "bSearchable": false,
            "aTargets": [3,8]
        }]

    });
});


$("#image_url").change(function () {
    readURL(this);
});

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