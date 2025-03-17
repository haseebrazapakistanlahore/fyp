
function deactivateCategory(category_id) {
    var result = window.confirm('Are you sure you want to deactivate this Category?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here
        $.ajax({
            method: "POST",
            url: './category/deactivate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'category_id': category_id
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
}

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

$('input[name="has_colors"]').change(function () {
    if ($(this).is(":checked")) {
        $('input#has_colors').val('1');
    } else {
        $('input#has_colors').val('0');
    }
}); 

$('input[name="has_sizes"]').change(function () {
    if ($(this).is(":checked")) {
        $('input#has_sizes').val('1');
    } else {
        $('input#has_sizes').val('0');
    }
}); 

$('input[name="has_color_no"]').change(function () {
    if ($(this).is(":checked")) {
        $('input#has_color_no').val('1');
    } else {
        $('input#has_color_no').val('0');
    }
}); 

$(document).ready(function () {
    $('#dataTableCategories').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [3,8]
        }, {
            "bSearchable": false,
            'aTargets': [3,8]
        }],
    });
});