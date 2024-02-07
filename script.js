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

function addProduct() {
    const category = document.getElementById('category');
    const brand = document.getElementById('brand');
    const model = document.getElementById('model');
    const title = document.getElementById('title');

    let condition = 0;

    if (document.getElementById('brand-new').checked) {
        condition = 1;
    } else if (document.getElementById('used').checked) {
        condition = 2;
    }

    const color = document.getElementById('color');
    const description = document.getElementById('description');
    const qty = document.getElementById('qty');
    const cost = document.getElementById('cost');
    const deliveryWithinColombo = document.getElementById('delivery-within-colombo');
    const deliveryOutOfColombo = document.getElementById('delivery-out-of-colombo');

    const imageUploader = document.getElementById('image-uploader');

    const requestData = new FormData();

    requestData.append('category', category.value);
    requestData.append('brand', brand.value);
    requestData.append('model', model.value);
    requestData.append('title', title.value);
    requestData.append('condition', condition);
    requestData.append('color', color.value);
    requestData.append('description', description.value);
    requestData.append('qty', qty.value);
    requestData.append('cost', cost.value);
    requestData.append('deliveryWithinColombo', deliveryWithinColombo.value);
    requestData.append('deliveryOutOfColombo', deliveryOutOfColombo.value);

    for (let i = 0; i < imageUploader.files.length; i++) {
        requestData.append('image-' + i, imageUploader.files[i]);
    }

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };

    request.open("POST", "addProductProcess.php", true);
    request.send(requestData);
}

function changeProductImages() {
    let image = document.getElementById("image-uploader");

    image.onchange = function () {
        let file_count = image.files.length;

        if (file_count <= 3) {
            for (let x = 0; x < file_count; x++) {
                let file = this.files[x];
                let url = window.URL.createObjectURL(file);

                console.log("img-" + x);

                document.getElementById("img-" + x).src = url;
            }
        } else {
            alert(file_count + " files. You are proceed to upload only 3 or less than 3 files.");
        }
    };
};


function changeStatus(productId) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "deactivated" || response == "activated") {
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };

    request.open("GET", "changeStatusProcess.php?productId=" + productId, true);
    request.send();
}

function sortMyProducts(pageNo = 1) {
    const search = document.getElementById('search');
    let time = 0;

    console.log(pageNo);

    if (document.getElementById("new-to-old").checked) {
        time = 1;
    } else if (document.getElementById("old-to-new").checked) {
        time = 2;
    }

    let qty = 0;

    if (document.getElementById('high-to-low').checked) {
        qty = 1;
    } else if (document.getElementById('low-to-high').checked) {
        qty = 2;
    }

    let condition = 0;

    if (document.getElementById("brand-new").checked) {
        condition = 1;
    } else if (document.getElementById('used').checked) {
        condition = 2;
    }

    const requestData = new FormData();
    requestData.append('search', search.value);
    requestData.append('time', time);
    requestData.append('qty', qty);
    requestData.append('condition', condition);
    requestData.append('page', pageNo);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            document.getElementById('sort').innerHTML = response;
        }
    };

    request.open("POST", "sortProcess.php", true);
    request.send(requestData);
}

function clearSort() {
    window.location.reload();
}

function sendId(id) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                window.location = "updateProduct.php";
            } else {
                alert(response);
            }
        }
    };

    request.open('GET', "sendIdProcess.php?productId=" + id, true);
    request.send();
}

function updateProduct() {
    const title = document.getElementById('title');
    const qty = document.getElementById('qty');
    const deliveryWithinColombo = document.getElementById('delivery-within-colombo');
    const deliveryOutOfColombo = document.getElementById('delivery-out-of-colombo');
    const description = document.getElementById('description');
    const imageUploader = document.getElementById('image-uploader');

    const requestData = new FormData();
    requestData.append('title', title.value);
    requestData.append('qty', qty.value);
    requestData.append('deliveryWithinColombo', deliveryWithinColombo.value);
    requestData.append('deliveryOutOfColombo', deliveryOutOfColombo.value);
    requestData.append('description', description.value);

    const fileCount = imageUploader.files.length;

    for (let x = 0; x < fileCount; x++) {
        requestData.append('image-' + x, imageUploader.files[x]);
    }

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            if (response == "product-updated-successfully") {
                window.location = "myProducts.php";
            }
            else { alert(response); }
        }
    };

    request.open('POST', 'updateProductProcess.php', true);
    request.send(requestData);
}

