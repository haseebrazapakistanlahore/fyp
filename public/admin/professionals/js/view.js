function deactivateProfessional(professional_id) {
    var result = window.confirm('Are you sure you want to deactivate this professional?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here

        $.ajax({
            method: "POST",
            url: './deactivate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'professional_id': professional_id 
                },
            success: function (response) {
                location.reload(); 
            }
        });
    }
}

function activateProfessional(professional_id) {
    var result = window.confirm('Are you sure you want to activate this professional?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here
        $.ajax({
            method: "POST",
            url: './activate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'professional_id': professional_id 
                },
            success: function (response) {
                location.reload();
            }
        });
    }
}

$('input[name="is_active"]').change(function () {
    if ($(this).is(":checked")) {
        $('input#is_active').val('0');
    } else {
        $('input#is_active').val('1');
    }
}); 

$('input[name="is_authorized"]').change(function () {
    if ($(this).is(":checked")) {
        $('input#is_authorized').val('1');
    } else {
        $('input#is_authorized').val('0');
    }
}); 

$('input[name="user_type"]').change(function () {
    if ($('input#user_type').val() == "1") {
        $('input#user_type').val('0');
        $("#professional-fields").hide();
        $("#professional-fields2").hide();
        $("#professional-fields3").hide();
        $("div.discount_fields").hide();
        $("div.discount_fields input").attr('required', false);
        $("#professional-fields #company_name").prop('required', false);
        $("#professional-fields #company_name").prop('required', false);
        
    } else {
        $('input#user_type').val('1');
        $("#professional-fields").show();
        $("#professional-fields2").show();
        $("#professional-fields3").show();
        $("div.discount_fields").show();
        $("div.discount_fields input").attr('required', true);
        $("#professional-fields #company_name").prop('required', true);
        $("#professional-fields #company_name").prop('required', true);
    }
}); 

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#user_profile_image').attr('src', e.target.result);
            $('#user_profile_image').show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#profile_image").change(function(){
    readURL(this);
});

$(document).ready(function () {
    $('#dataTableUsers').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0,6]
        },{
            "bSearchable": false, 
            'aTargets': [0,6]
        }],
    });

    $('#dataTableConsumers').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0,6]
        },{
            "bSearchable": false, 
            'aTargets': [0,6]
        }],
    });
    $('#dataTableProfessionals').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0,6]
        },{
            "bSearchable": false, 
            'aTargets': [0,6]
        }],
    });
    $('#dataTableDeleted').DataTable({
        responsive: true, 
        "order": [],
        "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0,5]
        },{
            "bSearchable": false, 
            'aTargets': [0,5]
        }],
    });
    $("div.discount_fields").hide();
});

$('select#discount_allowed').change(function () {
    if ($(this).val() == "1") {
        $("div.discount_fields").show();
        $("div.discount_fields input").attr('required', true);
    } else {
        $("div.discount_fields").hide();
        $("div.discount_fields input").attr('required', false);
    }
}); 

$('select#edit_discount_allowed').change(function () {
    if ($(this).val() == "1") {
        $("div.edit_discount_fields").show();
        $("div.edit_discount_fields input").attr('required', true);
    } else {
        $("div.edit_discount_fields").hide();
        $("div.edit_discount_fields input").attr('required', false);
        // $('input#is_authorized').val('0');
    }
}); 