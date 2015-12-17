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

namespace TopfaceLibrary\SmsOnline\Bulk;

use TopfaceLibrary\SmsOnline\InitializationInterface;

/**
 * Response class
 * @author alxmsl
 */
final class Response implements InitializationInterface {
    /**
     * Response status codes
     */
    const CODE_OK                   = 0, // the request was proceed successfully
          CODE_INCORRECT_DATA       = -1, // incorrect input data
          CODE_AUTHENTICATION_ERROR = -2, // authentication error
          CODE_REJECTED             = -3, // request processing is rejected
          CODE_TECHNICAL_ERROR      = -4, // temporary technical error
          CODE_LIMIT_REACHED        = -5; // you have no more sms on your account

    /**
     * @var int response code
     */
    private $code = 0;

    /**
     * @var string response message
     */
    private $message = '';

    /**
     * @var array message identifiers for delivery report
     */
    private $messageIds = [];

    /**
     * @return int response code
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @return string message identifiers for delivery report
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return array message identifiers for delivery report
     */
    public function getMessageIds() {
        return $this->messageIds;
    }

    /**
     * Method to get delivery report message id for phone
     * @param string $phone phone number
     * @return string message identifier
     */
    public function getMessageId($phone) {
        return $this->messageIds[$phone];
    }

    /**
     * @inheritdoc
     */
    public static function initializeByString($string) {
        $Object            = simplexml_load_string($string);
        $Response          = new Response();
        $Response->code    = (int) $Object->code;
        $Response->message = (string) $Object->tech_message;
        if (isset($Object->msg_id)) {
            foreach ($Object->msg_id as $node) {
                $Response->messageIds[(string) $node['phone']] = (string) $node;
            }
        }
        return $Response;
    }

    /**
     * @inheritdoc
     */
    public function __toString() {
        $format = <<<'EOD'
    code:    %s
    message: %s
    ids:
%s
EOD;
        $ids = '';
        foreach ($this->getMessageIds() as $phone => $messageId) {
            $ids .= sprintf("\t%s: %s\n", $phone, $messageId);
        }
        return sprintf($format
            , $this->getCode()
            , $this->getMessage()
            , $ids);
    }
}
