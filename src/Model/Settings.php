<?php

namespace NetAnts\WhatsRabbitLiveChat\Model;

use craft\base\Model;

class Settings extends Model
{
    public string $apiKey = '';
    public string $apiSecret = '';
    public string $pluginRepositoryDomain = '';
    public int $avatarAssetId = 0;
    public string $description = '';
    public string $title = '';
    public string $whatsAppUrl = '';
}
