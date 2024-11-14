<?php

namespace Rabbit\RabbitMessengerLiveChat\migrations;

use Craft;
use craft\db\Migration;

/**
 * m241023_111944_add_expanded_and_collapsed_states migration.
 */
class M241023111944AddExpandedAndCollapsedStates extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        // Place migration code here...

        $this->addColumn(
            '{{%rabbit_messenger_livechat_settings}}',
            'desktop_expanded',
            $this->tinyInteger()->notNull()->defaultValue(1)->after('whatsapp_url'),
        );

        $this->addColumn(
            '{{%rabbit_messenger_livechat_settings}}',
            'mobile_collapsed',
            $this->tinyInteger()->notNull()->defaultValue(1)->after('whatsapp_url'),
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropColumn('{{%rabbit_messenger_livechat_settings}}', 'desktop_expanded');
        $this->dropColumn('{{%rabbit_messenger_livechat_settings}}', 'mobile_collapsed');
        return true;
    }
}
