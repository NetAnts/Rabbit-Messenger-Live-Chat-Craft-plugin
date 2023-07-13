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

        parent::init();
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