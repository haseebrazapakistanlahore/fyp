// show selected thumbnail image
$("#thumbnail").change(function () {
    readURL(this);
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image').attr('src', e.target.result);
            $('#image').show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}


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
            $('#sub_category_id').append(' <option value="" selected disabled>Select SubCategory</option>');

            response.subCategories.forEach(function (item, index) {
                option = "<option value='" + item.id + "'>" + item.title + "</option>"
                $('#sub_category_id').append(option);
            });

            if (response.category.has_colors == "1") {
                $("input#color").attr('disabled', false);
                $("input#color").parents().eq(0).removeClass('hidden');
                $("input#colorupload").attr('disabled', false);
                $("input#colorupload").parents().eq(0).removeClass('hidden');
                $("button#add_color").parents().eq(0).removeClass('hidden');
            } else if ($("input[name='color[]']").length > 0) {
                $("button#add_color").parents().eq(0).addClass('hidden');
                $("input#color").attr('disabled', true);
                $("input#color").parents().eq(0).addClass('hidden');
                $("input#colorupload").attr('disabled', true);
                $("input#colorupload").parents().eq(0).addClass('hidden');
                var elements = $("input[name='color[]']").slice(1);
                Array.from(elements).forEach(element => {
                    $(element).parents().eq(1).remove();
                });
            }

            if (response.category.has_sizes == "1") {
                $("input#size").attr('disabled', false);
                $("input#size").parents().eq(0).removeClass('hidden');

            } else if ($("input#size").length > 0) {
                $("input#size").attr('disabled', true);
                $("input#size").parents().eq(0).addClass('hidden');

            }

            if (response.category.has_color_no == "1") {
                $("input#color_no").attr('disabled', false);
                $("input#color_no").parents().eq(0).removeClass('hidden');

            } else if ($("input#color_no").length > 0) {
                $("input#color_no").attr('disabled', true);
                $("input#color_no").parents().eq(0).addClass('hidden');

            }

        }
    });
});

// get subChildCategories for selected subCategory
$('#sub_category_id').on('change', function () {

    var category_id = $('#category_id').find(":selected").val();
    var sub_category_id = $('#sub_category_id').find(":selected").val();
    var option = '';
    $('#sub_child_category_id').prop('disabled', false);

    $.ajax({
        method: "POST",
        url: routes.getSubChildCategories,
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'category_id': category_id,
            'sub_category_id': sub_category_id
        },
        success: function (response) {
            $('#sub_child_category_id').empty();
            $('#sub_child_category_id').append(' <option value="" selected disabled>Select SubCategory</option>');

            response.subChildCategories.forEach(function (item, index) {
                option = "<option value='" + item.id + "'>" + item.title + "</option>"
                $('#sub_child_category_id').append(option);
            });
        }
    });
});


// get subCategories for selected category
// $('#edit_category_id').on('change', function () {

//     var edit_category_id = $('#edit_category_id').find(":selected").val();
//     var option = '';
//     $('#edit_sub_category_id').prop('disabled', false);

//     $.ajax({
//         method: "POST",
//         url: '../../getSubCategories',
//         data: {
//             _token: $('meta[name="csrf-token"]').attr('content'),
//             'category_id': edit_category_id
//         },
//         success: function (response) {
//             $('#edit_sub_category_id').empty();
//             $('#edit_sub_category_id').append(' <option value="" selected disabled>Select SubCategory</option>');
//             response.subCategories.forEach(function (item, index) {
//                 option = "<option value='" + item.id + "'>" + item.title + "</option>"
//                 $('#edit_sub_category_id').append(option);
//             });
//         }
//     });
// });

// check if no of gallery images is greater then 5 while adding product
$("#images").on("change", function () {
    event.preventDefault();
    var $fileUpload = $("input[name='images[]']");

    if (parseInt($fileUpload.get(0).files.length) > 5) {
        $("#images").val(null);
        alert("You can only upload a maximum of 5 files");
        return false;
    }
});

function deactivateProduct(product_id) {
    var result = window.confirm('Are you sure you want to delete this product?  This action cannot be undone. Proceed?');
    if (result == false) {
        event.preventDefault();

    } else {
        // ajax call here
        $.ajax({
            method: "POST",
            url: './product/deactivate',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'product_id': product_id
            },
            success: function (response) {
                location.reload();
            }
        });
    }
}

