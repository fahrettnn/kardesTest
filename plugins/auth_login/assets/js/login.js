$(document).ready(function() 
{
    $(document).on("submit", "#login_form", function(e) {
        e.preventDefault();
        $("#login_btn").prop("disabled", true);
        $.ajax({
            url: '../api/auth/login',
            type: "post",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) 
            {
                showErrorSwalToast(response.status, "", response.message, function() {
                    setTimeout(function() { location.reload(); }, 500);
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