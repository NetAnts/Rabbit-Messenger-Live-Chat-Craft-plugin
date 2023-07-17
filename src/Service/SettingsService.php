<?php

namespace NetAnts\WhatsRabbitLiveChat\Service;

use Craft;
use craft\base\PluginInterface;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;

class SettingsService
{
    private const PLUGIN_REPO_DEV_URL = 'plugins-acceptance.whatsrabbit.com';
    private const PLUGIN_REPO_PROD_URL = 'plugins.whatsrabbit.com';
    public string $pluginRepoUrl;

    public function __construct(
        private Craft $craft,
    ) {
        $this->pluginRepoUrl = getenv('RW_LC_DEV_MODE') ? self::PLUGIN_REPO_DEV_URL : self::PLUGIN_REPO_PROD_URL;
    }

    public function saveSettings(PluginInterface|null $plugin, LiveChatConfig $liveChatConfig): bool
    {
        if (!$plugin) {
            return false;
        }
        return $this->craft::$app->plugins->savePluginSettings($plugin, [
            'apiKey' => $liveChatConfig->apiKey,
            'apiSecret' => $liveChatConfig->apiSecret,
            'pluginRepositoryDomain' => $this->pluginRepoUrl,
            'avatarAssetId' => $liveChatConfig->avatarAssetId,
            'title' => $liveChatConfig->title,
            'description' => $liveChatConfig->description,
            'whatsAppUrl' => $liveChatConfig->whatsAppUrl,
            'loginUrl' => $liveChatConfig->loginUrl,
        ]);
    }
}
