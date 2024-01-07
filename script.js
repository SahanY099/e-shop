function changeView() {
    const signUpView = document.getElementById('signup-box');
    const signInView = document.getElementById('sign-in-box');

    signUpView.classList.toggle('d-none');
    signInView.classList.toggle('d-none');
}

function signup() {
    const fname = document.getElementById("fname");
    const lname = document.getElementById("lname");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const mobile = document.getElementById("mobile");
    const gender = document.getElementById("gender");

    const requestData = new FormData();
    requestData.append("fname", fname.value);
    requestData.append("lname", lname.value);
    requestData.append("email", email.value);
    requestData.append("password", password.value);
    requestData.append("mobile", mobile.value);
    requestData.append("gender", gender.value);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            const msgContainer = document.getElementById('msg-div-signup');
            msgContainer.classList.remove('d-none');

            const msg = document.getElementById('msg-signup');

            if (response === 'success') {
                msg.innerHTML = 'Sign up successful. Login to continue.';
                msg.classList.remove('alert-danger');
                msg.classList.add('alert-success');
            } else {
                msg.innerHTML = response;
            }
        }
    };

    request.open("POST", "signupProcess.php", true);
    request.send(requestData);
}

function signIn() {
    const email = document.getElementById("sign-in-email");
    const password = document.getElementById("sign-in-password");
    const rememberMe = document.getElementById("remember-me");

    const requestData = new FormData();
    requestData.append("email", email.value);
    requestData.append("password", password.value);
    requestData.append("rememberMe", rememberMe.checked);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            const msgContainer = document.getElementById('msg-div-sign-in');
            msgContainer.classList.remove('d-none');

            const msg = document.getElementById('msg-sign-in');

            if (response === 'success') {
                msg.innerHTML = 'Sign in successful.';
                msg.classList.remove('alert-danger');
                msg.classList.add('alert-success');

                setTimeout(() => {
                    window.location = "home.php";
                }, 750);
            } else {
                msg.innerHTML = response;
            }
        }
    };

    request.open("POST", "signInProcess.php", true);
    request.send(requestData);
}

let forgotPasswordModal;

function forgotPassword() {

    const modal = document.getElementById('forgot-password-modal');
    forgotPasswordModal = new bootstrap.Modal(modal);

    const email = document.getElementById('sign-in-email');

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response === "success") {
                alert("Verification code has sent to your email");
                forgotPasswordModal.show();
            }
            else {
                console.log(response == "success");
                document.getElementById('msg-div-sign-in').classList.remove('d-none');
                document.getElementById('msg-sign-in').innerHTML = response;
            }
        }
    };

    request.open("GET", "forgotPasswordProcess.php?email=" + email.value, true);
    request.send();

}

function showPassword(pwdFieldId, pwdFieldTogglerId) {
    const passwordField = document.getElementById(pwdFieldId);
    const toggleBtn = document.getElementById(pwdFieldTogglerId);

    if (passwordField.type == "password") {
        passwordField.type = "text";
        toggleBtn.innerHTML = "Hide";
    } else {
        passwordField.type = "password";
        toggleBtn.innerHTML = "Show";
    }
}

function resetPassword() {
    const email = document.getElementById('sign-in-email').value;
    const newPassword = document.getElementById('new-password').value;
    const retypeNewPassword = document.getElementById('retype-new-password').value;
    const verificationCode = document.getElementById('verification-code').value;

    const requestData = new FormData();
    requestData.append('email', email);
    requestData.append('newPassword', newPassword);
    requestData.append('retypeNewPassword', retypeNewPassword);
    requestData.append('verificationCode', verificationCode);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == 'success') {
                alert("Password has been updated successfully.");
                forgotPasswordModal.hide();
            } else {
                alert(response);
            }
        }
    };

    request.open('POST', 'resetPasswordProcess.php', true);
    request.send(requestData);
}

function signOut() {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                window.location.reload();
            }
        }
    };

    request.open("GET", "signOutProcess.php", true);
    request.send();
}


function changeProfileImage() {
    const profileImageSelector = document.getElementById('profile-image-selector');

    profileImageSelector.onchange = function () {
        const file = this.files[0];
        const url = window.URL.createObjectURL(file);

        document.getElementById('profile-image').src = url;
    };

}

function updateProfile() {
    const fname = document.getElementById('fname');
    const lname = document.getElementById('lname');
    const mobile = document.getElementById('mobile');
    const line1 = document.getElementById('line1');
    const line2 = document.getElementById('line2');
    const province = document.getElementById('province');
    const district = document.getElementById('district');
    const city = document.getElementById('city');
    const postalCode = document.getElementById('postal-code');
    const profileImageSelector = document.getElementById('profile-image-selector');

    const requestData = new FormData();

    requestData.append('fname', fname.value);
    requestData.append('lname', lname.value);
    requestData.append('mobile', mobile.value);
    requestData.append('line1', line1.value);
    requestData.append('line2', line2.value);
    requestData.append('province', province.value);
    requestData.append('district', district.value);
    requestData.append('city', city.value);
    requestData.append('postalCode', postalCode.value);
    requestData.append('profileImage', profileImageSelector.files[0]);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "updated" || response == "saved") {
                window.location.reload();
            } else if (response == "no-image-selected") {
                alert("You haven't selected any profile image");
            } else {
                alert(response);
            }
        }
    };

    request.open("POST", "updateProfileProcess.php", true);
    request.send(requestData);
}