function basicSearch(pageNo) {
    const text = document.getElementById("basic-search-text");
    const select = document.getElementById("basic-search-select");

    const requestData = new FormData();
    requestData.append("text", text.value);
    requestData.append("select", select.value);
    requestData.append("page", pageNo);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            document.getElementById("basic-search-result").innerHTML = response;

        }
    };

    request.open("POST", "basicSearchProcess.php", true);
    request.send(requestData);
}

function advancedSearch(pageNo) {
    const text = document.getElementById("text-input");
    const category = document.getElementById("category-input");
    const brand = document.getElementById("brand-input");
    const model = document.getElementById("model-input");
    const condition = document.getElementById("condition-input");
    const color = document.getElementById("color-input");
    const priceFrom = document.getElementById("price-from-input");
    const priceTo = document.getElementById("price-to-input");
    const sort = document.getElementById("sort-input");

    const requestData = new FormData();
    requestData.append("text", text.value);
    requestData.append("category", category.value);
    requestData.append("brand", brand.value);
    requestData.append("model", model.value);
    requestData.append("condition", condition.value);
    requestData.append("color", color.value);
    requestData.append("priceFrom", priceFrom.value);
    requestData.append("priceTo", priceTo.value);
    requestData.append("sort", sort.value);
    requestData.append("page", pageNo);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            document.getElementById("view-area").innerHTML = response;
        }
    };

    request.open("POST", "advancedSearchProcess.php", true);
    request.send(requestData);
}

function loadMainImage(imgId) {
    const imageSrc = document.getElementById("product-image-" + imgId).src;
    const mainImageContainer = document.getElementById("main-image-container");

    mainImageContainer.style.backgroundImage = "url(" + imageSrc + ")";
}

function checkQuantityValue(maxQty) {
    const quantityInput = document.getElementById("qty-input");
    if (quantityInput.value <= 0) {
        alert("Quantify must be 1 or more.");
        quantityInput.value = 1;
    } else if (quantityInput.value > maxQty) {
        alert("Insufficient quantify.");
        quantityInput.value = maxQty;
    }
}

function quantityIncrease(maxQty) {
    const quantityInput = document.getElementById("qty-input");

    if (quantityInput.value < maxQty) {
        const newValue = parseInt(quantityInput.value) + 1;
        quantityInput.value = newValue;
    } else {
        alert("Maximum quantity has reached.");
        quantityInput.value = maxQty;
    }
}

function quantityDecrease(maxQty) {
    const quantityInput = document.getElementById("qty-input");

    if (quantityInput.value > 1) {
        const newValue = parseInt(quantityInput.value) - 1;
        quantityInput.value = newValue;
    } else {
        alert("Minimum quantity has reached.");
        quantityInput.value = 1;
    }
}

function addToWatchlist(productId) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "added") {
                document.getElementById("heart-" + productId).classList.add("text-danger");
                document.getElementById("heart-" + productId).classList.remove("text-dark");
            } else if (response == "removed") {
                document.getElementById("heart-" + productId).classList.add("text-dark");
                document.getElementById("heart-" + productId).classList.remove("text-danger");
            } else {
                alert(response);
            }
        };
    };

    request.open('GET', "addToWatchlistProcess.php?productId=" + productId, true);
    request.send();
};

function removeFromWatchlist(watchlistId) {

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };

    request.open('GET', "removeFromWatchlistProcess.php?watchlistId=" + watchlistId, true);
    request.send();
}

function addToCart(productId) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            alert(response);
        }
    };

    request.open('GET', "addToCartProcess.php?productId=" + productId, true);
    request.send();
}

function changeCartQty(cartId) {
    const qty = document.getElementById("qty-num-" + cartId).value;

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "updated") {
                window.location.reload();
            } else {
                alert(response);
                window.location.reload();
            }
        }
    };

    request.open('GET', "cartQtyUpdateProcess.php?cartId=" + cartId + "&qty=" + qty, true);
    request.send();
}

function deleteFromCart(cartId) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "removed") {
                alert("Product removed from the cart");
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };

    request.open('GET', "deleteFromCartProcess.php?cartId=" + cartId, true);
    request.send();
}

