const showPassword = (btn, password) => {
    const type = password.attr('type') === 'password' ? 'text' : 'password';
    password.attr('type', type);
    btn.find(".eyeicon").toggleClass("fa-eye fa-eye-slash");
}


const checkPasswordMatch = () => {
    function updateClass(element, addClass, removeClass) {
        element.addClass(addClass).removeClass(removeClass);
    }
    const textValidPassword = $("#text-validate-password");
    const checkPasswordMatch = () => {
        const password = $("#password");
        const confirmPassword = $("#c_password");

        if (password.val() === confirmPassword.val()) {
            updateClass(password, "border-success", "border-danger");
            updateClass(confirmPassword, "border-success", "border-danger");
            textValidPassword.css("opacity", 0);
        } else {
            textValidPassword.css("opacity", 1);
            updateClass(password, "border-danger", "border-success");
            updateClass(confirmPassword, "border-danger", "border-success");
        }
    }
    const handleInputChange = () => {
        const password = $("#password");
        const confirmPassword = $("#c_password");
        if (confirmPassword.val() !== "") {
            checkPasswordMatch();
        } else {
            confirmPassword.removeClass("border-danger border-success");
        }
        if ((password.val() === "") || (confirmPassword.val() === "")) {
            password.removeClass("border-danger border-success");
            confirmPassword.removeClass("border-danger border-success");
            textValidPassword.css("opacity", 0);
        }
    }
    $("#c_password").on("input", handleInputChange);
    $("#password").on("input", handleInputChange);
}

function formatNumberWithComma(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}