<?php

use Phinx\Migration\AbstractMigration;

class InvoiceUpdateIndices extends AbstractMigration
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
        $invoices = $this->table('invoice');
        $invoices->removeIndex(array('number'))
            ->removeIndex(array('buyer_id', 'seller_id'))
            ->addIndex(
                array('number'), // NOT unique
                array('name' => 'idx_number'))
            ->addIndex(
                array('seller_id', 'number'), // unique
                array('unique' => true, 'name' => 'idx_seller_id_number'))
            ->update();
    }
}
