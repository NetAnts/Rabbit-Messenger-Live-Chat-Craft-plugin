<?php

namespace Rabbit\RabbitMessengerLiveChatTest;

use Codeception\PHPUnit\TestCase;
use Craft;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Rabbit\RabbitMessengerLiveChat\Model\ApiSettings;
use Rabbit\RabbitMessengerLiveChat\Model\DisplaySettings;
use Rabbit\RabbitMessengerLiveChat\Plugin;
use Rabbit\RabbitMessengerLiveChat\Service\SettingsService;
use Rabbit\RabbitMessengerLiveChat\ValueObject\LiveChatConfig;

//use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private Plugin $plugin;
    protected UnitTester $tester;

    protected function setUp(): void
    {
        $this->plugin = new Plugin('rabbit-messenger-live-chat');
    }

    public function testCanCreate(): void
    {
        $this->assertInstanceOf(Plugin::class, $this->plugin);
    }

    public function testInit(): void
    {
        // Given
        $controllerNamespace = 'Rabbit\\RabbitMessengerLiveChat\\Controller';

        // When
        $this->plugin->init();

        // Then
        $this->assertSame($controllerNamespace, $this->plugin->controllerNamespace);
    }

    public function testInitAddsHtml(): void
    {
        $this->plugin->isInstalled = true;
        $settingsService = Mockery::mock(SettingsService::class);
        $settingsService->expects('getSettings')->twice()->andReturn(LiveChatConfig::createFromRequest([
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'desktopExpanded' => true,
            'showInformationForm' => true,
            'starterPopupTimer' => '25',
            'enabled' => true,
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]));
        $settingsServiceProperty = new \ReflectionProperty(Plugin::class, 'service');
        $settingsServiceProperty->setAccessible(true);
        $settingsServiceProperty->setValue($this->plugin, $settingsService);

        // When
        $this->plugin->init();

        // Then
        $this->assertStringContainsString('<rabbit-messenger-live-chat-widget', Craft::$app->getView()->getBodyHtml());
    }

    public function testAddNavItem(): void
    {
        $event = Mockery::mock(RegisterCpNavItemsEvent::class);
        $event->navItems = [];

        $this->plugin->addNavItem($event);

        $this->assertCount(1, $event->navItems);
        $expectedNavItem = [
            'url' => 'rabbit-messenger-live-chat/display-settings/edit',
            'label' => 'Rabbit Messenger Live-chat',
            'icon' => '@Rabbit/RabbitMessengerLiveChat/icon.svg',
        ];

        $this->assertSame($expectedNavItem, $event->navItems[0]);
    }

    public function testAddCpRoute(): void
    {
        $event = Mockery::mock(RegisterUrlRulesEvent::class);
        $event->rules = [];

        $this->plugin->addCpRoute($event);

        $this->assertCount(1, $event->rules);
        $expectedRules = [
            'rabbit-messenger-live-chat/display-settings/edit' => 'rabbit-messenger-live-chat/display-settings/edit'
        ];
        $this->assertSame($expectedRules, $event->rules);
    }


    public function testCreateSettingsModel(): void
    {
        $settings = $this->plugin->getSettings();
        $this->assertInstanceOf(ApiSettings::class, $settings);
    }

    public function testGetLiveChatWidget(): void
    {
        $settingsService = Mockery::mock(SettingsService::class);
        $settingsService->expects('getSettings')->andReturn(LiveChatConfig::createFromRequest([
            'description' => 'Some description',
            'avatarAssetId' => ['some-avatar-id'],
            'whatsAppUrl' => 'https://wa.me',
            'desktopExpanded' => true,
            'showInformationForm' => true,
            'starterPopupTimer' => '25',
            'enabled' => true,
            'position' => 'fixed',
            'zIndex' => '10',
            'left' => 'inherit',
            'right' => '0',
            'bottom' => '0',
            'top' => 'inherit',
            'margin' => '20px',
        ]));
        $settingsServiceProperty = new \ReflectionProperty(Plugin::class, 'service');
        $settingsServiceProperty->setAccessible(true);
        $settingsServiceProperty->setValue($this->plugin, $settingsService);

        $response = $this->plugin->getLiveChatWidget();
        $expectedHtml = '<rabbit-messenger-live-chat-widget
                                    avatar-url=""
                                    login-url="/actions/rabbit-messenger-live-chat/login/get-token"
                                    whatsapp-url="https://wa.me"
                                    welcome-description="Some description"
                                    display-options="{&quot;position&quot;:&quot;fixed&quot;,&quot;z-index&quot;:&quot;10&quot;,&quot;left&quot;:&quot;inherit&quot;,' .
                                    '&quot;right&quot;:&quot;0&quot;,&quot;bottom&quot;:&quot;0&quot;,&quot;top&quot;:&quot;inherit&quot;,&quot;margin&quot;:&quot;20px&quot;}"
                                    default-expanded-desktop="true"
                                    show-information-form="true"
                                    starter-popup-timer="25"
                                ></rabbit-messenger-live-chat-widget>';
        $this->assertSame(preg_replace("(\s+)", "\s", $expectedHtml), preg_replace("(\s+)", "\s", $response));
    }
}
