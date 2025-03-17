$(document).on("submit", "#edit_general_settings", function(e) {
    e.preventDefault();
    $("#edit_btn").prop("disabled", true);
    var formData = new FormData(this);
    $.ajax({
        url: '../api/general-settings',
        type: "PUT",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(response) {
            showErrorSwalToast(response.status, "", response.message, function() {
                $("#edit_btn").prop("disabled", false);
            });
        },
        error: function(error) {
            data = error.responseJSON;
            $("#edit_btn").prop("disabled", false);
            showErrorSwalToast(data.status, "", data.message);
        }
    });
});