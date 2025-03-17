$(document).ready(function() 
{
    $(document).on("submit", "#password_reset", function(e) {
        e.preventDefault();
        $("#login_btn").prop("disabled", true);
        $.ajax({
            url: '../api/auth/reset-password',
            type: "post",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response)
            {
                console.log(response);
                showErrorSwalToast(response.status, "", response.message, function() {
                    //setTimeout(function() { location.reload(); }, 1500);
                });
            },
            error: function(error) 
            {
                data = error.responseJSON;
                $("#login_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });
});