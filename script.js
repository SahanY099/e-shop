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
