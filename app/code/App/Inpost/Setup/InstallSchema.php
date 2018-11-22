<?php

namespace App\Inpost\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table checkout_agreement_customers
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('inpost_points')
        )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                ],
                'Entity id'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Name'
            )->addColumn(
                'type',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Type'
            )->addColumn(
                'status',
                Table::TYPE_TEXT,
                50,
                ['nullable' => true],
                'Status'
            )->addColumn(
                'latitude',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Latitude'
            )->addColumn(
                'longitude',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Longitude'
            )->addColumn(
                'opening_hours',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Opening hours'
            )->addColumn(
                'city',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'City'
            )->addColumn(
                'province',
                Table::TYPE_TEXT,
                50,
                ['nullable' => true],
                'Province'
            )->addColumn(
                'post_code',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Post code'
            )->addColumn(
                'street',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Street'
            )->addColumn(
                'building_number',
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Building number'
            )->addColumn(
                'flat_number',
                Table::TYPE_TEXT,
                50,
                ['nullable' => true],
                'Flat number'
            )->addColumn(
                'point_description',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Point description'
            )->addColumn(
                'location_description',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Location description'
            )->addColumn(
                'payment_available',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false],
                'Payment available'
            )->addColumn(
                'payment_type',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Payment type'
            )->addColumn(
                'to_delete',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false],
                'To delete flag'
            )
            ->setComment(
                'Table keeps information about inpost shipping points'
            );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
