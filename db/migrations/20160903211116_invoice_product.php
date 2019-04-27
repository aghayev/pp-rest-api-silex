<?php

use Phinx\Migration\AbstractMigration;

class InvoiceProduct extends AbstractMigration
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
        // create the table
        $table = $this->table('invoice_product');
        $table->addColumn('invoice_id', 'integer')
            ->addColumn('product_id', 'integer')
            ->addColumn('quantity', 'integer')
            ->addIndex(array('invoice_id', 'product_id'), array('unique' => true))
            ->create();
    }
}
