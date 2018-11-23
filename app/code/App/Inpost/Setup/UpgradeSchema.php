<?php

namespace App\Inpost\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Config;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Config
     */
    protected $eavConfig;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * UpgradeSchema constructor.
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     */
    public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

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

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->upgradeVersionOneZeroTwo($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param $setup
     */
    protected function upgradeVersionOneZeroOne(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('customer_entity'),
            'inpost_point',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 32,
                'comment' => 'Customer Inpost point',
            ]
        );
    }

    /**
     * @param $setup
     */
    protected function upgradeVersionOneZeroTwo(SchemaSetupInterface $setup)
    {
        $orderTable = 'sales_order';

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'inpost_point',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 32,
                    'comment' => 'Customer Inpost point',
                ]
            );
    }
}