function payNow(productId) {
    const qty = document.getElementById("qty-input").value;

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            const obj = JSON.parse(response);
            const amount = obj["amount"];
            const email = obj["email"];


            if (response == "1") {
                alert("Please login first");
                window.location = "index.php";
            } else if (response == "2") {
                alert("Please update your profile");
                window.location = "userProfile.php";
            } else {
                payhere.onCompleted = function onCompleted(orderId) {
                    alert("Payment completed. OrderID:" + orderId);

                    saveInvoice(orderId, productId, email, amount, qty);
                };

                // Payment window closed
                payhere.onDismissed = function onDismissed() {
                    console.log("Payment dismissed");
                };

                // Error occurred
                payhere.onError = function onError(error) {
                    console.log("Error:" + error);
                };

                // Put the payment variables here
                var payment = {
                    "sandbox": true,
                    "merchant_id": obj["merchant_id"],
                    "return_url": "http://localhost/e-shop/singleProductView.php?id=" + productId,
                    "cancel_url": "http://localhost/e-shop/singleProductView.php?id=" + productId,
                    "notify_url": "http://sample.com/notify",
                    "order_id": obj["order_id"],
                    "items": obj["item"],
                    "amount": amount,
                    "currency": "LKR",
                    "hash": obj["hash"],
                    "first_name": obj["fname"],
                    "last_name": obj["lname"],
                    "email": email,
                    "phone": obj["mobile"],
                    "address": obj["address"],
                    "city": obj["city"],
                    "country": "Sri Lanka",
                    "delivery_address": obj["address"],
                    "delivery_city": obj["city"],
                    "delivery_country": "Sri Lanka",
                    "custom_1": "",
                    "custom_2": ""
                };

                payhere.startPayment(payment);

            }

        }
    };

    request.open('GET', "payNowProcess.php?productId=" + productId + "&qty=" + qty, true);
    request.send();
}


function saveInvoice(orderId, productId, email, amount, qty) {
    const requestData = new FormData();

    requestData.append("orderId", orderId);
    requestData.append("productId", productId);
    requestData.append("email", email);
    requestData.append("amount", amount);
    requestData.append("qty", qty);


    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                window.location = "invoice.php?orderId=" + orderId;
            } else {
                alert(response);
            }
        }
    };

    request.open("POST", "saveInvoiceProcess.php", true);
    request.send(requestData);
}

function printInvoice() {
    const restorePage = document.body.innerHTML;
    const page = document.getElementById("page").innerHTML;

    document.body.innerHTML = page;
    window.print();
    document.body.innerHTML = restorePage;
}

let feedbackModel;

function addFeedback(productId) {
    console.log('ok');
    const modal = document.getElementById("feedback-modal-" + productId);
    feedbackModel = new bootstrap.Modal(modal);

    feedbackModel.show();
}

function saveFeedback(productId) {
    let type;

    if (document.getElementById('type-1').checked) {
        type = 1;
    } else if (document.getElementById('type-2').checked) {
        type = 2;
    } else if (document.getElementById('type-3').checked) {
        type = 3;
    }

    const feedback = document.getElementById('feed').value;

    const requestData = new FormData();

    requestData.append('productId', productId);
    requestData.append('type', type);
    requestData.append('feed', feedback);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                alert("Thank you for your feedback.");
                feedbackModel.hide();
            } else {
                alert(response);
            }
        }
    };

    request.open('POST', 'saveFeedbackProcess.php', true);
    request.send(requestData);
}

function sendMsg() {
    let receiverMail;

    const selectedReceiver = document.getElementById('select-user').value;

    if (selectedReceiver == 0) {
        receiverMail = document.getElementById('rmail').innerHTML;
    } else {
        receiverMail = selectedReceiver;
    }

    console.log(receiverMail);

    const msgText = document.getElementById('msg-text');

    const requestData = new FormData();
    requestData.append('receiverEmail', receiverMail);
    requestData.append('msgText', msgText.value);

    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                alert("Message sent");
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };

    request.open('POST', 'sendMsgProcess.php', true);
    request.send(requestData);

}

function viewMessage(email) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.status == 200 & request.readyState == 4) {
            const response = request.responseText;
            document.getElementById("chat-box").innerHTML = response;
        }
    };

    request.open("GET", "viewMsgProcess.php?email=" + email, true);
    request.send();
}

