function deactivateUser(userId) {
    var result = window.confirm('Are you sure you want to deactivate this user?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here

        $.ajax({
            method: "POST",
            url: './deactivate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'user_id': userId 
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
}

function activateUser(userId) {
    var result = window.confirm('Are you sure you want to activate this user?');
    if (result == false) {
        event.preventDefault();
    } else {

        // ajax call here
        $.ajax({
            method: "POST",
            url: './activate',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'user_id': userId 
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
    $('#dataTableAdmins').DataTable({
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

  

});