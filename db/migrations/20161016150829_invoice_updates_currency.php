<?php

use Phinx\Migration\AbstractMigration;

class InvoiceUpdatesCurrency extends AbstractMigration
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
        $invoice = $this->table('invoice');
        $invoice->addColumn('currency_id', 'integer')
        ->save();

        $product = $this->table('product');
        $product->addColumn('currency_id', 'integer')
        ->save();

        $invoiceProduct = $this->table('invoice_product');
        $invoiceProduct->addColumn('currency_id', 'integer')
        ->save();
    }
}
