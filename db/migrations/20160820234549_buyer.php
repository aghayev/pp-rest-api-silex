<?php

use Phinx\Migration\AbstractMigration;

class Buyer extends AbstractMigration
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
        $table = $this->table('buyer');
        $table->addColumn('account_number', 'string', array('limit' => 50))
            ->addColumn('name', 'string', array('limit' => 50))
            ->addColumn('email', 'string', array('limit' => 100))
            ->addColumn('address_line_1', 'string', array('limit' => 50))
            ->addColumn('address_line_2', 'string', array('limit' => 50))
            ->addColumn('address_line_3', 'string', array('limit' => 50))
            ->addColumn('post_code', 'string', array('limit' => 30))
            ->addColumn('telephone', 'string', array('limit' => 30))
            ->addColumn('vat', 'string', array('limit' => 100))
            ->addColumn('delivery_name', 'string', array('limit' => 50))
            ->addColumn('delivery_address_line_1', 'string', array('limit' => 50))
            ->addColumn('delivery_address_line_2', 'string', array('limit' => 50))
            ->addColumn('delivery_address_line_3', 'string', array('limit' => 50))
            ->addColumn('delivery_post_code', 'string', array('limit' => 30))
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', array('null' => true))
            ->addIndex(array('account_number'), array('unique' => true))
            ->addIndex(array('name', 'email'), array('unique' => true))
            ->create();
    }
}