$("#product_type").on('change', function () {

    if ($('input#price_professional').length > 0) {
        $('input#price_professional').parent().remove();
    }
    if ($('input#price').length > 0) {
        $('input#price').parent().remove();
    }
    var html = getPriceHtml($(this).val());
    $("select#sub_child_category_id").parent().after(html);
    
    $('#sub_category_id').prop('disabled', true);
    $('#sub_category_id').empty();
    $('#sub_category_id').append(' <option value="" selected disabled>Select SubCategory</option>');
    
    var category_type = $('#product_type').find(":selected").val();
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

$("#offer_available").on('change', function () {

    if ($('input#offer_price').length > 0) {
        $('input#offer_price').parent().remove();
    }

    if ($(this).val() == 1) {
        $(this).parent().after(' <div class="form-group col-md-6 col-sm-6 col-xs-12">\
        <label>Offer Price  <span class="required-star">*</span></label>\
        <input type="number" id="offer_price" class="form-control" name="offer_price"\
        placeholder="Enter discounted price" value="{{ old("offer_price") }}" required>\
        </div>');
    }
});

$("#color_available").on('change', function () {

    if ($('input#color_name').length > 0) {
        $('input#color_name').parent().remove();
    }

    if ($('input#color_number').length > 0) {
        $('input#color_number').parent().remove();
    }

    if ($(this).val() == 1) {
        $(this).parent().after('  <div class="form-group col-md-6 col-sm-6 col-xs-12">\
            <label>Color Name<span class="required-star">*</span></label>\
            <input type="text" id="color_name" class="form-control" name="color_name"\
            placeholder="Enter Product Color Name" required></div>\
            <div class="form-group col-md-6 col-sm-6 col-xs-12">\
            <label>Color Number<span class="required-star">*</span></label>\
            <input type="text" id="color_number" class="form-control" name="color_number"\
            placeholder="Enter Color No" required></div>');
    }
});


function getPriceHtml(productType) {

    if (productType == 0) {
        return '<div class="form-group col-md-6 col-sm-6 col-xs-12">\
        <label>Consumer Price <span class="required-star">*</span></label>\
        <input type="number" id="price" class="form-control" name="price" placeholder="Unit Price For Consumers" \
         required> </div>';

    } else if (productType == 1) {
        return '<div class="form-group col-md-6 col-sm-6 col-xs-12"> <label>Professional Price <span class="required-star">*</span></label>\
        <input type="number" id="price_professional" class="form-control" name="price_professional" \
        placeholder="Unit Price For Professionals" required> </div>';

    } else {
        return '<div class="form-group col-md-6 col-sm-6 col-xs-12">\
        <label>Consumer Price <span class="required-star">*</span></label>\
        <input type="number" id="price" class="form-control" name="price" placeholder="Unit Price For Consumers" \
         required> </div>\
        <div class="form-group col-md-6 col-sm-6 col-xs-12"> <label>Professional Price <span class="required-star">*</span></label>\
        <input type="number" id="price_professional" class="form-control" name="price_professional" \
        placeholder="Unit Price For Professionals" required> </div>';
    }
}

$(document).ready(function () {
    $('#dataTableProduct').DataTable({
        responsive: true,
        "order": [],
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [7,9]
        }, {
            "bSearchable": false,
            "aTargets": [7,9]
        }]
    });
});

$("#add_color").click(function () {
    event.preventDefault();
    $("#add_color").parents().eq(0).before('<div class="form-group col-md-6 col-sm-6 col-xs-12"> <label>Color </label>\
    <div class="d-flex"> <input type="text" class="form-control col-md-6 col-sm-6 col-xs-12 mr-2 border-radius" name="color[]" placeholder="Enter Color"> \
    <input type="file" class="form-control col-md-6 col-sm-6 col-xs-12 mr-2" name="colorImages[]" placeholder="Enter Color">\
        <button type="button" onclick="deleteColor(this);" class="btn btn-danger"><i class="fa 2x fa-close"></i></button> </div> </div>');
});

function deleteColor(element) {
    $(element).parents().eq(1).remove();
}

$('input[name="is_featured"]').change(function () {
    if ($(this).is(":checked")) {
        $('input#is_featured').val('1');
    } else {
        $('input#is_featured').val('0');
    }
});
