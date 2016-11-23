<?php

use yii\db\Migration;

class m160921_214555_structure extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%property2element_class}}', [
            'element_class_id' => $this->integer()->notNull(),
            'property_id'      => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%element_type}}', [
            'id'               => $this->primaryKey(),
            'name'             => $this->string(255)->notNull(),
            'sysname'          => $this->string(50),
            'element_class_id' => $this->integer()->notNull(),
            'variant_type_id'  => $this->smallInteger()->notNull(),
        ]);

        $this->createTable('{{%property_range}}', [
            'id'            => $this->bigPrimaryKey(),
            'name'          => $this->string(255)->notNull(),
            'property_id'   => $this->integer()->notNull(),
            'from_value_id' => $this->bigInteger()->notNull(),
            'to_value_id'   => $this->bigInteger()->notNull(),
        ]);

        $this->createTable('{{%property_array}}', [
            'id'          => $this->bigPrimaryKey(),
            'name'        => $this->string(255)->notNull(),
            'property_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%property_variant}}', [
            'id'              => $this->bigPrimaryKey(),
            'element_id'      => $this->bigInteger()->notNull(),
            'property_id'     => $this->integer()->notNull(),
            'value_table_id'  => $this->smallInteger()->notNull(),
            'value_id'        => $this->bigInteger()->notNull(),
            'element_type_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%relation_variant}}', [
            'id'                 => $this->bigPrimaryKey(),
            'element_id'         => $this->bigInteger()->notNull(),
            'relation_class_id'  => $this->integer()->notNull(),
            'related_element_id' => $this->bigInteger()->notNull(),
            'value'              => $this->float(),
            'property_unit_id'   => $this->integer(),
            'element_type_id'    => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%context}}', [
            'id'   => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
        ]);

        $this->createTable('{{%element_class}}', [
            'id'          => $this->primaryKey(),
            'context_id'  => $this->smallInteger()->notNull(),
            'name'        => $this->string(255)->notNull(),
            'description' => $this->text(),
            'sysname'     => $this->string(50)->notNull()->unique(),
        ]);

        $this->createTable('{{%element_category}}', [
            'id'              => $this->primaryKey(),
            'name'            => $this->string(255)->notNull(),
            'sysname'         => $this->string(50),
            'description'     => $this->text(),
            'parent_id'       => $this->integer(),
            'is_parent'       => $this->boolean()->defaultValue(false)->notNull(),
            'root_id'         => $this->integer(),
            'is_active'       => $this->boolean()->defaultValue(false)->notNull(),
            'element_type_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%property_type}}', [
            'id'        => $this->primaryKey(),
            'name'      => $this->string(50)->notNull()->unique(),
            'parent_id' => $this->smallInteger(),
        ]);

        $this->createTable('{{%property}}', [
            'id'               => $this->primaryKey(),
            'name'             => $this->string(255)->notNull()->unique(),
            'description'      => $this->text(),
            'property_unit_id' => $this->integer(),
            'is_specific'      => $this->boolean()->defaultValue(false)->notNull(),
            'property_type_id' => $this->smallInteger()->notNull(),
            'sysname'          => $this->string(50),
        ]);

        $this->createTable('{{%property_relation}}', [
            'id'             => $this->bigPrimaryKey(),
            'property_id'    => $this->integer()->notNull(),
            'element_id'       => $this->bigInteger()->notNull(),
            'value_table_id' => $this->smallInteger()->notNull(),
            'value_id'       => $this->bigInteger()->notNull(),
        ]);

        $this->createTable('{{%boolean_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->boolean()->notNull(),
        ]);

        $this->createTable('{{%int_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%bigint_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->bigInteger()->notNull(),
        ]);

        $this->createTable('{{%float_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->float()->notNull(),
        ]);

        $this->createTable('{{%string_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->string(255)->notNull(),
        ]);

        $this->createTable('{{%text_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->text()->notNull(),
        ]);

        $this->createTable('{{%date_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->date()->notNull(),
        ]);

        $this->createTable('{{%timestamp_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->timestamp()->notNull(),
        ]);

        $this->createTable('{{%geolocation_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%list_item_property_value}}', [
            'id'          => $this->bigPrimaryKey(),
            'property_id' => $this->integer()->notNull(),
            'value'       => $this->string(50)->notNull(),
            'label'       => $this->string(50)->notNull(),
        ]);

        $this->createTable('{{%element2element_class}}', [
            'element_class_id' => $this->integer()->notNull(),
            'element_id'       => $this->bigInteger()->notNull(),
        ]);

        $this->createTable('{{%element_class2relation_class}}', [
            'element_class_id'  => $this->integer()->notNull(),
            'relation_class_id' => $this->integer()->notNull(),
            'is_root'           => $this->boolean()->defaultValue(false)->notNull(),
        ]);

        $this->createTable('{{%element}}', [
            'id'                => $this->bigPrimaryKey(),
            'name'              => $this->string(255)->notNull(),
            'schema_element_id' => $this->bigInteger(),
            'is_active'         => $this->boolean()->defaultValue(false)->notNull(),
        ]);

        $this->createTable('{{%relation_class}}', [
            'id'               => $this->primaryKey(),
            'name'             => $this->string(255)->notNull()->unique(),
            'sysname'          => $this->string(50)->notNull()->unique(),
            'description'      => $this->text(),
            'relation_type_id' => $this->smallInteger()->notNull(),
        ]);

        $this->createTable('{{%relation_group}}', [
            'id'                => $this->primaryKey(),
            'name'              => $this->string(255)->notNull(),
            'relation_class_id' => $this->integer()->notNull(),
            'root_id'           => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%model}}', [
            'id'         => $this->primaryKey(),
            'element_id' => $this->bigInteger()->notNull(),
        ]);

        $this->createTable('{{%property_unit}}', [
            'id'   => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
        ]);

        $this->createTable('{{%relation}}', [
            'id'                => $this->bigPrimaryKey(),
            'relation_group_id' => $this->integer()->notNull(),
            'parent_element_id' => $this->bigInteger()->notNull(),
            'child_element_id'  => $this->bigInteger()->notNull(),
            'value'             => $this->float(),
            'property_unit_id'  => $this->integer(),
            'order'             => $this->smallInteger()->defaultValue(1000)->notNull(),
        ]);

        $this->addForeignKey('FK-relation-relation_group', '{{%relation}}', 'relation_group_id', '{{%relation_group}}', 'id');
        $this->createIndex('IX-relation-relation_group', '{{%relation}}', 'relation_group_id');
        $this->addForeignKey('FK-relation-parent_element', '{{%relation}}', 'parent_element_id', '{{%element}}', 'id');
        $this->createIndex('IX-relation-parent_element', '{{%relation}}', 'parent_element_id');
        $this->addForeignKey('FK-relation-child_element', '{{%relation}}', 'child_element_id', '{{%element}}', 'id');
        $this->createIndex('IX-relation-child_element', '{{%relation}}', 'child_element_id');
        $this->addForeignKey('FK-relation-property_unit', '{{%relation}}', 'property_unit_id', '{{%property_unit}}', 'id');
        $this->createIndex('IX-relation-property_unit', '{{%relation}}', 'property_unit_id');
        $this->createIndex('IX-relation-relation_group_id,child_element_id', '{{%relation}}', ['relation_group_id', 'child_element_id'], true);

        $this->addForeignKey('FK-relation_group-relation_class', '{{%relation_group}}', 'relation_class_id', '{{%relation_class}}', 'id');
        $this->createIndex('IX-relation_group-relation_class', '{{%relation_group}}', 'relation_class_id');
        $this->addForeignKey('FK-relation_group-element', '{{%relation_group}}', 'root_id', '{{%element}}', 'id');
        $this->createIndex('IX-relation_group-element', '{{%relation_group}}', 'root_id');
        $this->createIndex('IX-relation_group-relation_class_id-root_id', '{{%relation_group}}', ['relation_class_id', 'root_id'], true);

        $this->addColumn('{{%model}}', 'data', 'JSONB NOT NULL');
        $this->addForeignKey('FK-model-element', '{{%model}}', 'element_id', '{{%element}}', 'id');
        $this->createIndex('IX-model-element', '{{%model}}', 'element_id');

        $this->addForeignKey('FK-element-element', '{{%element}}', 'schema_element_id', '{{%element}}', 'id');
        $this->createIndex('IX-element-element', '{{%element}}', 'schema_element_id');
        $this->createIndex('IX-element-name-schema_element_id', '{{%element}}', ['name', 'schema_element_id'], true);

        $this->addPrimaryKey('PK-property2element_class-element_class_id,property_id', '{{%property2element_class}}', ['element_class_id', 'property_id']);
        $this->addForeignKey('FK-property2element_class-element_class', '{{%property2element_class}}', 'element_class_id', '{{%element_class}}', 'id');
        $this->createIndex('IX-property2element_class-element_class', '{{%property2element_class}}', 'element_class_id');
        $this->addForeignKey('FK-property2element_class-property', '{{%property2element_class}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-property2element_class-property', '{{%property2element_class}}', 'property_id');

        $this->alterColumn('{{%context}}', 'id', 'smallint');

        $this->addForeignKey('FK-element_class-context', '{{%element_class}}', 'context_id', '{{%context}}', 'id');
        $this->createIndex('IX-element_class-context', '{{%element_class}}', 'context_id');

        $this->addForeignKey('FK-element_category-element_type', '{{%element_category}}', 'element_type_id', '{{%element_type}}', 'id');
        $this->addForeignKey('FK-element_category-root', '{{%element_category}}', 'root_id', '{{%element_category}}', 'id');
        $this->createIndex('IX-element_category-element_type', '{{%element_category}}', 'element_type_id');

        $this->alterColumn('{{%property_type}}', 'id', 'smallint');

        $this->addForeignKey('FK-property-property_type', '{{%property}}', 'property_type_id', '{{%property_type}}', 'id');
        $this->createIndex('IX-property-property_type', '{{%property}}', 'property_type_id');
        $this->addForeignKey('FK-property-property_unit', '{{%property}}', 'property_unit_id', '{{%property_unit}}', 'id');
        $this->createIndex('IX-property-property_unit', '{{%property}}', 'property_unit_id');

        $this->addForeignKey('FK-element_type-element_class', '{{%element_type}}', 'element_class_id', '{{%element_class}}', 'id');
        $this->createIndex('IX-element_type-element_class', '{{%element_type}}', 'element_class_id');

        $this->addForeignKey('FK-property_range-property', '{{%property_range}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-property_range-property', '{{%property_range}}', 'property_id');
        $this->createIndex('IX-property_range-property_id-name', '{{%property_range}}', ['property_id', 'name'], true);
        $this->createIndex('IX-property_range-property_id-from_value_id-to_value_id', '{{%property_range}}', ['property_id', 'from_value_id', 'to_value_id'], true);

        $this->addColumn('{{%property_array}}', 'value_ids', 'int8[] NOT NULL');
        $this->addForeignKey('FK-property_array-property', '{{%property_array}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-property_array-property', '{{%property_array}}', 'property_id');
        $this->createIndex('IX-property_array-property_id-name', '{{%property_array}}', ['property_id', 'name'], true);
        $this->createIndex('IX-property_array-property_id-value_ids', '{{%property_array}}', ['property_id', 'value_ids'], true);

        $this->addForeignKey('FK-property_relation-property', '{{%property_relation}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-property_relation-property', '{{%property_relation}}', 'property_id');
        $this->addForeignKey('FK-property_relation-element', '{{%property_relation}}', 'element_id', '{{%element}}', 'id');
        $this->createIndex('IX-property_relation-element', '{{%property_relation}}', 'element_id');
        $this->createIndex('IX-property_relation-property_id,element_id', '{{%property_relation}}', ['property_id', 'element_id'], true);

        $this->addForeignKey('FK-property_variant-element', '{{%property_variant}}', 'element_id', '{{%element}}', 'id');
        $this->createIndex('IX-property_variant-element', '{{%property_variant}}', 'element_id');
        $this->addForeignKey('FK-property_variant-property', '{{%property_variant}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-property_variant-property', '{{%property_variant}}', 'property_id');
        $this->addForeignKey('FK-property_variant-element_type', '{{%property_variant}}', 'element_type_id', '{{%element_type}}', 'id');
        $this->createIndex('IX-property_variant-element_type', '{{%property_variant}}', 'element_type_id');
        $this->createIndex('IX-property_variant-element_id-property_id-value_table_id-value_id', '{{%property_variant}}', ['element_id', 'property_id', 'value_table_id', 'value_id'], true);

        $this->addForeignKey('FK-relation_variant-element', '{{%relation_variant}}', 'element_id', '{{%element}}', 'id');
        $this->createIndex('IX-relation_variant-element', '{{%relation_variant}}', 'element_id');
        $this->addForeignKey('FK-relation_variant-relation_class', '{{%relation_variant}}', 'relation_class_id', '{{%relation_class}}', 'id');
        $this->createIndex('IX-relation_variant-relation_class', '{{%relation_variant}}', 'relation_class_id');
        $this->addForeignKey('FK-relation_variant-related_element', '{{%relation_variant}}', 'related_element_id', '{{%element}}', 'id');
        $this->createIndex('IX-relation_variant-related_element', '{{%relation_variant}}', 'related_element_id');
        $this->addForeignKey('FK-relation_variant-property_unit', '{{%relation_variant}}', 'property_unit_id', '{{%property_unit}}', 'id');
        $this->createIndex('IX-relation_variant-property_unit', '{{%relation_variant}}', 'property_unit_id');
        $this->addForeignKey('FK-relation_variant-element_type', '{{%relation_variant}}', 'element_type_id', '{{%element_type}}', 'id');
        $this->createIndex('IX-relation_variant-element_type', '{{%relation_variant}}', 'element_type_id');
        $this->createIndex('IX-relation_variant-element_id-relation_class_id-related_element_id-value-property_unit_id', '{{%relation_variant}}', ['element_id', 'relation_class_id', 'related_element_id', 'value', 'property_unit_id'], true);

        $this->addForeignKey('FK-boolean_property_value-property', '{{%boolean_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-boolean_property_value-property', '{{%boolean_property_value}}', 'property_id');
        $this->createIndex('IX-boolean_property_value-property_id-value', '{{%boolean_property_value}}', ['property_id', 'value'], true);

        $this->addForeignKey('FK-int_property_value-property', '{{%int_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-int_property_value-property', '{{%int_property_value}}', 'property_id');
        $this->createIndex('IX-int_property_value-property_id-value', '{{%int_property_value}}', ['property_id', 'value'], true);

        $this->addForeignKey('FK-bigint_property_value-property', '{{%bigint_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-bigint_property_value-property', '{{%bigint_property_value}}', 'property_id');
        $this->createIndex('IX-bigint_property_value-property_id-value', '{{%bigint_property_value}}', ['property_id', 'value'], true);

        $this->addForeignKey('FK-float_property_value-property', '{{%float_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-float_property_value-property', '{{%float_property_value}}', 'property_id');
        $this->createIndex('IX-float_property_value-property_id-value', '{{%float_property_value}}', ['property_id', 'value'], true);

        $this->addForeignKey('FK-string_property_value-property', '{{%string_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-string_property_value-property', '{{%string_property_value}}', 'property_id');
        $this->createIndex('IX-string_property_value-property_id-value', '{{%string_property_value}}', ['property_id', 'value'], true);

        $this->addForeignKey('FK-text_property_value-property', '{{%text_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-text_property_value-property', '{{%text_property_value}}', 'property_id');
        $this->createIndex('IX-text_property_value-property_id-value', '{{%text_property_value}}', ['property_id', 'value'], true);

        $this->addForeignKey('FK-date_property_value-property', '{{%date_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-date_property_value-property', '{{%date_property_value}}', 'property_id');
        $this->createIndex('IX-date_property_value-property_id-value', '{{%date_property_value}}', ['property_id', 'value'], true);

        $this->addForeignKey('FK-timestamp_property_value-property', '{{%timestamp_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-timestamp_property_value-property', '{{%timestamp_property_value}}', 'property_id');
        $this->createIndex('IX-timestamp_property_value-property_id-value', '{{%timestamp_property_value}}', ['property_id', 'value'], true);

        $this->addColumn('{{%geolocation_property_value}}', 'value', 'point NOT NULL');
        $this->addForeignKey('FK-geolocation_property_value-property', '{{%geolocation_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-geolocation_property_value-property', '{{%geolocation_property_value}}', 'property_id');

        $this->addForeignKey('FK-list_item_property_value-property', '{{%list_item_property_value}}', 'property_id', '{{%property}}', 'id');
        $this->createIndex('IX-list_item_property_value-property', '{{%list_item_property_value}}', 'property_id');
        $this->createIndex('IX-list_item_property_value-property_id-value', '{{%list_item_property_value}}', ['property_id', 'value'], true);
        $this->createIndex('IX-list_item_property_value-property_id-label', '{{%list_item_property_value}}', ['property_id', 'label'], true);

        $this->addPrimaryKey('PK-element2element_class-element_class_id,element_id', '{{%element2element_class}}', ['element_class_id', 'element_id']);
        $this->addForeignKey('FK-element2element_class-element_class', '{{%element2element_class}}', 'element_class_id', '{{%element_class}}', 'id');
        $this->createIndex('IX-element2element_class-element_class', '{{%element2element_class}}', 'element_class_id');
        $this->addForeignKey('FK-element2element_class-element', '{{%element2element_class}}', 'element_id', '{{%element}}', 'id');
        $this->createIndex('IX-element2element_class-element', '{{%element2element_class}}', 'element_id');

        $this->addPrimaryKey('PK-element_class2relation_class-element_class_id,relation_class_id,is_root', '{{%element_class2relation_class}}', ['element_class_id', 'relation_class_id', 'is_root']);
        $this->addForeignKey('FK-element_class2relation_class-element_class', '{{%element_class2relation_class}}', 'element_class_id', '{{%element_class}}', 'id');
        $this->createIndex('IX-element_class2relation_class-element_class', '{{%element_class2relation_class}}', 'element_class_id');
        $this->addForeignKey('FK-element_class2relation_class-relation_class', '{{%element_class2relation_class}}', 'relation_class_id', '{{%relation_class}}', 'id');
        $this->createIndex('IX-element_class2relation_class-relation_class', '{{%element_class2relation_class}}', 'relation_class_id');
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->dropTable('{{%element_category}}');
        $this->dropTable('{{%property2element_class}}');
        $this->dropTable('{{%list_item_property_value}}');
        $this->dropTable('{{%property_range}}');
        $this->dropTable('{{%property_array}}');
        $this->dropTable('{{%boolean_property_value}}');
        $this->dropTable('{{%int_property_value}}');
        $this->dropTable('{{%bigint_property_value}}');
        $this->dropTable('{{%float_property_value}}');
        $this->dropTable('{{%string_property_value}}');
        $this->dropTable('{{%text_property_value}}');
        $this->dropTable('{{%date_property_value}}');
        $this->dropTable('{{%timestamp_property_value}}');
        $this->dropTable('{{%geolocation_property_value}}');
        $this->dropTable('{{%property_variant}}');
        $this->dropTable('{{%relation_variant}}');
        $this->dropTable('{{%property_relation}}');
        $this->dropTable('{{%property}}');
        $this->dropTable('{{%property_type}}');
        $this->dropTable('{{%element2element_class}}');
        $this->dropTable('{{%element_class2relation_class}}');
        $this->dropTable('{{%element_type}}');
        $this->dropTable('{{%element_class}}');
        $this->dropTable('{{%context}}');
        $this->dropTable('{{%relation}}');
        $this->dropTable('{{%property_unit}}');
        $this->dropTable('{{%relation_group}}');
        $this->dropTable('{{%model}}');
        $this->dropTable('{{%element}}');
        $this->dropTable('{{%relation_class}}');
    }
}
