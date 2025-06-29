<?php

namespace Rabbit\RabbitMessengerLiveChat\migrations;

use craft\db\Migration;

/**
 * m230808_090125_rabbit_messenger_livechat_settings migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->dropTableIfExists('{{%rabbit_messenger_livechat_settings}}');

        $this->createTable(
            '{{%rabbit_messenger_livechat_settings}}',
            [
                'id' => $this->primaryKey(),
                'avatar_asset_id' => $this->integer()->notNull(),
                'description' => $this->string()->notNull(),
                'whatsapp_url' => $this->string()->notNull(),
                'desktop_expanded' => $this->tinyInteger()->notNull()->defaultValue(1),
                'show_information_form' => $this->tinyInteger()->notNull()->defaultValue(1),
                'starter_popup_timer' => $this->integer()->defaultValue(25),
                'enabled' => $this->tinyInteger()->notNull()->defaultValue(1),
                'position' => $this->string()->defaultValue('fixed'),
                'z_index' => $this->string()->defaultValue('10'),
                'left' => $this->string()->defaultValue('inherit'),
                'right' => $this->string()->defaultValue('0'),
                'bottom' => $this->string()->defaultValue('0'),
                'top' => $this->string()->defaultValue('inherit'),
                'margin' => $this->string()->defaultValue('20px'),
            ]
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists('{{%rabbit_messenger_livechat_settings}}');
        return true;
    }
}
