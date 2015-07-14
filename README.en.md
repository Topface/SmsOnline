# SmsOnlineClient 

[🇷🇺](/README.md)

PHP client for [SMS Bulk API v2.2](http://ru.sms-online.com/doc/smsonline_sms_bulk_v2.2_en.pdf) of 
 [SmsOnline](sms-online.com)

## Advantages

1. Lightweight. You could use only three classes for work: [API client](/source/Bulk/Client.php), 
 [message](/source/Bulk/Message.php) and [response](/source/Bulk/Response.php)
2. Powerful. You could create one message instance for multiple phones  
3. `composer` support makes installation simplified
4. Independent namespace helps to use API client on different projects and frameworks
5. [CLI utility](/bin/send.php) helps you to test Bulk API interactions

## Installation

For simplified usage all what you need is require packet via composer

```
    $ composer require topface/smsonlineclient
```

In third-party projects, require packet in your `composer.json`

```
    "topface/smsonlineclient": "*"
```

...and update composer: `composer update`

## Usages

First what you need is client instance. Just create it using you client login, secret and alpha-name

```
    use Topface\SmsOnline\Bulk\Client;
    
    $Client = new Client(<from>, <user>, <secret>);
```

....then create message and send it

```
    use Topface\SmsOnline\Bulk\Message;

    $Message = new Message('hello');
    $Message->addPhone(79031234567);
    $Message->addPhone(79165557755);
    $Result = $Client->send($Message);
```

## Console usage

You could use script `send.php` to call Sms Bulk API directly

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

Using utility you could send messages

```
    $ php bin/send.php -f='Company' -p='79031234567,79165557755' -s='secRet' -t='hello' -u='userlogin'
        code:    0
        message: OK
        ids:
            79031234567: 45678901-2222-1111-4466-aabbcc556677
            79165557755: 56789012-2222-1111-4466-aabbcc556677
```

When something wrong, utility will show error response

```
    $ php bin/send.php -f='Company' -p='79031234567,79165557755' -s='wr0NGsecRet' -t='hello' -u='userlogin'
        code:    -2
        message: AUTH ERROR (sign)
        ids:
```

## Tests

For completely tests running just call `phpunit` command

```
    $ phpunit
    PHPUnit 4.7.5 by Sebastian Bergmann and contributors.
    
    Runtime:	PHP 5.5.23
    
    ......
    
    Time: 111 ms, Memory: 6.00Mb
    
    OK (6 tests, 47 assertions)
```

## Contributing

Contributing are welcome. 
We accepts contributions via pull requests.
Please, add tests for you patches and document any changes in library behaviour

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
