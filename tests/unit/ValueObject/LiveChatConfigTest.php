<?php

namespace Rabbit\RabbitMessengerLiveChatTest\ValueObject;

use Rabbit\RabbitMessengerLiveChat\db\Settings;
use Rabbit\RabbitMessengerLiveChat\Exception\InvalidDataException;
use Rabbit\RabbitMessengerLiveChat\Service\SettingsService;
use Rabbit\RabbitMessengerLiveChat\ValueObject\LiveChatConfig;

class LiveChatConfigTest extends \Codeception\PHPUnit\TestCase
{
    public function testCreateFromRequestWithMissingData()
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionMessage('Could not create LiveChatConfig because the following data is missing "title"');
        LiveChatConfig::createFromRequest([
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'mobileCollapsed' => true,
            'desktopExpanded' => true,
            'enabled' => true,
            'whatsAppUrl' => 'https://wa.me',
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]);
    }

    public function testCreateFromRequest()
    {
        $config = LiveChatConfig::createFromRequest([
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => [42],
            'mobileCollapsed' => true,
            'desktopExpanded' => true,
            'enabled' => true,
            'whatsAppUrl' => 'https://wa.me',
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]);

        $this->assertInstanceOf(LiveChatConfig::class, $config);
    }

    public function testCreateFromDatabase()
    {
        $settings = new Settings([
            'title' => 'Some title',
            'description' => 'Some description',
            'avatar_asset_id' => 42,
            'mobile_collapsed' => true,
            'desktop_expanded' => true,
            'enabled' => true,
            'whatsapp_url' => 'https://wa.me',
            'position' => 'fixed',
            'z_index' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]);
        $config = LiveChatConfig::createFromDatabase($settings);

        $this->assertInstanceOf(LiveChatConfig::class, $config);
    }
}
