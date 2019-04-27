<?php

use Phinx\Migration\AbstractMigration;

class InvoiceUpdates extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $users = $this->table('invoice');
        $users->addColumn('paid', 'enum', ['values' => ['0', '1']])
            ->addColumn('payment_method', 'enum', ['values' => ['bank','cash', 'card']])
            ->addColumn('payment_info', 'string', array('limit' => 255))
            ->addColumn('paid_at', 'datetime')
            ->addColumn('processed_at', 'datetime')
            ->addColumn('shipping_price', 'decimal')
            ->addColumn('coupon_code', 'string', array('limit' => 50))
            ->addColumn('coupon_value', 'decimal')
            ->addColumn('total_amount', 'decimal')
            ->addColumn('discount_percent', 'decimal')
            ->addColumn('paid_amount', 'decimal')
            ->addColumn('allow_refund', 'enum', ['values' => ['0', '1']])
            ->save();
    }
}
