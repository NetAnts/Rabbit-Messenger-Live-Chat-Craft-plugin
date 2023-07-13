<?php

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

    public function init(): void
    {
        $this->controllerNamespace = 'NetAnts\\WhatsRabbitLiveChat\\Controller';

        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'url' => 'whatsrabbit-live-chat',
                    'label' => 'What\'sRabbit LiveChat',
                    'icon' => '@app/icons/envelope.svg',
                    'subnav' => [
                        [
                            'url' => 'whatsrabbit-live-chat',
                            'label' => 'Settings',
                            'settings' => $this->getSettings()
                        ]
                    ]
                ];
            }
        );


        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['whatsrabbit-live-chat/settings/save'] = 'whatsrabbit-live-chat/settings/save';
            }
        );

        /**
         * Register live chat hook and files
         */
        Craft::$app->view->hook('whatsrabbit-live-chat', [$this, 'getLiveChatWidget']);

        // Replace with prod url and files
        Craft::$app->getView()->registerCssFile("https://assets.plugins-acceptance.whatsrabbit.com/styles.css");
        Craft::$app->getView()->registerJsFile("https://assets.plugins-acceptance.whatsrabbit.com/polyfills.js");
        Craft::$app->getView()->registerJsFile("https://assets.plugins-acceptance.whatsrabbit.com/main.js");

        parent::init();
    }

    public function getLiveChatWidget(array &$context): string
    {
        $settings = Plugin::getInstance()->getSettings();

        return sprintf(
            '<whatsrabbit-live-chat-widget
                        avatar-url="%s"
                        login-url="%s"
                        whatsapp-url="%s"
                        welcome-title="%s"
                        welcome-description="%s"
                    ></whatsrabbit-live-chat-widget>',
            $settings['avatarUrl'],
            $settings['loginUrl'],
            $settings['whatsAppUrl'],
            $settings['title'],
            $settings['description']
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'whatsrabbit-live-chat/settings',
            ['settings' => $this->getSettings()]
        );
    }
}