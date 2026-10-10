# ربات اکو تلگرام با php

یک ربات ساده اکو تلگرام با PHP و پنل ادمین.

## راه‌اندازی

### ۱. گرفتن توکن
از [@BotFather](https://t.me/BotFather) در تلگرام با دستور `/newbot` یک ربات بسازید و توکن را کپی کنید.

### ۲. قرار دادن توکن
توکن را در فایل `config.php` جایگزین کنید:

```php
define('BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE');
```
### ۳. ست کردن وبهوک
 این آدرس رو یکبار باز کنید تا وبهوک ست بشه
 ```https://yoursite.com/webhook.php?init```
