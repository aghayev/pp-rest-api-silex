<?php

use Phinx\Migration\AbstractMigration;

class Image extends AbstractMigration
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
        $table = $this->table('image');
        $table->addColumn('seller_id','integer')
            ->addColumn('name', 'string', array('limit' => 255))
            ->addColumn('size', 'integer')
            ->addColumn('mime', 'string', array('limit' => 255))
            ->addColumn('status','integer')
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', array('null' => true))
            ->addIndex(array('name'), array('unique' => true))
            ->create();
    }
}
