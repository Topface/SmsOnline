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

use alxmsl\Network\Http\Request;

/**
 * Sms Bulk API client
 * @author alxmsl
 */
final class Client {
    /**
     * Endpoint url for Sms Bulk API
     */
    const ENDPOINT_URI = 'https://bulk.sms-online.com';

    /**
     * @var string sender alpha-name
     */
    private $from = '';

    /**
     * @var string secret key
     */
    private $secret = '';

    /**
     * @var string sender login
     */
    private $user = '';

    /**
     * @var int connect timeout, seconds
     */
    private $connectTimeout = 0;

    /**
     * @var int request timeout, seconds
     */
    private $requestTimeout = 0;

    /**
     * @param int $connectTimeout connect timeout, seconds
     * @return $this
     */
    public function setConnectTimeout($connectTimeout) {
        $this->connectTimeout = (int) $connectTimeout;
        return $this;
    }

    /**
     * @return int connect timeout, seconds
     */
    public function getConnectTimeout() {
        return $this->connectTimeout;
    }

    /**
     * @param int $requestTimeout request timeout, seconds
     * @return $this
     */
    public function setRequestTimeout($requestTimeout) {
        $this->requestTimeout = (int) $requestTimeout;
        return $this;
    }

    /**
     * @return int request timeout, seconds
     */
    public function getRequestTimeout() {
        return $this->requestTimeout;
    }

    /**
     * @param string $from sender alpha-name
     * @param string $user sender login
     * @param string $secret secret key
     */
    public function __construct($from, $user, $secret) {
        $this->from   = (string) $from;
        $this->secret = (string) $secret;
        $this->user   = (string) $user;
    }

    /**
     * Send message method
     * @param Message $Message message instance
     * @return Response response instance
     * @codeIgnoreCoverage
     */
    public function send(Message $Message) {
        return Response::initializeByString($this->createRequest($Message)->send());
    }

    /**
     * Create request method
     * @param Message $Message message instance
     * @return Request HTTP request instance
     */
    private function createRequest(Message $Message) {
        $Request = new Request();
        $Request->setTransport(Request::TRANSPORT_CURL);
        $Request->setUrl(self::ENDPOINT_URI)
            ->setContentTypeCode(Request::CONTENT_TYPE_TEXT)
            ->setConnectTimeout($this->getConnectTimeout())
            ->setTimeout($this->getRequestTimeout());

        $data = array_merge([
            'user=' . $this->user,
            'from=' . $this->from,
            'sign=' . $this->sign($Message),
        ], $Message->export());
        $Request->setPostData(implode('&', $data));
        return $Request;
    }

    /**
     * Sign message method
     * @param Message $Message message instance
     * @return string message signature
     */
    private function sign(Message $Message) {
        return md5($this->user . $this->from . implode($Message->getPhones()) . $Message->getText() . $this->secret);
    }
}
