<?php
require_once __DIR__ . '/config.php';

define('ADMIN_PASSWORD', '1234'); // رمز ورود پنل - عوضش کن

session_start();

// ==== ورود ادمین ====
if (isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin'] = true;
    } else {
        $error = 'رمز اشتباه است';
    }
}

// ==== خروج ====
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// ==== ارسال پیام به کاربر ====
$sendResult = '';
if (isset($_POST['send_message']) && !empty($_SESSION['admin'])) {
    $to_id = $_POST['user_id'];
    $msg   = $_POST['message'];

    $res = file_get_contents(API . 'sendMessage?' . http_build_query([
        'chat_id' => $to_id,
        'text'    => $msg,
    ]));

    $data = json_decode($res, true);
    $sendResult = ($data['ok'] ?? false)
        ? "✅ پیام با موفقیت ارسال شد"
        : "❌ خطا در ارسال: " . ($data['description'] ?? 'نامشخص');
}

// ==== خواندن کاربران ====
$users = [];
if (file_exists(USERS_FILE)) {
    $users = json_decode(file_get_contents(USERS_FILE), true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<title>پنل ادمین ربات</title>
<style>
    body { font-family: Tahoma, sans-serif; background: #f0f2f5; padding: 20px; }
    .box { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(0,0,0,.1); }
    h1, h2 { color: #333; }
    input, textarea, button { font-family: inherit; font-size: 14px; padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc; width: 100%; box-sizing: border-box; margin-bottom: 8px; }
    button { background: #0088cc; color: #fff; border: none; cursor: pointer; }
    button:hover { background: #006fa3; }
    .user { border: 1px solid #e0e0e0; padding: 12px; border-radius: 6px; margin-bottom: 10px; }
    .user b { color: #0088cc; }
    .send-box { display: none; margin-top: 10px; }
    .send-box.open { display: block; }
    .msg-ok { color: green; font-weight: bold; }
    .msg-err { color: red; font-weight: bold; }
    .logout { float: left; color: #c00; text-decoration: none; }
    small { color: #888; }
</style>
<script>
function toggleSend(id) {
    document.getElementById('send-' + id).classList.toggle('open');
}
</script>
</head>
<body>

<?php if (empty($_SESSION['admin'])): ?>

    <div class="box" style="max-width:400px;margin:80px auto;">
        <h2>🔐 ورود ادمین</h2>
        <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="post">
            <input type="password" name="password" placeholder="رمز عبور" required>
            <button type="submit">ورود</button>
        </form>
    </div>

<?php else: ?>

    <div class="box">
        <h1>👥 لیست کاربران ربات <a href="?logout" class="logout">خروج</a></h1>

        <?php if ($sendResult): ?>
            <p class="<?= strpos($sendResult, '✅') !== false ? 'msg-ok' : 'msg-err' ?>">
                <?= $sendResult ?>
            </p>
        <?php endif; ?>

        <?php if (empty($users)): ?>
            <p>هنوز هیچ کاربری ربات را استارت نزده است.</p>
        <?php else: ?>
            <p><small>تعداد کاربران: <?= count($users) ?></small></p>

            <?php foreach ($users as $u): ?>
                <div class="user">
                    <b><?= htmlspecialchars(trim($u['first_name'] . ' ' . $u['last_name'])) ?></b>
                    <?php if (!empty($u['username'])): ?>
                        (<a href="https://t.me/<?= htmlspecialchars($u['username']) ?>" target="_blank">@<?= htmlspecialchars($u['username']) ?></a>)
                    <?php endif; ?>
                    <br>
                    <small>
                        🆔 <code><?= $u['id'] ?></code> |
                        🌐 <?= htmlspecialchars($u['language']) ?> |
                        ⏰ <?= htmlspecialchars($u['last_seen']) ?>
                    </small>
                    <br><br>
                    <button onclick="toggleSend(<?= $u['id'] ?>)">✉️ ارسال پیام</button>

                    <div class="send-box" id="send-<?= $u['id'] ?>">
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <textarea name="message" rows="4" placeholder="متن پیام برای این کاربر..." required></textarea>
                            <button type="submit" name="send_message">📤 ارسال</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php endif; ?>

</body>
</html>
