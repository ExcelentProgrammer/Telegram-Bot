<h1 align="center">Telegram-Bot</h1>

## Kutubxonadagi qulayliklar

- Tez
- Ishonchli
    - Xavfsiz
- oson
    - qulay


## Ro'yhat

- [Kutubxonadagi qulayliklar](#kutubxonadagi-qulayliklar)
- [Boshlash](#boshlash)
    - [Webhook](#webhook)






## Boshlash

Boshlash uchun avval kerakli Modullarni Bot.php Fayliga yuklab olish kerak 

```php
<?php

use Telegram\Bot;
use Database\DB;

```

## Webhook

Endi Bot ishlashi uchun webhook qilish kerak

- webhook index.php ga Yo'naltiriladi
``
https://api.telegram.org/bot{bot-token}/setwebhook?url={fayil-manzili}
``
  
## Xabar yuborish

Bot.php

```php
<?php

use Telegram\Bot;
use Database\DB;


Bot::sendMessage("Salom");
```
  



