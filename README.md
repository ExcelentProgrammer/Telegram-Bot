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
    - [botga yuborilgan xabarni qabul 0qilish](#botga-yuborilgan-xabarni-qabul-qilish)






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

## botga yuborilgan xabarni qabul qilish

Foydalanuvchi botga yuborgan Textli Xabarni Qabul qilish

```php
Bot::getText();
````

  
## Xabar yuborish

Bot.php

```php
<?php

use Telegram\Bot;
use Database\DB;


Bot::sendMessage("Salom");
```
  



