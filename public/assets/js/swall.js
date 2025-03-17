function showErrorSwalToast(icon, title, text ,callback, timer = 1000) 
{
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true,
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer && typeof callback === 'function') {
            callback();
        }
    });
}