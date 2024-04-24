<?php

namespace Rabbit\RabbitMessengerLiveChat\db;

use yii\db\ActiveRecord;

class Settings extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%rabbit_messenger_livechat_settings}}';
    }
}
