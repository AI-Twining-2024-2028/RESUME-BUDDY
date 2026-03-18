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
    <title>ATS Checker</title>

    <style>
        body {
            margin: 0;
            background: #0f172a;
            font-family: Arial;
            color: white;
            padding: 40px;
        }

        h2 { text-align: center; }

        .box {
            max-width: 700px;
            margin: auto;
            background: rgba(255,255,255,0.05);
            padding: 25px;
            border-radius: 12px;
        }

        input, textarea {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            border: none;
            background: #1f2937;
            color: white;
        }

        button {
            padding: 12px 20px;
            background: linear-gradient(45deg, #22c55e, #16a34a);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
        }

        a {
            color: cyan;
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

<h2>📊 Advanced ATS Resume Checker</h2>

<div class="box">

<form method="POST" enctype="multipart/form-data">

    <label>Upload Resume (TXT or PDF):</label>
    <input type="file" name="resumeFile" required>

    <label>Paste Job Description:</label>
    <textarea name="jd" required></textarea>

    <button type="submit">Check ATS Score</button>

</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $resumeText = "";

    if (isset($_FILES["resumeFile"])) {

        $fileType = strtolower(pathinfo($_FILES["resumeFile"]["name"], PATHINFO_EXTENSION));

        if ($fileType == "txt") {
            $resumeText = file_get_contents($_FILES["resumeFile"]["tmp_name"]);
        }

        elseif ($fileType == "pdf") {

            $content = file_get_contents($_FILES["resumeFile"]["tmp_name"]);
            $resumeText = preg_replace('/[^(\x20-\x7F)]*/','', $content);
        }

        else {
            echo "<div class='result'>Unsupported file type. Upload TXT or PDF.</div>";
            exit();
        }
    }

    $resumeText = strtolower($resumeText);
    $jd = strtolower($_POST["jd"]);

    preg_match_all("/\b[a-z]{3,}\b/", $jd, $matches);
    $words = array_unique($matches[0]);

    $matched = [];
    $missing = [];

    foreach ($words as $word) {
        if (strpos($resumeText, $word) !== false)
            $matched[] = $word;
        else
            $missing[] = $word;
    }

    $score = count($words) > 0 ? round((count($matched) / count($words)) * 100) : 0;

    echo "<div class='result'>";
    echo "<h3>ATS Score: $score%</h3>";

    if ($score >= 80)
        echo "<p style='color:lime'>🔥 Excellent Resume Optimization</p>";
    elseif ($score >= 60)
        echo "<p style='color:orange'>👍 Good but can improve</p>";
    else
        echo "<p style='color:red'>⚠ Needs Improvement</p>";

    echo "<p><b>Matched Keywords:</b> " . implode(", ", array_slice($matched,0,15)) . "</p>";
    echo "<p><b>Missing Keywords:</b> " . implode(", ", array_slice($missing,0,15)) . "</p>";

    echo "</div>";
}
?>

</div>

<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>