<?php

declare(strict_types=1);

namespace Rabbit\RabbitMessengerLiveChatTest\Factory;

use craft\helpers\App;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Rabbit\RabbitMessengerLiveChat\Factory\LiveChatServiceFactory;
use Rabbit\RabbitMessengerLiveChat\Service\SettingsService;
use PHPUnit\Framework\TestCase;
use Rabbit\LiveChatPluginCore\LiveChatService;

class LiveChatServiceFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private LiveChatServiceFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new LiveChatServiceFactory();
    }

    public function testInvokeCreatesLiveChatService(): void
    {
        $factory = new LiveChatServiceFactory();
        $settingsService = Mockery::mock(SettingsService::class);
        $settingsService->pluginRepoUrl = 'bla';
        $service = $factory(LiveChatService::class, $settingsService);
        $this->assertInstanceOf(LiveChatService::class, $service);
    }
}
