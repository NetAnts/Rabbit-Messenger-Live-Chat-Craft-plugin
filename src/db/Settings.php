<?php
namespace NetAnts\WhatsRabbitLiveChat\db;

use yii\db\ActiveRecord;

class Settings extends ActiveRecord {
    public static function tableName(): String
    {
        return '{{%whatsrabbit_livechat_settings}}';
    }

}
