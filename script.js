document.addEventListener("DOMContentLoaded", function () {

    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");

    const loginUser = document.getElementById("loginUser");
    const loginPass = document.getElementById("loginPass");

    const regUser = document.getElementById("regUser");
    const regEmail = document.getElementById("regEmail");
    const regPass = document.getElementById("regPass");

    const msg = document.getElementById("msg");
    const title = document.getElementById("title");
    const toggleText = document.getElementById("toggleText");
    const toggleBtn = document.querySelector(".switch button");

    let isLogin = true;

    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const username = loginUser ? loginUser.value.trim() : "";
            const password = loginPass ? loginPass.value.trim() : "";

            let data =
                "username=" + encodeURIComponent(username) +
                "&password=" + encodeURIComponent(password);

            fetch("login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: data
            })
                .then(res => res.text())
                .then(result => {
                    result = result.trim();

                    if (result === "success") {
                        if (msg) { msg.style.color = "#00ffae"; msg.innerText = "Login Successful!"; }
                        setTimeout(() => { window.location = "dashboard.php"; }, 800);
                    } else {
                        if (msg) { msg.style.color = "#ff4d4d"; msg.innerText = "Invalid login!"; }
                    }
                })
                .catch(() => { if (msg) { msg.style.color = "#ff4d4d"; msg.innerText = "Server error!"; } });
        });
    }

    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const username = regUser ? regUser.value.trim() : "";
            const email = regEmail ? regEmail.value.trim() : "";
            const password = regPass ? regPass.value.trim() : "";

            let data =
                "username=" + encodeURIComponent(username) +
                "&email=" + encodeURIComponent(email) +
                "&password=" + encodeURIComponent(password);

            fetch("register.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: data
            })
                .then(res => res.text())
                .then(result => {
                    result = result.trim();

                    if (result === "success") {
                        if (msg) { msg.style.color = "#00ffae"; msg.innerText = "Registration Successful!"; }
                    }
                    else if (result === "exists") {
                        if (msg) { msg.style.color = "#ffae00"; msg.innerText = "User already registered!"; }
                    }
                    else {
                        if (msg) { msg.style.color = "#ff4d4d"; msg.innerText = "Registration failed!"; }
                    }
                })
                .catch(() => { if (msg) { msg.style.color = "#ff4d4d"; msg.innerText = "Server error!"; } });
        });
    }

    function toggleForm() {
        if (msg) msg.innerText = "";

        if (isLogin) {
            if (loginForm) loginForm.style.display = "none";
            if (registerForm) registerForm.style.display = "block";

            if (title) title.innerText = "Register";
            if (toggleText) toggleText.innerText = "Already have an account?";
            if (toggleBtn) toggleBtn.innerText = "Login";
        } else {
            if (registerForm) registerForm.style.display = "none";
            if (loginForm) loginForm.style.display = "block";

            if (title) title.innerText = "Login";
            if (toggleText) toggleText.innerText = "Don't have an account?";
            if (toggleBtn) toggleBtn.innerText = "Register";
        }

        isLogin = !isLogin;
    }
    window.toggleForm = toggleForm;

    document.addEventListener("click", function (e) {
        let layer = document.getElementById("touch-effects");
        if (!layer) return;

        let circle = document.createElement("div");
        circle.className = "touch-circle";

        circle.style.left = e.clientX + "px";
        circle.style.top = e.clientY + "px";

        layer.appendChild(circle);
        setTimeout(() => circle.remove(), 800);
    });

});

