<?php

namespace NetAnts\WhatsRabbitLiveChat\Model;

use craft\base\Model;

class Settings extends Model
{
    public string $apiKey = '';
    public string $apiSecret = '';
    public string $pluginRepositoryDomain = '';
    public string $avatarUrl = '';
    public string $description = '';
    public string $title = '';

    public function defineRules(): array
    {
        return [
            [['apiKey', 'apiSecret', 'pluginRepositoryDomain', 'title', 'description'], 'required'],
        ];
    }
}