<?php

declare(strict_types=1);

namespace NetAnts\WhatsRabbitLiveChat;

use Craft;
use craft\base\Model;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\Cp;
use craft\web\UrlManager;
use NetAnts\WhatsRabbitLiveChat\Model\Settings;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    public bool $hasCpSettings = true;

    public const PLUGIN_REPO_PROD_URL = 'plugins.whatsrabbit.com';

    public function init(): void
    {
        $this->controllerNamespace = 'NetAnts\\WhatsRabbitLiveChat\\Controller';

        /**
         * Register control panel menu items
         */
        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            [$this, 'addNavItem'],
        );

        /**
         * Register api route
         */
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            [$this, 'addRoute'],
        );

        /**
         * Register live chat hook and files
         */
        Craft::$app->view->hook('whatsrabbit-live-chat', [$this, 'getLiveChatWidget']);

        $pluginRepoUrl = getenv('PLUGIN_REPO_HOST') ?: self::PLUGIN_REPO_PROD_URL;
        Craft::$app->getView()->registerCssFile(sprintf('https://assets.%s/styles.css', $pluginRepoUrl));
        Craft::$app->getView()->registerJsFile(sprintf('https://assets.%s/polyfills.js', $pluginRepoUrl));
        Craft::$app->getView()->registerJsFile(sprintf('https://assets.%s/main.js', $pluginRepoUrl));
        parent::init();
    }

    public function addNavItem(RegisterCpNavItemsEvent $event): void
    {
        $event->navItems[] = [
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
    }

    public function addRoute(RegisterUrlRulesEvent $event): void
    {
        $event->rules['whatsrabbit-live-chat'] = 'login/getToken';
    }

    public function getLiveChatWidget(array &$context): string
    {
        $settings = $this->getSettings();

        $asset = Craft::$app->assets->getAssetById((int)$settings['avatarAssetId'][0]);

        return sprintf(
            '<whatsrabbit-live-chat-widget
                        avatar-url="%s"
                        login-url="%s"
                        whatsapp-url="%s"
                        welcome-title="%s"
                        welcome-description="%s"
                    ></whatsrabbit-live-chat-widget>',
            $asset?->url,
            '/actions/whatsrabbit-live-chat/login/get-token',
            $settings['whatsAppUrl'],
            $settings['title'],
            $settings['description']
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    // @codeCoverageIgnoreStart
    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'whatsrabbit-live-chat/settings',
            ['settings' => $this->getSettings()]
        );
    }
    // @codeCoverageIgnoreEnd
}
