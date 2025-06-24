<?php

namespace Rabbit\RabbitMessengerLiveChat\Model;

use craft\base\Model;

class DisplaySettings extends Model
{
    public ?int $avatarAssetId = null;
    public string $description = '';
    public string $whatsAppUrl = '';
    public bool $desktopExpanded = true;
    public bool $showInformationForm = true;
    public int $starterPopupTimer = 25;
    public bool $enabled = true;
    public string $position = 'fixed';
    public string $zIndex = '10';
    public string $left = 'inherit';
    public string $right = '0';
    public string $bottom = '0';
    public string $top = 'inherit';
    public string $margin = '20px';

    public function rules(): array
    {
        return [
            [['whatsAppUrl', 'description', 'avatarAssetId','starterPopupTimer'], 'required'],
            [['enabled', 'desktopExpanded', 'showInformationForm'], 'boolean'],
            [['position', 'zIndex', 'left', 'right', 'bottom', 'top', 'margin'], 'string']
        ];
    }
}
