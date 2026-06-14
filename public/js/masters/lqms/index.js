window.lqmIndexToast = function (successMsg, errorMsg) {
    return {
        init() {
            if (!successMsg && !errorMsg) return;

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            if (successMsg) {
                Toast.fire({
                    icon: "success",
                    title: successMsg,
                });
            }

            if (errorMsg) {
                Toast.fire({
                    icon: "error",
                    title: errorMsg,
                });
            }
        },
    };
};
