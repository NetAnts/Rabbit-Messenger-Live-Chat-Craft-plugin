<?php

namespace NetAnts\WhatsRabbitLiveChatTest;

use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NetAnts\WhatsRabbitLiveChat\Model\Settings;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use PHPUnit\Framework\TestCase;

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
        $controllerNamespace = 'NetAnts\\WhatsRabbitLiveChat\\Controller';

        // When
        $this->plugin->init();

        // Then
        $this->assertSame($controllerNamespace, $this->plugin->controllerNamespace);
    }

    public function testAddNavItem(): void
    {
        $event = Mockery::mock(RegisterCpNavItemsEvent::class);
        $event->navItems = [];

        $this->plugin->addNavItem($event);

        $this->assertCount(1, $event->navItems);
        $expectedNavItem = [
            'url' => 'whatsrabbit-live-chat',
            'label' => 'What\'sRabbit LiveChat',
            'icon' => '@app/icons/envelope.svg',
            'subnav' => [
                [
                    'url' => 'whatsrabbit-live-chat',
                    'label' => 'Settings',
                ]
            ]
        ];

        $this->assertSame($expectedNavItem, $event->navItems[0]);
    }

    public function testAddRoute(): void
    {
        $event = Mockery::mock(RegisterUrlRulesEvent::class);
        $event->rules = [];

        $this->plugin->addRoute($event);

        $this->assertCount(1, $event->rules);
        $expectedRules = [
            'whatsrabbit-live-chat' => 'login/getToken'
        ];
        $this->assertSame($expectedRules, $event->rules);
    }


    public function testCreateSettingsModel(): void
    {
        $settings = $this->plugin->getSettings();
        $this->assertInstanceOf(Settings::class, $settings);
    }

    public function testGetLiveChatWidget(): void
    {
        $context = [];
        $settings = [
            'avatarAssetId' => [0],
        ];
        $this->plugin->setSettings($settings);
        $response = $this->plugin->getLiveChatWidget($context);
        $expectedHtml = '<whatsrabbit-live-chat-widget
                                    avatar-url=""
                                    login-url="/actions/whatsrabbit-live-chat/login/get-token"
                                    whatsapp-url=""
                                    welcome-title=""
                                    welcome-description=""
                                ></whatsrabbit-live-chat-widget>';
        $this->assertSame(preg_replace("(\s+)", "\s", $expectedHtml), preg_replace("(\s+)", "\s", $response));
    }
}
