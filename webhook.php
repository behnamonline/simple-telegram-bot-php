<?php
require_once __DIR__ . '/config.php';

$sec = explode(':', BOT_TOKEN)[1] ?? '';

// ==== ست Webhook با ?init (بدون نیاز به sec) ====
if (isset($_GET['init'])) {
    $path = strtok($_SERVER['REQUEST_URI'], '?');
    $url  = 'https://' . $_SERVER['HTTP_HOST'] . $path . '?sec=' . $sec;

    echo file_get_contents(API . 'setWebhook?url=' . urlencode($url) . '&drop_pending_updates=true');
    exit;
}

// ==== پردازش پیام (فقط اینجا sec چک میشه) ====
$update = json_decode(file_get_contents('php://input'), true);

// اگر آپدیت از تلگرام آمده، sec باید درست باشد
if ($update) {
    if (!isset($_GET['sec']) || $_GET['sec'] !== $sec) {
        http_response_code(403);
        exit;
    }

    $chat_id = $update['message']['chat']['id'] ?? null;
    $text    = $update['message']['text'] ?? null;
    $from    = $update['message']['from'] ?? null;

    if ($chat_id && $text) {
        if ($from) {
            saveUser($from);
        }

        $reply = $text === '/start'
            ? 'سلام! هر چی بفرستی همون رو می‌فرستم.'
            : '🔁 ' . $text;

        file_get_contents(API . 'sendMessage?' . http_build_query([
            'chat_id' => $chat_id,
            'text'    => $reply,
        ]));
    }
}

http_response_code(200);


/**
 * ذخیره اطلاعات کاربر در فایل JSON
 */
function saveUser($from) {
    $users = [];

    if (file_exists(USERS_FILE)) {
        $users = json_decode(file_get_contents(USERS_FILE), true) ?: [];
    }

    $id = $from['id'];
    $users[$id] = [
        'id'         => $id,
        'first_name' => $from['first_name'] ?? '',
        'last_name'  => $from['last_name'] ?? '',
        'username'   => $from['username'] ?? '',
        'language'   => $from['language_code'] ?? '',
        'last_seen'  => date('Y-m-d H:i:s'),
    ];

    file_put_contents(USERS_FILE, json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
