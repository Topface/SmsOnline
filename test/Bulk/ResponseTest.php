<?php

namespace Topface\Test\SmsOnline\Bulk;

use PHPUnit_Framework_TestCase;
use Topface\SmsOnline\Bulk\Response;

/**
 * Response class tests
 * @author alxmsl
 */
final class ResponseTest extends PHPUnit_Framework_TestCase {
    public function test() {
        $Response1 = Response::initializeByString('<?xml version="1.0"?>
<response>
 <tech_message>OK</tech_message>
 <code>0</code>
 <msg_id phone="79031234567">550e8400-e29b-41d4-a716-446655440000</msg_id>
 <msg_id phone="79165557755">550e8400-e29b-41d4-a716-446655440001</msg_id>
</response>');
        $this->assertEquals('OK', $Response1->getMessage());
        $this->assertSame(0, $Response1->getCode());
        $this->assertCount(2, $Response1->getMessageIds());
        $this->assertEquals([
            '79031234567' => '550e8400-e29b-41d4-a716-446655440000',
            '79165557755' => '550e8400-e29b-41d4-a716-446655440001',
        ], $Response1->getMessageIds());
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440000', $Response1->getMessageId('79031234567'));
        $this->assertEquals('550e8400-e29b-41d4-a716-446655440001', $Response1->getMessageId('79165557755'));
        $this->assertEquals('    code:    0
    message: OK
    ids:
	79031234567: 550e8400-e29b-41d4-a716-446655440000
	79165557755: 550e8400-e29b-41d4-a716-446655440001
', (string) $Response1);
        
        $Response2 = Response::initializeByString('<?xml version="1.0"?>
<response>
 <tech_message>OK</tech_message>
 <code>0</code>
</response>');
        $this->assertEquals('OK', $Response2->getMessage());
        $this->assertSame(0, $Response2->getCode());
        $this->assertCount(0, $Response2->getMessageIds());
        $this->assertEmpty($Response2->getMessageIds());
        $this->assertEquals('    code:    0
    message: OK
    ids:
', (string) $Response2);

        $Response3 = Response::initializeByString('<?xml version="1.0" encoding="utf-8"?>
<response>
<code>-1</code>
<tech_message>SYNTAX ERROR (user/sign)</tech_message>
</response>');
        $this->assertEquals('SYNTAX ERROR (user/sign)', $Response3->getMessage());
        $this->assertSame(-1, $Response3->getCode());
        $this->assertCount(0, $Response3->getMessageIds());
        $this->assertEmpty($Response3->getMessageIds());
        $this->assertEquals('    code:    -1
    message: SYNTAX ERROR (user/sign)
    ids:
', (string) $Response3);
    }
}
