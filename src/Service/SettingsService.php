<?php

namespace NetAnts\WhatsRabbitLiveChat\Service;

use Craft;
use craft\base\PluginInterface;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;

class SettingsService
{
    public string $pluginRepoUrl;

    public function __construct(
        private Craft $craft,
    ) {
        $this->pluginRepoUrl = getenv('PLUGIN_REPO_HOST') ?: Plugin::PLUGIN_REPO_PROD_URL;
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
        ]);
    }
}
