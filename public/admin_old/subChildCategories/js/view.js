
function deactivateSubChildCategory(sub_category_id) {
    var result = window.confirm('Are you sure you want to deactivate this Sub Child Category?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here
        $.ajax({
            method: "POST",
            url: './sub-child-category/deactivate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'sub_child_category_id': sub_child_category_id
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
    $('#dataTableSubChildCategories').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [6]
        }, {
            "bSearchable": false,
            'aTargets': [6]
        }],
    });
});


// get subCategories for selected category
$('#category_type').on('change', function () {

    var category_type = $('#category_type').find(":selected").val();
    var option = '';
    $('#category_id').prop('disabled', false);

    $.ajax({
        method: "POST",
        url: routes.getCategories,
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'category_type': category_type
        },
        success: function (response) {
            $('#category_id').empty();
            $('#category_id').append(' <option value="" selected disabled>Select Category</option>');

            response.categories.forEach(function (item, index) {
                option = "<option value='" + item.id + "'>" + item.title + "</option>"
                $('#category_id').append(option);
            });
        }
    });
});

// get subCategories for selected category
$('#category_id').on('change', function () {

    
    
    var category_id = $('#category_id').find(":selected").val();
    var option = '';
    $('#sub_category_id').prop('disabled', false);
    
    $.ajax({
        method: "POST",
        url: routes.getSubCategories,
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'category_id': category_id
        },
        success: function (response) {
            $('#sub_category_id').empty();
            $('#sub_category_id').append(' <option value="" selected disabled>Select Child Category</option>');

            response.subCategories.forEach(function (item, index) {
                option = "<option value='" + item.id + "'>" + item.title + "</option>"
                $('#sub_category_id').append(option);
            });
            
        }
    });
});
