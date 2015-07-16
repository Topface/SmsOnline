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

namespace Topface\SmsOnline\Bulk;

/**
 * Message class
 * @author alxmsl
 */
final class Message {
    /**
     * Message statuses
     */
    const STATUS_DELIVERED     = 0,  // the message was delivered to the subscriber
          STATUS_BUFFERED      = 1,  // the message is queued at SMSC
          STATUS_ABSENT        = 2,  // the subscriber is out of coverage. Message is queued
          STATUS_PREPARING     = 3,  // the message is being prepared for delivery
          STATUS_UNKNOWN       = 4,  // no reply from mobile network operator
          STATUS_NOT_DELIVERED = -1, // message was not delivered
          STATUS_EXPIRED       = -2, // message expired and deleted from the SMSC
          STATUS_REJECTED      = -3; // delivery rejected by the mobile network operator

    /**
     * Maximum message delay, seconds
     */
    const MAX_DELAY = 10080;

    /**
     * Message type: text or binary
     */
    const TYPE_TEXT   = 0,
          TYPE_BINARY = 1;

    /**
     * Delivery report constants
     */
    const NO_DELIVERY_REPORT = 0,
          DELIVERY_REPORT    = 1;

    /**
     * @var int message type
     */
    private $type = self::TYPE_TEXT;

    /**
     * @var int delivery message delay
     */
    private $delay = 0;

    /**
     * @var bool need delivery message report or not
     */
    private $needDeliveryReport = false;

    /**
     * @var int[] phone numbers for message
     */
    private $phones = [];

    /**
     * @return int[] phone numbers for message
     */
    public function getPhones() {
        return $this->phones;
    }

    /**
     * @param int[] $phones phone numbers for message
     * @return $this self instance
     */
    public function setPhones(array $phones) {
        $this->phones = $phones;
        return $this;
    }

    /**
     * Add phone for delivering method
     * @param int $phone phone number
     * @return $this self instance
     */
    public function addPhone($phone) {
        $this->phones[] = (int) $phone;
        $this->phones = array_unique($this->phones);
        return $this;
    }

    /**
     * @var string message text
     */
    private $text = '';

    /**
     * @return string message text
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @var string transaction identifier
     */
    private $transactionId = '';

    /**
     * @return string transaction identifier
     */
    public function getTransactionId() {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId transaction identifier
     * @return $this self instance
     */
    public function setTransactionId($transactionId) {
        $this->transactionId = (string) $transactionId;
        return $this;
    }

    /**
     * @var null|string session mode prefix
     */
    private $prefix = null;

    /**
     * @return string|null session mode prefix
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * @param string $prefix session mode prefix
     * @return $this self instance
     */
    public function setPrefix($prefix) {
        $this->prefix = (string) $prefix;
        return $this;
    }

    /**
     * @var string message charset
     */
    private $charset = 'UTF-8';

    /**
     * @return string message charset
     */
    public function getCharset() {
        return $this->charset;
    }

    /**
     * @param string $charset message charset
     * @return $this self instance
     */
    public function setCharset($charset) {
        $this->charset = (string) $charset;
        return $this;
    }

    /**
     * @var null|string message UDH
     */
    private $userDataHeader = null;

    /**
     * @return null|string message UDH
     */
    public function getUserDataHeader() {
        return $this->userDataHeader;
    }

    /**
     * @param string $userDataHeader UDH for message
     * @return $this self instance
     */
    public function setUserDataHeader($userDataHeader) {
        $this->userDataHeader = (string) $userDataHeader;
        return $this;
    }

    /**
     * @param string $text message text
     * @param bool $needDeliveryReport need delivery report or not
     * @param int $delay timeout for message delivering, seconds
     * @param int $type message type
     */
    public function __construct($text, $needDeliveryReport = false, $delay = 0, $type = self::TYPE_TEXT) {
        $this->delay              = min($delay, self::MAX_DELAY);
        $this->needDeliveryReport = (bool) $needDeliveryReport;
        $this->text               = (string) $text;
        $this->type               = $type;
    }

    /**
     * Export message state for sending method
     * @return string[] message state
     */
    public function export() {
        $data = [
            'charset=' .  rawurlencode($this->getCharset()),
            'delay='   .  $this->delay,
            'dlr='     .  ($this->needDeliveryReport ? self::DELIVERY_REPORT : self::NO_DELIVERY_REPORT),
            'hex='     .  $this->type,
            'txt='     .  $this->getText(),
        ];
        foreach ($this->getPhones() as $phone) {
            $data[] = 'phone=' . rawurlencode($phone);
        }
        if (!empty($this->getTransactionId())) {
            $data[] = 'p_transaction_id=' . rawurlencode($this->getTransactionId());
        }
        if (!is_null($this->getPrefix())) {
            $data[] = 'pref=' . rawurlencode($this->getPrefix());
        }
        if (!is_null($this->getUserDataHeader())) {
            $data[] = 'udh=' . $this->getUserDataHeader();
        }
        return $data;
    }
}
