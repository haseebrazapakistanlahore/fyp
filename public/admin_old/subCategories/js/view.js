
function deactivateSubCategory(sub_category_id) {
    var result = window.confirm('Are you sure you want to deactivate this SubCategory?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here
        $.ajax({
            method: "POST",
            url: './sub-category/deactivate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'sub_category_id': sub_category_id
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


$(document).ready(function () {
    $('#dataTableSubCategories').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [ 4, 6]
        }, {
            "bSearchable": false,
            'aTargets': [ 4, 6]
        }],
    });
});


// get subCategories for selected category
$('#edit_category_type').on('change', function () {

    var category_type = $('#edit_category_type').find(":selected").val();
    var option = '';
    $('#edit_category_id').prop('disabled', false);

    $.ajax({
        method: "POST",
        url: routes.getCategories,
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'category_type': category_type
        },
        success: function (response) {
            $('#edit_category_id').empty();
            $('#edit_category_id').append(' <option value="" selected disabled>Select Category</option>');

            response.categories.forEach(function (item, index) {
                option = "<option value='" + item.id + "'>" + item.title + "</option>"
                $('#edit_category_id').append(option);
            });
        }
    });
});
