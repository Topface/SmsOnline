# SmsOnlineClient 

[🇬🇧](/README.en.md)

[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](http://www.apache.org/licenses/LICENSE-2.0)
[![Build Status](https://travis-ci.org/Topface/SmsOnline.svg?branch=v1.0.0)](https://travis-ci.org/Topface/SmsOnline)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Topface/SmsOnline/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Topface/SmsOnline/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Topface/SmsOnline/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Topface/SmsOnline/?branch=master)

PHP библиотека, реализующая [SMS Bulk API v2.2](http://ru.sms-online.com/doc/smsonline_sms_bulk_v2.2_ru.pdf) компании 
 [SmsOnline](sms-online.com)

## Достоинства

1. Простота. Для отправления смс доступно всего 3 класса: [клиента API](/source/Bulk/Client.php), 
 [сообщение](/source/Bulk/Message.php) и [ответ](/source/Bulk/Response.php)
2. Полная фукнциональность. Вы можете создавать один экзепляр сообщения для нескольких получателей  
3. Реализован в виде `composer`-пакета, что позволяет подклчить библиотеку в одну строчку
4. Для быстрой отправки смс можно использовать [утилиту](/bin/send.php) командной строки 

## Установка

Для простого использования получите пакет через `composer`

```
    $ composer require topface/smsonline
```

При использовании в стороннем проекте, измените `composer.json` следующим образом

```
    "topface/smsonline": "*"
```

Затем выполните команду `composer update`

## Использование

Для начала необходимо создать сущность клиента, определив его логин, секретный ключ и наименование отправителя

```
    use TopfaceLibrary\SmsOnline\Bulk\Client;
    
    $Client = new Client(<from>, <user>, <secret>);
```

Затем создайте экземпляр сообщения и просто отправьте его

```
    use TopfaceLibrary\SmsOnline\Bulk\Message;

    $Message = new Message('hello');
    $Message->addPhone(79031234567);
    $Message->addPhone(79165557755);
    $Result = $Client->send($Message);
```

## Использование командной строки

Можно использовать скрипт `send.php` для вызова Sms Bulk API напрямую

```
    $ php bin/send.php -h
    Using: /usr/local/bin/php bin/send.php [-h|--help] -f|--from -p|--phone -s|--secret -t|--text -u|--user
    -h, --help  - show help
    -f, --from  - sender alpha-name
    -p, --phone  - receiver phone or phones (comma-separated)
    -s, --secret  - secret key
    -t, --text  - message text
    -u, --user  - sender login
```

Просто вызовите скрипт, указав требуемые параметры: наименование отправителя, секретный ключ, логин, телефон получателя 
 и текст сообщения

```
    $ php bin/send.php -f='Company' -p='79031234567,79165557755' -s='secRet' -t='hello' -u='userlogin'
        code:    0
        message: OK
        ids:
            79031234567: 45678901-2222-1111-4466-aabbcc556677
            79165557755: 56789012-2222-1111-4466-aabbcc556677
```

Если случится что-нибудь плохое, скрипт вернет код ошибки возврата и ее описание

```
    $ php bin/send.php -f='Company' -p='79031234567,79165557755' -s='wr0NGsecRet' -t='hello' -u='userlogin'
        code:    -2
        message: AUTH ERROR (sign)
        ids:
```

## Тестирование

Для запуска тестов воспользуйтесь командой `phpunit`

```
    $ phpunit
    PHPUnit 4.7.5 by Sebastian Bergmann and contributors.
    
    Runtime:	PHP 5.5.23
    
    ......
    
    Time: 111 ms, Memory: 6.00Mb
    
    OK (6 tests, 47 assertions)
```

## Содействие 

Мы будем рады любой помощи в развитии проекта. 
Исправления принимаются в виде пул-реквестов.
Искренне просим вас добавлять тесты и документировать все присылаемые изменения

## Лицензия

Авторское право © 2015 ООО "Топфейс" <alexey.y.maslov@topface.com>
Лицензировано Apache License, Version 2.0. С полным текстом лицензии 
можно ознакомиться по ссылке

    http://www.apache.org/licenses/LICENSE-2.0
