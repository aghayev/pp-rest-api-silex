<?php

use Phinx\Migration\AbstractMigration;

class Product extends AbstractMigration
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
        $table = $this->table('product');
        $table->addColumn('number', 'string', array('limit' => 50))
            ->addColumn('seller_id', 'integer')
            ->addColumn('description', 'string', array('limit' => 100))
            ->addColumn('price', 'decimal')
            ->addColumn('created', 'datetime')
            ->addIndex(array('number'), array('unique' => true))
            ->create();
    }
}
