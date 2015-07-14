<?php
/**
 * Copyright 2015 Topface, LLC <alexey.y.maslov@topface.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Utility for send sms via sms-online
 * @author alxmsl
 */

include __DIR__ . '/../vendor/autoload.php';

use Topface\SmsOnline\Bulk\Client;
use Topface\SmsOnline\Bulk\Message;
use alxmsl\Cli\CommandPosix;
use alxmsl\Cli\Option;
use alxmsl\Cli\Exception\RequiredOptionException;

$from   = '';
$phones = [];
$secret = '';
$text   = '';
$user   = '';

$Command = new CommandPosix();
$Command->appendHelpParameter('show help');
$Command->appendParameter(new Option('from', 'f', 'sender alpha-name', Option::TYPE_STRING, true)
    , function($name, $value) use (&$from) {
        $from = (string) $value;
    });
$Command->appendParameter(new Option('phone', 'p', 'receiver phone or phones (comma-separated)', Option::TYPE_STRING, true)
    , function($name, $value) use (&$phones) {
        $phones = explode(',', $value);
    });
$Command->appendParameter(new Option('secret', 's', 'secret key', Option::TYPE_STRING, true)
    , function($name, $value) use (&$secret) {
    $secret = (string) $value;
    });
$Command->appendParameter(new Option('text', 't', 'message text', Option::TYPE_STRING, true)
    , function($name, $value) use (&$text) {
        $text = (string) $value;
    });
$Command->appendParameter(new Option('user', 'u', 'sender login', Option::TYPE_STRING, true)
    , function($name, $value) use (&$user) {
        $user = (string) $value;
    });

try {
    $Command->parse(true);

    $Message = new Message($text, true);
    $Message->setPhones($phones);

    $Client = new Client($from, $user, $secret);
    $Result = $Client->send($Message);
    printf("%s\n", (string) $Result);
} catch (RequiredOptionException $Ex) {
    $Command->displayHelp();
}



