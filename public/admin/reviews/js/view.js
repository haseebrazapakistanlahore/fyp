function deleteReview(reviewId) {
    var result = window.confirm('Are you sure you want to delete this review?');
    if (result == false) {
        e.preventDefault();
    }else{
        $.ajax({
            method: "POST",
            url: './review/delete',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'review_id': reviewId 
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
}

function deleteReviewSecond(reviewId) {
    var result = window.confirm('Are you sure you want to delete this review?');
    if (result == false) {
        e.preventDefault();
    }else{
        $.ajax({
            method: "POST",
            url: '../../review/delete',
            data: { 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                'review_id': reviewId 
                },
            success: function (response) {
                location.reload();
                
            }
        });
    }
}