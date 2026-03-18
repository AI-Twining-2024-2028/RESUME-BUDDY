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
    <title>Resume Buddy</title>

    <style>
        body {
            margin: 0;
            font-family: Segoe UI, Arial;
            background: linear-gradient(135deg, #020617, #0f172a);
            height: 100vh;
            display: flex;
            flex-direction: column;
            color: white;
        }

        .header {
            padding: 18px;
            background: linear-gradient(90deg, #00f5ff, #8b5cf6);
            color: black;
            font-weight: bold;
            text-align: center;
            font-size: 18px;
        }

        .messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .msg {
            max-width: 70%;
            padding: 14px 18px;
            border-radius: 18px;
            font-size: 15px;
            line-height: 1.4;
            word-break: break-word;
            animation: pop .25s ease;
            display: inline-block;
        }

        @keyframes pop {
            from {
                opacity: 0;
                transform: scale(.9);
            }

            to {
                opacity: 1;
            }
        }

        .bot {
            background: #1f2937;
            align-self: flex-start;
        }

        .user {
            background: linear-gradient(135deg, #22d3ee, #06b6d4);
            color: black;
            align-self: flex-end;
        }

        .input {
            display: flex;
            padding: 14px;
            background: #020617;
            border-top: 1px solid #1f2937;
        }

        textarea {
            flex: 1;
            resize: none;
            border: none;
            padding: 14px;
            border-radius: 14px;
            background: #0f172a;
            color: white;
            outline: none;
            font-size: 15px;
            max-height: 120px;
        }

        button {
            margin-left: 10px;
            padding: 14px 20px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #00f5ff, #8b5cf6);
            color: black;
            font-weight: bold;
            cursor: pointer;
        }

        .action-btn {
            margin: auto;
            padding: 10px 18px;
            background: #22c55e;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">🤖 Resume Buddy</div>
    <div class="messages" id="chat"></div>

    <div class="input">
        <textarea id="input" placeholder="Type your answer…"></textarea>
        <button onclick="send()">Send</button>
    </div>

    <script>
        const chat = document.getElementById("chat");
        const input = document.getElementById("input");

        const fields = ["name", "phone", "email", "linkedin", "github",
            "education", "experience", "projects", "skills"
        ];

        const questions = [
            "What is your full name?",
            "Your phone number?",
            "Your email address?",
            "LinkedIn profile link?",
            "GitHub profile link?",
            "Education details?",
            "Work experience?",
            "Projects completed?",
            "Technical skills?"
        ];

        let resume = {};
        let step = 0;

        let suggestionMode = false;
        let askingRole = false;
        let askingCompany = false;
        let checkingATS = false;
        let targetRole = "";
        let targetCompany = "";

        function msg(text, type) {
            let d = document.createElement("div");
            d.className = "msg " + type;
            d.innerText = text;
            chat.appendChild(d);
            chat.scrollTop = chat.scrollHeight;
        }

        function bot(t) {
            setTimeout(() => msg(t, "bot"), 300);
        }

        function send() {

            let text = input.value.trim();
            if (!text) return;

            msg(text, "user");
            input.value = "";

            if (checkingATS) {
                calculateATS(text);
                checkingATS = false;
                return;
            }

            if (step < questions.length) {
                resume[fields[step]] = text;
                step++;
                if (step < questions.length) {
                    bot(questions[step]);
                } else {
                    bot("✅ Resume collected!");
                    bot("Do you want suggestions? YES/NO");
                    suggestionMode = true;
                }
                return;
            }

            if (suggestionMode) {
                if (text.toLowerCase() == "yes") {
                    bot("What role are you targeting?");
                    suggestionMode = false;
                    askingRole = true;
                    return;
                }
                if (text.toLowerCase() == "no") {
                    createButtons();
                    suggestionMode = false;
                    return;
                }
            }

            if (askingRole) {
                targetRole = text;
                bot("What company type are you targeting?");
                askingRole = false;
                askingCompany = true;
                return;
            }

            if (askingCompany) {
                targetCompany = text;
                giveSuggestions();
                askingCompany = false;
                return;
            }
        }

        input.addEventListener("keydown", e => {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                send();
            }
        });

        function createButtons() {

            let gen = document.createElement("button");
            gen.className = "action-btn";
            gen.innerText = "Generate Resume";
            gen.onclick = generateResume;
            chat.appendChild(gen);

            let ats = document.createElement("button");
            ats.className = "action-btn";
            ats.innerText = "Check ATS Score";
            ats.onclick = () => {
                bot("Paste Job Description to check ATS score.");
                checkingATS = true;
            };
            chat.appendChild(ats);
        }

        function giveSuggestions() {
            bot("📌 ATS Suggestions for " + targetRole);
            bot("✔ Use role specific keywords.");
            bot("✔ Quantify achievements.");
            bot("✔ Match resume with " + targetCompany + " job description.");
            bot("✔ Use action verbs.");
            createButtons();
        }

        function calculateATS(jd) {

            let resumeText = Object.values(resume).join(" ").toLowerCase();
            jd = jd.toLowerCase();

            let words = [...new Set(jd.match(/\b[a-z]{3,}\b/g))];

            if (!words) {
                bot("Invalid Job Description");
                return;
            }

            let matched = [];
            let missing = [];

            words.forEach(w => {
                if (resumeText.includes(w)) matched.push(w);
                else missing.push(w);
            });

            let score = Math.round((matched.length / words.length) * 100);

            bot("🔍 ATS Score: " + score + "%");
            bot("✅ Matched: " + matched.slice(0, 15).join(", "));
            bot("❌ Missing: " + missing.slice(0, 15).join(", "));
        }

        function formatBullets(text) {
            if (!text) return "<p></p>";
            let lines = text.split("\n").filter(l => l.trim() != "");
            if (lines.length === 1) return "<p>" + text + "</p>";
            let list = "<ul>";
            lines.forEach(l => {
                list += "<li>" + l + "</li>";
            });
            list += "</ul>";
            return list;
        }

        function generateResume() {

            const html = '<!DOCTYPE html>' +
                '<html><head><meta charset="utf-8"><title>Corporate Resume</title>' +
                '<style>' +
                'body{font-family:"Times New Roman",serif;margin:40px;color:#111;line-height:1.4;}' +
                '.container{max-width:900px;margin:auto;}' +
                '.header{text-align:center;margin-bottom:20px;}' +
                '.header h1{margin:0;font-size:34px;}' +
                '.contact{font-size:14px;margin-top:6px;}' +
                '.section{margin-top:18px;}' +
                '.section-title{font-weight:bold;border-bottom:1px solid #000;padding-bottom:3px;margin-bottom:6px;text-transform:uppercase;font-size:15px;}' +
                'ul{margin-left:20px;}' +
                '.download{text-align:center;margin-top:25px;}' +
                '.download button{padding:10px 18px;font-size:16px;cursor:pointer;background:#000;color:white;border:none;}' +
                '@media print{.download{display:none;}}' +
                '</style>' +
                '<script>function downloadPDF(){window.print();}<\/script>' +
                '</head><body>' +
                '<div class="container">' +
                '<div class="header"><h1>' + (resume.name || '') + '</h1>' +
                '<div class="contact">' +
                (resume.phone || '') + ' | ' + (resume.email || '') + ' | ' + (resume.linkedin || '') + ' | ' + (resume.github || '') +
                '</div></div>' +
                '<div class="section"><div class="section-title">Education</div><p>' + (resume.education || '') + '</p></div>' +
                '<div class="section"><div class="section-title">Work Experience</div>' + formatBullets(resume.experience) + '</div>' +
                '<div class="section"><div class="section-title">Projects</div>' + formatBullets(resume.projects) + '</div>' +
                '<div class="section"><div class="section-title">Technical Skills</div>' + formatBullets(resume.skills) + '</div>' +
                '<div class="download"><button onclick="downloadPDF()">⬇ Download PDF</button></div>' +
                '</div></body></html>';

            const win = window.open();
            win.document.open();
            win.document.write(html);
            win.document.close();
        }

        bot("Hi! Let's build your resume 🚀");
        bot(questions[0]);
    </script>

</body>

</html>