<?php

namespace NetAnts\WhatsRabbitLiveChat\Service;

use Craft;
use craft\base\PluginInterface;
use NetAnts\WhatsRabbitLiveChat\Plugin;
use NetAnts\WhatsRabbitLiveChat\ValueObject\LiveChatConfig;
use yii\base\Module;

class SettingsService
{
    private const PLUGIN_REPO_DEV_URL = 'plugins-acceptance.whatsrabbit.com';
    private const PLUGIN_REPO_PROD_URL = 'plugins.whatsrabbit.com';

    public function __construct(
        private readonly Craft $craft,
    )
    {
    }

    public function saveSettings(PluginInterface $plugin, LiveChatConfig $liveChatConfig): bool
    {

        $this->craft::$app->plugins->savePluginSettings($plugin, [
            'apiKey' => $liveChatConfig->apiKey,
            'apiSecret' => $liveChatConfig->apiSecret,
            'pluginRepositoryDomain' => getenv('DEV_MODE') ? self::PLUGIN_REPO_DEV_URL : self::PLUGIN_REPO_PROD_URL,
            'liveChatWidgetLogo' => $liveChatConfig->liveChatWidgetLogo,
        ]);
        return true;
    }
}