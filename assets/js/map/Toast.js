class Toast {
    constructor() {
        const toastTrigger = document.getElementById('liveToastBtn');
        const toastLiveExample = document.getElementById('liveToast');
        this.toast = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
    }

    show = (title, caption) => {
        $("#toastTitle").html(title);
        $("#toastBody").html(caption);
        this.toast.show();
    }
}

export { Toast }