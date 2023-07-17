<?php

namespace NetAnts\WhatsRabbitLiveChatTest;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use PHPUnit\Framework\TestCase;
use UnitTester;

class PluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    private Plugin|null $plugin;

    protected function setUp(): void
    {
        $this->markTestSkipped('Will be fixed in the future');
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
}
