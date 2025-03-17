function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#imageUpload").change(function () {
    readURL(this);
});

$('.remove-img').on('click', function () {
    $('#imageUpload').val('');
    $('.avatar-preview, #imagePreview').removeAttr('style');
    $('#imagePreview').css('background-image', 'url(' + imageUrl + ')');
});


$(document).ready(function() 
{
    $(document).on("submit", "#editform", function(e) {
        e.preventDefault();
        $("#edit_btn").prop("disabled", true);
        $.ajax({
            url: 'api/edit-profile',
            type: "post",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) 
            {
                showErrorSwalToast(response.status, "", response.message, function() {
                    $("#edit_btn").prop("disabled", true);
                    setTimeout(function() { location.reload(); }, 500);
                });
            },
            error: function(error) 
            {
                data = error.responseJSON;
                $("#edit_btn").prop("disabled", false);
                showErrorSwalToast(data.status, "", data.message);
            }
        });
    });
});