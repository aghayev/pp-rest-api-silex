<?php

use Phinx\Migration\AbstractMigration;

class Invoice extends AbstractMigration
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
        $table = $this->table('invoice');
        $table->addColumn('number', 'string', array('limit' => 50))
            ->addColumn('buyer_id', 'integer')
            ->addColumn('seller_id', 'integer')
            ->addColumn('status', 'integer')
            ->addColumn('created', 'datetime')
            ->addIndex(array('number'), array('unique' => true))
            ->addIndex(array('buyer_id', 'seller_id'), array('unique' => true))
            ->create();
    }
}