let adminVerificationModel;
function adminVerification() {
    const email = document.getElementById("email");

    const requestData = new FormData();
    requestData.append("email", email.value);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                alert("Please take a look at you email inbox for verification code.");
                const adminVerificationModelElement = document.getElementById("verification-modal");
                adminVerificationModel = new bootstrap.Modal(adminVerificationModelElement);
                adminVerificationModel.show();

            } else {
                alert(response);
            }
        }
    };

    request.open("POST", "adminVerificationProcess.php", true);
    request.send(requestData);
}

function verifyAdmin() {
    const verificationCode = document.getElementById("verification-code");

    const requestData = new FormData();
    requestData.append("verificationCode", verificationCode.value);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                adminVerificationModel.hide();
                window.location = "adminPanel.php";
            } else {
                alert(response);
            }
        }
    };

    request.open("POST", "verificationProcess.php", true);
    request.send(requestData);
}

function blockUser(email) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            alert(response);
            window.location.reload();
        }
    };

    request.open("GET", "userBlockProcess.php?email=" + email, true);
    request.send();
}

let msgModal;

function viewMsgModal(email) {
    const modal = document.getElementById("msg-modal-" + email);
    msgModal = new bootstrap.Modal(modal);
    msgModal.show();
}

function blockProduct(productId) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            alert(response);
            window.location.reload();
        }
    };

    request.open("GET", "productBlockProcess.php?productId=" + productId, true);
    request.send();
}

let productModal;

function viewProductModal(email) {
    const modal = document.getElementById("product-modal-" + email);
    productModal = new bootstrap.Modal(modal);
    productModal.show();
}

let categoryModal;

function addNewCategory() {
    const modal = document.getElementById("add-category-modal");
    categoryModal = new bootstrap.Modal(modal);
    categoryModal.show();
}

let verificationCodeModal;
let email;
let categoryName;

function verifyCategory() {
    const modal = document.getElementById("add-category-verification-modal");
    verificationCodeModal = new bootstrap.Modal(modal);

    email = document.getElementById("email").value;
    categoryName = document.getElementById("category-name").value;

    const requestData = new FormData();
    requestData.append("email", email);
    requestData.append("name", categoryName);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                categoryModal.hide();
                verificationCodeModal.show();
            } else {
                alert(response);
            }
        }
    };

    request.open("POST", "addNewCategoryProcess.php", true);
    request.send(requestData);
}

function saveCategory() {
    const verificationCode = document.getElementById("verification-code");

    const requestData = new FormData();
    requestData.append("verificationCode", verificationCode.value);
    requestData.append("email", email);
    requestData.append("categoryName", categoryName);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;

            if (response == "success") {
                verificationCodeModal.hide();
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };

    request.open("POST", "saveCategoryProcess.php", true);
    request.send(requestData);
}

function sendAdminMsg(email = "") {
    const text = document.getElementById("msg-text");

    const requestData = new FormData();

    requestData.append("text", text.value);
    requestData.append("email", email);

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
        }
    };

    request.open("POST", "sendAdminMsgProcess.php", true);
    request.send(requestData);
}

let contactAdminModal;
function contactAdmin() {
    const modal = document.getElementById('contact-admin');

    contactAdminModal = new bootstrap.Modal(modal);
    contactAdminModal.show();
}

function searchInvoice() {
    const text = document.getElementById("search-text").value;

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            document.getElementById("view-area").innerHTML = response;

        }
    };

    request.open("GET", "searchInvoiceProcess.php?id=" + text, true);
    request.send();
}

function findSellings() {
    const from = document.getElementById("from").value;
    const to = document.getElementById("to").value;

    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
            const response = request.responseText;
            document.getElementById("view-area").innerHTML = response;
        }
    };

    request.open("GET", "findSellingsProcess.php?from=" + from + "&to=" + to, true);
    request.send();
}

function changeInvoiceStatus(invoiceId) {
    const request = new XMLHttpRequest();

    request.onreadystatechange = () => {
        if (request.status == 200 & request.readyState == 4) {
            const response = request.responseText;
            if (response == 'success') {
                window.location.reload();
            } else {
                alert(response);
            }
        }
    };

    request.open("GET", "changeInvoiceStatusProcess.php?invoiceId=" + invoiceId, true);
    request.send();
}
