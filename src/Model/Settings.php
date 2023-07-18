<?php

namespace NetAnts\WhatsRabbitLiveChat\Model;

use craft\base\Model;

class Settings extends Model
{
    public string $apiKey = '';
    public string $apiSecret = '';
    public string $pluginRepositoryDomain = '';
    public array $avatarAssetId = [];
    public string $description = '';
    public string $title = '';
    public string $whatsAppUrl = '';
}
