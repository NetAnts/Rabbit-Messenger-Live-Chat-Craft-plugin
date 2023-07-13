<?php

namespace NetAnts\WhatsRabbitLiveChat\Model;

use craft\base\Model;

class Settings extends Model
{
    public string $apiKey = '';
    public string $apiSecret = '';
    public string $pluginRepositoryDomain = '';
    public string $liveChatWidgetLogo = '';

    public function defineRules(): array
    {
        return [
            [['apiKey', 'apiSecret', 'pluginRepositoryDomain'], 'required'],
        ];
    }
}