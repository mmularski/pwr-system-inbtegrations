<?php

namespace App\Inpost\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->upgradeVersionOneZeroOne($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param $setup
     */
    protected function upgradeVersionOneZeroOne(SchemaSetupInterface $setup)
    {
        $quoteTable = 'quote';
        $orderTable = 'sales_order';

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteTable),
                'inpost_name',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 50,
                    'comment' => 'InPost point name',
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'inpost_name',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'length' => 50,
                    'comment' => 'InPost point name',
                ]
            );
    }
}
