<?php
/*
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
 */

namespace TopfaceLibrary\Test\SmsOnline\Bulk;

use alxmsl\Network\Http\Request;
use PHPUnit_Framework_TestCase;
use ReflectionClass;
use TopfaceLibrary\SmsOnline\Bulk\Client;
use TopfaceLibrary\SmsOnline\Bulk\Message;

/**
 * Client class tests
 * @author alxmsl
 */
final class ClientTest extends PHPUnit_Framework_TestCase {
    public function testInitialization() {
        $Class = new ReflectionClass(Client::class);
        $FromProperty = $Class->getProperty('from');
        $FromProperty->setAccessible(true);
        $UserProperty = $Class->getProperty('user');
        $UserProperty->setAccessible(true);
        $SecretProperty = $Class->getProperty('secret');
        $SecretProperty->setAccessible(true);

        $Client1 = new Client('FROM', 'login', '5eCrET');
        $this->assertEquals('FROM', $FromProperty->getValue($Client1));
        $this->assertEquals('login', $UserProperty->getValue($Client1));
        $this->assertEquals('5eCrET', $SecretProperty->getValue($Client1));
        $this->assertSame(0, $Client1->getConnectTimeout());
        $this->assertSame(0, $Client1->getRequestTimeout());

        $Client2 = new Client('FROM', 'login', '5eCrET');
        $Client2->setRequestTimeout(5)
            ->setConnectTimeout(1);
        $this->assertSame(1, $Client2->getConnectTimeout());
        $this->assertSame(5, $Client2->getRequestTimeout());
    }

    public function testSignature() {
        $Class  = new ReflectionClass(Client::class);
        $Method = $Class->getMethod('sign');
        $Method->setAccessible(true);
        $Client = new Client('FROM', 'login', '5eCrET');

        $Message1 = new Message('hello');
        $this->assertEquals('f4b447d259fb90514e16bebe2bf3dc7d', $Method->invoke($Client, $Message1));

        $Message2 = new Message('hello');
        $Message2->addPhone(79031234567);
        $this->assertEquals('b5c0d2b33bb1293006d803bd7c6461f1', $Method->invoke($Client, $Message2));

        $Message3 = new Message('hello');
        $Message3->addPhone(79031234567);
        $Message3->addPhone(79165557755);
        $this->assertEquals('ac989b951e5e42a3cd48b83ac40d822f', $Method->invoke($Client, $Message3));
    }

    public function testCreateRequest() {
        $Class  = new ReflectionClass(Client::class);
        $Method = $Class->getMethod('createRequest');
        $Method->setAccessible(true);

        $Client1  = new Client('FROM', 'login', '5eCrET');
        $Message1 = new Message('hello');
        /** @var Request $Request1 */
        $Request1 = $Method->invoke($Client1, $Message1);
        $this->assertEquals('https://bulk.sms-online.com', $Request1->getUrl());
        $this->assertEquals('user=login&from=FROM&sign=f4b447d259fb90514e16bebe2bf3dc7d&charset=UTF-8&delay=0&dlr=0&hex=0&txt=hello', $Request1->getPostData());
    }
}
