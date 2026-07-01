function confirmFormDelete(wire) {
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this record!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            wire.delete();
        }
    });
}

window.formShowToast = function(successMsg, errorMsg) {
    return {
        init() {
            if (!successMsg && !errorMsg) return;

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            if (successMsg) {
                Toast.fire({
                    icon: 'success',
                    title: successMsg
                });
            }

            if (errorMsg) {
                Toast.fire({
                    icon: 'error',
                    title: errorMsg
                });
            }
        }
    }
}
