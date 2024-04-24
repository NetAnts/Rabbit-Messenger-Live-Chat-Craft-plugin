<?php

namespace Rabbit\RabbitMessengerLiveChatTest\Model;

use Codeception\PHPUnit\TestCase;
use Rabbit\RabbitMessengerLiveChat\Model\ApiSettings;

class ApiSettingsTest extends TestCase
{
    public function testRules()
    {
        $settings = new ApiSettings();
        $rules = $settings->rules();
        $this->assertSame([[['apiKey', 'apiSecret'], 'required']], $rules);
    }
}
