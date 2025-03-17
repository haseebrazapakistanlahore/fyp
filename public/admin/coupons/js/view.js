
function deleteCoupon(couponId) {
    var result = window.confirm('Are you sure you want to delete this coupon?  This action cannot be undone. Proceed?');
    if (result == false) {
        e.preventDefault();
    }else{

        $.ajax({
            method: "POST",
            url: './coupon/delete',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'coupon_id': couponId 
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
};