window.formIndexToast = function (successMsg, errorMsg) {
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



if (typeof window.choicesSelect === 'undefined') {
    window.choicesSelect = function(wireModelName) {
        return {
            choice: null,
            init() {
                this.choice = new Choices(this.$refs.select, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false
                });
                this.$refs.select.addEventListener('change', (e) => {
                    this.$wire.set(wireModelName, e.target.value);
                });

                this.$wire.$watch(wireModelName, (value) => {
                    this.choice.setChoiceByValue(String(value));
                });
            }
        }
    };
}
