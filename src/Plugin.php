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

        Craft::$app->view->hook('whatsrabbit-live-chat', [$this, 'getLiveChatWidget']);

        \Craft::$app->getView()->registerCssFile("http://localhost:4401/angular-dist/styles.css");
        \Craft::$app->getView()->registerJsFile("http://localhost:4401/angular-dist/runtime.js");
        \Craft::$app->getView()->registerJsFile("http://localhost:4401/angular-dist/polyfills.js");
        \Craft::$app->getView()->registerJsFile("http://localhost:4401/angular-dist/vendor.js");
        \Craft::$app->getView()->registerJsFile("http://localhost:4401/angular-dist/main.js");


        parent::init();
    }

    public function getLiveChatWidget(array &$context): string {
        return '<whatsrabbit-live-chat-widget
                        avatar-url="/rabbit-avatar.jpg"
                        login-url="http://localhost:4401/login-proxy.php"
                        whatsapp-url="https://wa.me/message/6UNK3375VBFLN1"
                        welcome-title="Rabbit | Hop into the future"
                        welcome-description="Heb je een vraag of wil je meer informatie? Neem dan contact met ons op via:"
                    ></whatsrabbit-live-chat-widget>';
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

    public static function getPluginInstance(): self
    {
        return parent::getInstance();
    }
}