<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>AI Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, #00f5ff22, transparent 40%),
                radial-gradient(circle at 80% 70%, #8b5cf622, transparent 40%);
            animation: glow 8s infinite alternate;
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

        .dashboard {
            width: 420px;
            padding: 35px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border-radius: 14px;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.3);
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 26px;
        }

        .username {
            color: #00f5ff;
            margin: 10px 0 20px;
            font-weight: bold;
        }

        .btn {
            display: block;
            margin: 12px auto;
            padding: 12px;
            width: 80%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            transition: 0.3s;
        }

        .chat-btn {
            background: linear-gradient(45deg, #00f5ff, #8b5cf6);
            color: black;
        }

        .ats-btn {
            background: linear-gradient(45deg, #22c55e, #16a34a);
            color: white;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px cyan;
        }

        .footer {
            margin-top: 15px;
            font-size: 13px;
            opacity: 0.7;
        }
    </style>
</head>

<body>

    <div class="dashboard">

        <h1>🤖 AI Control Panel</h1>

        <div class="username">
            Welcome, <?php echo $_SESSION['user']; ?>
        </div>

        <button class="btn chat-btn"
            onclick="window.location='chatbot.php'">
            Open Resume AI Chatbot
        </button>

        <button class="btn ats-btn"
            onclick="window.location='ats.php'">
            Check ATS Score
        </button>

        <button class="btn logout-btn"
            onclick="window.location='logout.php'">
            Logout
        </button>

        <div class="footer">
            AI Resume Builder Dashboard
        </div>

    </div>

</body>

</html>