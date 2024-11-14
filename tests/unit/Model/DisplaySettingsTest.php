<?php

namespace Rabbit\RabbitMessengerLiveChatTest\Model;

use Codeception\PHPUnit\TestCase;
use Rabbit\RabbitMessengerLiveChat\Model\DisplaySettings;

class DisplaySettingsTest extends TestCase
{
    public function testRules()
    {
        $settings = new DisplaySettings();
        $rules = $settings->rules();
        $this->assertSame([
            [['title', 'whatsAppUrl', 'description', 'avatarAssetId'], 'required'],
            [['enabled', 'mobileCollapsed', 'desktopExpanded'], 'boolean'],
            [['position', 'zIndex', 'left', 'right', 'bottom', 'top', 'margin'], 'string']
        ], $rules);
    }
}
