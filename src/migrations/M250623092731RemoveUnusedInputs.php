<?php

namespace Rabbit\RabbitMessengerLiveChat\migrations;

use Craft;
use craft\db\Migration;

/**
 * M250623092731RemoveUnusedInputs migration.
 */
class M250623092731RemoveUnusedInputs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->dropColumn('{{%rabbit_messenger_livechat_settings}}', 'title');
        $this->dropColumn('{{%rabbit_messenger_livechat_settings}}', 'mobile_collapsed');
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->addColumn(
            '{{%rabbit_messenger_livechat_settings}}',
            'title',
            $this->string()->notNull()->after('avatar_asset_id')
        );
        $this->addColumn(
            '{{%rabbit_messenger_livechat_settings}}',
            'mobile_collapsed',
            $this->tinyInteger()->notNull()->defaultValue(1)->after('whatsapp_url')
        );
        return true;
    }
}
