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

namespace Topface\Test\SmsOnline\Bulk;

use PHPUnit_Framework_TestCase;
use Topface\SmsOnline\Bulk\Message;

/**
 * Message class tests
 * @author alxmsl
 */
final class MessageTest extends PHPUnit_Framework_TestCase {
    public function testInitialization() {
        $Message1 = new Message('hello');
        $this->assertEquals('UTF-8', $Message1->getCharset());
        $this->assertEmpty($Message1->getTransactionId());
        $this->assertEmpty($Message1->getPhones());
        $this->assertNull($Message1->getPrefix());
        $this->assertEquals('hello', $Message1->getText());
        $this->assertNull($Message1->getUserDataHeader());

        $Message2 = new Message('');
        $Message2->setPhones([
            79031234567,
        ]);
        $this->assertEquals([
            79031234567,
        ], $Message2->getPhones());
        $Message2->addPhone(79165557755);
        $this->assertEquals([
            79031234567,
            79165557755,
        ], $Message2->getPhones());
        $Message2->setPhones([]);
        $this->assertEmpty($Message2->getPhones());

        $Message3 = new Message('');
        $Message3->setCharset('UTF-16BE');
        $this->assertEquals('UTF-16BE', $Message3->getCharset());
        $Message3->setCharset('UTF-8');
        $this->assertEquals('UTF-8', $Message3->getCharset());

        $Message4 = new Message('');
        $Message4->setPrefix('OPOP');
        $this->assertEquals('OPOP', $Message4->getPrefix());

        $Message5 = new Message('');
        $Message5->setTransactionId('s0meTr4n54CTi0N');
        $this->assertEquals('s0meTr4n54CTi0N', $Message5->getTransactionId());

        $Message6 = new Message('');
        $Message6->setUserDataHeader('AABB667788DD');
        $this->assertEquals('AABB667788DD', $Message6->getUserDataHeader());
    }

    public function testExport() {
        $Message1 = new Message('hello');
        $this->assertEquals([
            'charset=UTF-8',
            'delay=0',
            'dlr=0',
            'hex=0',
            'txt=hello',
        ], $Message1->export());

        $Message2 = new Message('hi', true, 15000, Message::TYPE_BINARY);
        $this->assertEquals([
            'charset=UTF-8',
            'delay=10080',
            'dlr=1',
            'hex=1',
            'txt=hi',
        ], $Message2->export());

        $Message3 = new Message('text');
        $Message3->addPhone(79031234567);
        $Message3->addPhone(79165557755);
        $this->assertEquals([
            'charset=UTF-8',
            'delay=0',
            'dlr=0',
            'hex=0',
            'txt=text',
            'phone=79031234567',
            'phone=79165557755',
        ], $Message3->export());

        $Message4 = new Message('text');
        $Message4->addPhone(79031234567)
            ->setCharset('UTF-16BE')
            ->setPrefix('OPOP')
            ->setTransactionId('s0meTr4n54CTi0N')
            ->setUserDataHeader('AABB667788DD');
        $this->assertEquals([
            'charset=UTF-16BE',
            'delay=0',
            'dlr=0',
            'hex=0',
            'txt=text',
            'phone=79031234567',
            'p_transaction_id=s0meTr4n54CTi0N',
            'pref=OPOP',
            'udh=AABB667788DD',
        ], $Message4->export());
    }
}
