<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<title>پنل ربات</title>
<style>
    * { box-sizing: border-box; }
    body {
        font-family: Tahoma, sans-serif;
        background: #121212;
        color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }
    .box {
        background: #1e1e1e;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,.6);
        text-align: center;
        min-width: 320px;
    }
    h1 {
        color: #fff;
        margin: 0 0 25px;
        font-size: 22px;
    }
    a {
        display: block;
        padding: 14px;
        margin: 10px 0;
        background: #0088cc;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        transition: background .2s;
        font-size: 15px;
    }
    a:hover { background: #00a0f0; }
    a.admin { background: #6c5ce7; }
    a.admin:hover { background: #7f6ff0; }
</style>
</head>
<body>
    <div class="box">
        <h1>🤖 پنل مدیریت ربات</h1>
        <a href="webhook.php?init" target="_blank">🔗 ست کردن Webhook</a>
        <a href="admin.php" class="admin">👤 پنل ادمین</a>
    </div>
</body>
</html>
