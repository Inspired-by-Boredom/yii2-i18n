<?php
/**
 * @link https://github.com/Vintage-web-production/yii2-i18n
 * @copyright Copyright (c) 2017 Vintage Web Production
 * @license BSD 3-Clause License
 */

use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles creation of messages tables
 *
 * @author Aleksandr Zelenin <aleksandr@zelenin.me>
 * @since 1.0
 */
class m140609_093837_addI18nTables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        $sourceMessageTable = $i18n->sourceMessageTable;
        $messageTable = $i18n->messageTable;

        $this->createTable($sourceMessageTable, [
            'id' => Schema::TYPE_PK,
            'category' => Schema::TYPE_STRING,
            'message' => Schema::TYPE_TEXT
        ]);

        $this->createTable($messageTable, [
            'id' => Schema::TYPE_INTEGER,
            'language' => Schema::TYPE_STRING,
            'translation' => Schema::TYPE_TEXT
        ]);
        $this->addPrimaryKey('id', $messageTable, ['id', 'language']);
        $this->addForeignKey('fk_source_message_message', $messageTable, 'id', $sourceMessageTable, 'id', 'cascade', 'restrict');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable) || !isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }

        $this->dropTable($i18n->sourceMessageTable);
        $this->dropTable($i18n->messageTable);

        return true;
    }
}
