<?php

namespace Rabbit\RabbitMessengerLiveChat\migrations;

use Craft;
use craft\db\Migration;

/**
 * M250611145405AddingNewInputFields migration.
 */
class M250611145405AddingNewInputFields extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // Place migration code here...

        $this->addColumn(
            '{{%rabbit_messenger_livechat_settings}}',
            'show_information_form',
            $this->tinyInteger()->notNull()->defaultValue(1),
        );

        $this->addColumn(
            '{{%rabbit_messenger_livechat_settings}}',
            'starter_popup_timer',
            $this->integer()->notNull()->defaultValue(25),
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropColumn('{{%rabbit_messenger_livechat_settings}}', 'show_information_form');
        $this->dropColumn('{{%rabbit_messenger_livechat_settings}}', 'starter_popup_timer');
        return true;
    }
}
