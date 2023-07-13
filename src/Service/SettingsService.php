<?php

namespace NetAnts\WhatsRabbitLiveChat\Service;

use Craft;
use craft\base\PluginInterface;
use NetAnts\WhatsRabbitLiveChat\Model\Settings;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;
use yii\base\Model;
use yii\base\Module;

class SettingsService
{
    private const PLUGIN_REPO_DEV_URL = 'plugins-acceptance.whatsrabbit.com';
    private const PLUGIN_REPO_PROD_URL = 'plugins.whatsrabbit.com';

    public function __construct(
        private readonly Craft $craft,
        private readonly AssetService $assetService,
    )
    {
    }

    public function saveSettings(PluginInterface $plugin, LiveChatConfig $liveChatConfig): bool
    {
//        $asset = $this->assetService->createAsset($liveChatConfig->avatarUrl);

        $this->craft::$app->plugins->savePluginSettings($plugin, [
            'apiKey' => $liveChatConfig->apiKey,
            'apiSecret' => $liveChatConfig->apiSecret,
            'pluginRepositoryDomain' => getenv('DEV_MODE') ? self::PLUGIN_REPO_DEV_URL : self::PLUGIN_REPO_PROD_URL,
            'avatarUrl' => $liveChatConfig->avatarUrl,
            'title' => $liveChatConfig->title,
            'description' => $liveChatConfig->description,
        ]);
        return true;
    }
}