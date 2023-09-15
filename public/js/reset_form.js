// reset_form.js
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#contactType");
    if (form) {
        form.addEventListener("submit", function () {
            form.reset();
        });
    }
});
