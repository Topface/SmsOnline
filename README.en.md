# SmsOnlineClient 

[🇷🇺](/README.md)
[![Build Status](https://travis-ci.org/Topface/SmsOnline.svg?branch=v1.0.0)](https://travis-ci.org/Topface/SmsOnline)

PHP client for [SMS Bulk API v2.2](http://ru.sms-online.com/doc/smsonline_sms_bulk_v2.2_en.pdf) by company 
 [SmsOnline](sms-online.com)

## Advantages

1. Simplicity. To send an SMS, there are 3 classes available: [API client](/source/Bulk/Client.php), 
 [message](/source/Bulk/Message.php) and [response](/source/Bulk/Response.php)
2. Complete Functionality. You can create one template/sample and use it to send message to multiple users
3. Built-in as the packet `composer`, this allows a user to connect their library in one line
4. Independent namespace helps to use API client on different projects and frameworks
5. To quickly send an SMS one can use the [command-line utility](/bin/send.php)

## Installation

For quick use, install the packet with the help of `composer`

```
    $ composer require topface/smsonlineclient
```

For use in other projects, change `composer.json` by using the following

```
    "topface/smsonlineclient": "*"
```

Afterwards enter the command: `composer update`

## Usages

First a user must create a client instance, as well as determine their login, password, and sender designation

```
    use Topface\SmsOnline\Bulk\Client;
    
    $Client = new Client(<from>, <user>, <secret>);
```

Afterwards create a sample message and send it

```
    use Topface\SmsOnline\Bulk\Message;

    $Message = new Message('hello');
    $Message->addPhone(79031234567);
    $Message->addPhone(79165557755);
    $Result = $Client->send($Message);
```

## Console usage

It's possible to use the script `send.php` to call SMS Bulk API directly

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

Simply call the script and indicate the required parameters: sender designation, password, login, receiver's phone 
 number and text for the message

```
    $ php bin/send.php -f='Company' -p='79031234567,79165557755' -s='secRet' -t='hello' -u='userlogin'
        code:    0
        message: OK
        ids:
            79031234567: 45678901-2222-1111-4466-aabbcc556677
            79165557755: 56789012-2222-1111-4466-aabbcc556677
```

If something goes wrong the script will return an error code and description:

```
    $ php bin/send.php -f='Company' -p='79031234567,79165557755' -s='wr0NGsecRet' -t='hello' -u='userlogin'
        code:    -2
        message: AUTH ERROR (sign)
        ids:
```

## Tests

To initiate a test use this command: `phpunit`

```
    $ phpunit
    PHPUnit 4.7.5 by Sebastian Bergmann and contributors.
    
    Runtime:	PHP 5.5.23
    
    ......
    
    Time: 111 ms, Memory: 6.00Mb
    
    OK (6 tests, 47 assertions)
```

## Contributing

We welcome any help in developing this project. We accept contributions as pull requests. Finally, we kindly ask that 
 you add your tests and document all changes in library behavior/corrections.

## License

Copyright 2015 Topface, LLC <alexey.y.maslov@topface.com>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
