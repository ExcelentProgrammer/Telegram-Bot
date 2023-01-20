<div id="alert" class="alert" data-aos="fade-down">
    <div id="alert-body" class="alert-body">
        <p id="alert-text" class="alert-text"></p>
    </div>
    <span id="alert-close" class="close-button">&times;</span>
</div>
<div id="url-alert" class="url-alert alert" data-aos="fade-down">
    <div id="url-alert-body" class="url-alert alert-body">
        <p id="url-alert-text" class="url-alert alert-text"></p>
        <br>
        <button id="url-alert-close" class="alert-button button-close"></button>
        <a type="button" id="url-alert-continue" class="alert-button button-continue"></a>
    </div>
</div>

<div id="input-alert" class="input-alert alert" data-aos="fade-down">
    <div id="input-alert-body" class="input-alert alert-body">
        <p id="input-alert-text" class="input-alert alert-text"></p>
        <br>
        <form id="alert-form">
            <input type="text" requires id="alert-form-input" class="input alert-input" name="value">
            <button type="button" id="input-alert-close" class="alert-button button-close"></button>
            <button type="submit" id="input-alert-continue" class="alert-button btn button-continue"></button>
        </form>
    </div>
</div>
<script>
    var alert = document.querySelector("#alert");
    var alertBody = document.querySelector("#alert-body")
    var alertText = document.querySelector("#alert-text")
    var closeButton = document.querySelector("#alert-close");

    var urlAlert = document.querySelector("#url-alert");
    var urlAlertBody = document.querySelector("#url-alert-body")
    var urlAlertText = document.querySelector("#url-alert-text")
    var urlCloseButton = document.querySelector("#url-alert-close");
    var urlContinueButton = document.querySelector("#url-alert-continue");

    var inputAlert = document.querySelector("#input-alert");
    var inputAlertBody = document.querySelector("#input-alert-body")
    var inputAlertText = document.querySelector("#input-alert-text")
    var inputCloseButton = document.querySelector("#input-alert-close");
    var inputContinueButton = document.querySelector("#input-alert-continue");
    var alertForm = document.querySelector("#alert-form");
    var alertFormInput = document.querySelector("#alert-form-input");


    closeButton.addEventListener("click", function () {
        alert.classList.add("d-none");
    });
    urlCloseButton.addEventListener("click", function () {
        urlAlert.classList.add("d-none");
    });
    inputCloseButton.addEventListener("click", function () {
        inputAlert.classList.add("d-none");
    });

    function showAlert(data) {
        alert.classList.remove("d-none");

        var background = data.background ?? "greenyellow";
        var message = data.message ?? "message empty";
        var textColor = data.textColor ?? "black";
        var closeButtonColor = data.closeButtonColor ?? "red";
        alert.style.display = "flex";
        alert.style.background = `${background}`;
        alertText.innerHTML = message;
        alertText.style.color = textColor;

        closeButton.style.color = closeButtonColor;
    }

    function showUrlAlert(data) {
        urlAlert.classList.remove("d-none");

        var bg = data.bg ?? "greenyellow";
        var msg = data.msg ?? "message empty";
        var tc = data.tc ?? "black";
        var closebc = data.closebc ?? "greenyellow";
        var url = data.url ?? "";
        var cbt = data.cbt ?? "Davom etish";
        var cbc = data.cbc ?? "red";
        var bbt = data.bbt ?? "Ortga";
        urlAlert.style.display = "flex";
        urlAlert.style.background = `${bg}`;
        urlAlertText.innerHTML = msg;
        urlAlertText.style.color = tc;
        urlCloseButton.innerHTML = bbt;
        urlContinueButton.innerHTML = cbt
        urlContinueButton.setAttribute("href", url);
        urlContinueButton.style.color = cbc;
        urlCloseButton.style.color = closebc;
    }

    function showInputAlert(data) {
        inputAlert.classList.remove("d-none");

        var bg = data.bg ?? "greenyellow";
        var msg = data.msg ?? "message empty";
        var tc = data.tc ?? "black";
        var action = data.action ?? "#";
        var method = data.method ?? "GET";
        var closebc = data.closebc ?? "greenyellow";
        var url = data.url ?? "";
        var value = data.value ?? null;
        var cbt = data.cbt ?? "Davom etish";
        var cbc = data.cbc ?? "red";
        var bbt = data.bbt ?? "Ortga";
        inputAlert.style.display = "flex";
        inputAlert.style.background = `${bg}`;
        inputAlertText.innerHTML = msg;
        inputAlertText.style.color = tc;
        inputCloseButton.innerHTML = bbt;
        inputContinueButton.innerHTML = cbt
        alertForm.setAttribute("action", action);
        alertForm.setAttribute("method", method);
        if (value != null) {
            alertFormInput.setAttribute("value", value);
        }
        inputContinueButton.style.color = cbc;
        inputCloseButton.style.color = closebc;
    }
</script>
