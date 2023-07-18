<?php

namespace NetAnts\WhatsRabbitLiveChatTest\Service;

use Craft;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\Service\SettingsService;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;
use PHPUnit\Framework\TestCase;

class SettingsServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    private Craft | MockInterface $craft;
    private SettingsService $service;

    protected function setUp(): void
    {
        $this->craft = Mockery::mock(Craft::class);
        $this->service = new SettingsService(
            $this->craft,
        );
    }

    public function testSaveSettings(): void
    {
        $plugin = Mockery::mock(Plugin::class);
        $liveChatConfig = LiveChatConfig::createFromRequest([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => 'some-avatar-id',
            'whatsAppUrl' => 'https://wa.me',
            'loginUrl' => '',
        ]);
        $settingsModel = Mockery::mock(\craft\base\Model::class);
        $settingsModel->expects('setAttributes')->with([
            'apiKey' => $liveChatConfig->apiKey,
            'apiSecret' => $liveChatConfig->apiSecret,
            'pluginRepositoryDomain' => 'plugins.whatsrabbit.com',
            'avatarAssetId' => $liveChatConfig->avatarAssetId,
            'title' => $liveChatConfig->title,
            'description' => $liveChatConfig->description,
            'whatsAppUrl' => $liveChatConfig->whatsAppUrl,
            'loginUrl' => $liveChatConfig->loginUrl,
        ], false);
        $settingsModel->expects('validate')->andReturnTrue();
        $settingsModel->expects('toArray')->andReturn([]);
        $plugin->expects('getSettings')->andReturn($settingsModel)->times(3);
        $plugin->expects('beforeSaveSettings')->andReturnTrue();
        $plugin->expects('afterSaveSettings');
        $plugin->expects('has')->andReturnTrue()->twice();
        $plugin->expects('get')->andReturnNull()->twice();

        $response = $this->service->saveSettings($plugin, $liveChatConfig);
        $this->assertTrue($response);
    }

    public function testSaveSettingsWithoutPluginReturnsFalse(): void
    {
        $liveChatConfig = LiveChatConfig::createFromRequest([
            'apiKey' => 'some-api-key',
            'apiSecret' => 'some-api-secret',
            'title' => 'Some title',
            'description' => 'Some description',
            'avatarAssetId' => 'some-avatar-id',
            'whatsAppUrl' => 'https://wa.me',
            'loginUrl' => '',
        ]);

        $response = $this->service->saveSettings(null, $liveChatConfig);
        $this->assertFalse($response);
    }
}
