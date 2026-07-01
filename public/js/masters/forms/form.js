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
