<?php

namespace NetAnts\WhatsRabbitLiveChatTest;

use craft\base\Event;
use craft\test\EventItem;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use PHPUnit\Framework\TestCase;
use UnitTester;

class PluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private Plugin $plugin;
    protected UnitTester $tester;

    protected function setUp(): void
    {
        $this->plugin = Plugin::getInstance();
    }

    public function testCanCreate(): void
    {
        $this->assertInstanceOf(Plugin::class, $this->plugin);
    }

    public function testInit(): void
    {
        // Given
        $navData = [
            'label' => 'What\'sRabbit LiveChat',
            'url' => 'whatsrabbit-live-chat',
        ];
        $controllerNamespace = 'NetAnts\\WhatsRabbitLiveChat\\Controller';

        // When
        $this->plugin->init();

        // Then
        $this->assertSame($controllerNamespace, $this->plugin->controllerNamespace);
        $this->assertSame($navData, $this->plugin->getCpNavItem());
    }

    public function testGetLiveChatWidget(): void
    {
        // Given
        $data = [
            'avatarAssetId' => ['1'],
            'whatsAppUrl' => 'https://wa.me',
            'title' => 'some title',
            'description' => 'some description',
        ];
        $this->plugin->getPluginInstance()->setSettings($data);
        // When
        $context = ['some data'];
        $result = $this->plugin->getLiveChatWidget($context);

        // Then
        $this->assertSame(sprintf(
            '<whatsrabbit-live-chat-widget
                        avatar-url="%s"
                        login-url="%s"
                        whatsapp-url="%s"
                        welcome-title="%s"
                        welcome-description="%s"
                    ></whatsrabbit-live-chat-widget>',
            '', // Since there is no asset with id '1', the value is blank
            '/actions/whatsrabbit-live-chat/login/get-token',
            $data['whatsAppUrl'],
            $data['title'],
            $data['description']
        ), $result);
    }

    public function testGetPluginInstance(): void
    {
        // When
        $result = $this->plugin->getPluginInstance();

        // Then
        $this->assertInstanceOf(Plugin::class, $result);
    }
}
