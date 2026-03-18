<?php
session_start();

session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logging out...</title>

    <style>
        body {
            margin: 0;
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial;
            color: white;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 30% 40%, #00f5ff22, transparent 40%),
                radial-gradient(circle at 70% 60%, #8b5cf622, transparent 40%);
            animation: glow 6s infinite alternate;
            z-index: -1;
        }

        @keyframes glow {
            0% {
                background-position: 0% 0%, 100% 100%;
            }

            100% {
                background-position: 100% 100%, 0% 0%;
            }
        }

        .box {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            backdrop-filter: blur(12px);
            box-shadow: 0 0 20px cyan;
            animation: fade 1s ease;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 4px solid #1f2937;
            border-top: 4px solid cyan;
            border-radius: 50%;
            margin: 20px auto;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        setTimeout(() => {
            window.location = "index.html";
        }, 2000);
    </script>

</head>

<body>

    <div class="box">

        <h2>Logging out...</h2>

        <div class="loader"></div>

        <p>Session closed securely ✅</p>

    </div>

</body>

</html>