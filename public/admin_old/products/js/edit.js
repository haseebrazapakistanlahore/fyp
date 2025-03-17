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


// get subCategories for selected category
$('#edit_category_id').on('change', function () {

    var edit_category_id = $('#edit_category_id').find(":selected").val();
    var option = '';
    $('#edit_sub_category_id').prop('disabled', false);

    $.ajax({
        method: "POST",
        url: routes.getSubCategories,
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'category_id': edit_category_id
        },
        success: function (response) {
            $('#edit_sub_category_id').empty();
            $('#edit_sub_category_id').append(' <option value="" selected disabled>Select Sub Category</option>');
            
            $('#edit_sub_child_category_id').empty();
            $('#edit_sub_child_category_id').append(' <option value="" selected disabled>Select Sub Child Category</option>');
            response.subCategories.forEach(function (item, index) {
                option = "<option value='" + item.id + "'>" + item.title + "</option>"
                $('#edit_sub_category_id').append(option);
            });


            if (response.category.has_colors == "1") {


                // check if product have previous colors enable them
                if ($("input[name='old_colors[]']").length > 0) {
                    var elements = $("input[name='old_colors[]']");
                    Array.from(elements).forEach(element => {
                        $(element).attr('disabled', false);
                        $(element).parents().eq(1).removeClass('hidden');
                    });

                } else {
                    $("input#color").attr('disabled', false);
                    $("input#color").parents().eq(0).removeClass('hidden');
                }
                $("button#edit_add_color").parents().eq(0).removeClass('hidden');


            } else {

                if ($("input[name='old_colors[]']").length > 0) {
                    var elements = $("input[name='old_colors[]']");
                    Array.from(elements).forEach(element => {
                        $(element).attr('disabled', true);
                        $(element).parents().eq(1).addClass('hidden');
                    });
                }

                if ($("input[name='colors[]']").length > 0) {
                    var elements = $("input[name='colors[]']");
                    Array.from(elements).forEach(element => {
                        $(element).attr('disabled', true);
                        $(element).parents().eq(1).addClass('hidden');
                    });
                }
                if ($("input#color").length > 0){
                    $("input#color").attr('disabled', true);
                    $("input#color").parents().eq(0).addClass('hidden');
                }
                
                $("button#edit_add_color").parents().eq(0).addClass('hidden');
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
$('#edit_sub_category_id').on('change', function () {

    var edit_category_id = $('#edit_category_id').find(":selected").val();
    var edit_sub_category_id = $('#edit_sub_category_id').find(":selected").val();
    var option = '';
    
    $('#edit_sub_child_category_id').prop('disabled', false);

    $.ajax({
        method: "POST",
        url: routes.getSubChildCategories,
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'category_id': edit_category_id,
            'sub_category_id': edit_sub_category_id
        },
        success: function (response) {
            $('#edit_sub_child_category_id').empty();
            $('#edit_sub_child_category_id').append(' <option value="" selected disabled>Select Sub Child Category</option>');

            response.subChildCategories.forEach(function (item, index) {
                option = "<option value='" + item.id + "'>" + item.title + "</option>"
                $('#edit_sub_child_category_id').append(option);
            });
        }
    });
});



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

// check if no of gallery images is greater then 5 while updating product
$("#updateProductForm input#images").change(function () {
    var fileUpload = $("input[name='images[]']");
    var previousImageCount = $("#previousImageCount").val();
    var noOfImagesAllowed = 5 - parseInt(previousImageCount);

    if (parseInt(fileUpload.get(0).files.length) > noOfImagesAllowed) {
        alert("You can only upload a maximum of " + noOfImagesAllowed + " files");
        $("input[name='images[]']").val('');
        return false;
    }
})
$("#color_available").on('change', function () {

    if ($('input#color_name').length > 0) {
        $('input#color_name').parent().remove();
    }
    if ($('input#color_number').length > 0) {
        $('input#color_number').parent().remove();
    }

    if ($(this).val() == 1) {
        $(this).parent().after(
            '  <div class="form-group col-md-6 col-sm-6 col-xs-12">\
    <label>Color Name<span class="required-star">*</span></label>\
    <input type="text" id="color_name" class="form-control" name="color_name"\
    placeholder="Enter Product Color Name"  required></div>\
    <div class="form-group col-md-6 col-sm-6 col-xs-12">\
    <label>Color Number<span class="required-star">*</span></label>\
    <input type="text" id="color_number" class="form-control" name="color_number"\
    placeholder="Enter Color No" required></div>'
        );
    }
});

$("#product_type").on('change', function () {

    if ($(this).val() == 0) {
        // remove if has professional price and check if consumer price is not there then add
        if ($('input#price_professional').length > 0) {
            $('input#price_professional').parent().remove();
        }

        if ($('input#price').length == 0) {
            var html = getPriceHtml($(this).val());
            $("select#edit_sub_category_id").parent().after(html);
        }
    }

    if ($(this).val() == 1) {
        // remove if has professional price and check if consumer price is not there then add
        if ($('input#price').length > 0) {
            $('input#price').parent().remove();
        }

        if ($('input#price_professional').length == 0) {
            var html = getPriceHtml($(this).val());
            $("select#edit_sub_category_id").parent().after(html);
        }
    }
    if ($(this).val() == 2) {
        // remove if has professional price and check if consumer price is not there then add
        if ($('input#price').length > 0) {
            var html = getPriceHtml(1);
            $("select#edit_sub_category_id").parent().after(html);
            // $('input#price').parent().remove();

        } else if ($('input#price_professional').length > 0) {
            var html = getPriceHtml(0);
            $("select#edit_sub_category_id").parent().after(html);
            // $('input#price_professional').parent().remove();

        } else {
            var html = getPriceHtml($(this).val());
            $("select#edit_sub_category_id").parent().after(html);
        }

    }

    // $("select#edit_sub_category_id").parent().after(html);
    
    $('#edit_sub_category_id').prop('disabled', true);
    $('#edit_sub_category_id').empty();
    $('#edit_sub_category_id').append(' <option value="" selected disabled>Select SubCategory</option>');
    
    var category_type = $('#product_type').find(":selected").val();
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

function getPriceHtml(productType) {
    var price = '';
    if (productType == 0) {
        if ($("input[name='old_price_for_consumer']").length > 0) {
            price = $("input[name='old_price_for_consumer']").val();
        }
        return '<div class="form-group col-md-6 col-sm-6 col-xs-12">\
        <label>Consumer Price <span class="required-star">*</span></label>\
        <input type="number" id="price" class="form-control" name="price" value="' + price + '" placeholder="Unit Price For Consumers" \
         required> </div>';

    } else if (productType == 1) {
        if ($("input[name='old_price_for_professiona']").length) {
            price = $("input[name='old_price_for_professiona']").val();
        }
        return '<div class="form-group col-md-6 col-sm-6 col-xs-12"> <label>Professional Price <span class="required-star">*</span></label>\
        <input type="number" id="price_professional" class="form-control" value="' + price + '" name="price_professional" \
        placeholder="Unit Price For Professionals" required> </div>';

    } else {
        if ($("input[name='old_price_for_consumer']").length > 0) {
            consumerPrice = $("input[name='old_price_for_consumer']").val();
        }
        if ($("input[name='old_price_for_professiona']").length) {
            professionalPrice = $("input[name='old_price_for_professiona']").val();
        }
        return '<div class="form-group col-md-6 col-sm-6 col-xs-12">\
        <label>Consumer Price <span class="required-star">*</span></label>\
        <input type="number" id="price" class="form-control" name="price" value="' + consumerPrice + '" placeholder="Unit Price For Consumers" \
         required> </div>\
        <div class="form-group col-md-6 col-sm-6 col-xs-12"> <label>Professional Price <span class="required-star">*</span></label>\
        <input type="number" id="price_professional" class="form-control" name="price_professional" \
        placeholder="Unit Price For Professionals" value="' + professionalPrice + '" required> </div>';
    }

}

// delete product image while editing
function deleteProductImage(imageId) {

    if (imageId != "" && imageId != undefined) {
        $.ajax({
            method: "POST",
            url: '../removeProductImage',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'imageId': imageId
            },
            success: function (response) {
                if (response.status == 1) {
                    $("span.prdouct-image." + imageId).remove();
                    var previousImageCount = $("#previousImageCount").val();
                    $("#previousImageCount").val(parseInt(previousImageCount) - 1)
                }
            }
        });
    }
}

$("#edit_add_color").click(function () {
    event.preventDefault();
    $("#edit_add_color").parents().eq(0).before('<div class="form-group col-md-6 col-sm-6 col-xs-12"> <label>Color <span class="required-star">*</span></label>\
    <div class="d-flex"> <input type="text" class="form-control col-md-6 col-sm-6 col-xs-12 mr-2" name="color[]" placeholder="Enter Color" required>\
    <input type="file" class="form-control col-md-6 col-sm-6 col-xs-12 mr-2" name="colorImages[]" placeholder="Enter Color">\
        <button type="button" onclick="editDeleteColor(this);" class="btn btn-danger"><i class="fa 2x fa-minus"></i></button> </div> </div>');
});

function editDeleteColor(element) {
    $(element).parents().eq(1).remove();
}

function deleteOldColor(oldColor) {

    if ($("#deleted_colors").val() == "") {
        var deleted = [];
        deleted.push($(oldColor).prev().prev().data('colorid'));
        $('#deleted_colors').val(JSON.stringify(deleted));

    } else {
        deleted = JSON.parse($('#deleted_colors').val());
        deleted.push($(oldColor).prev().prev().data('colorid'));
        $('#deleted_colors').val(JSON.stringify(deleted));
    }
    $(oldColor).parents().eq(1).remove();
}

$('input[name="is_featured"]').change(function () {
    if ($(this).is(":checked")) {
        $('input#is_featured').val('1');
    } else {
        $('input#is_featured').val('0');
    }
}); 

