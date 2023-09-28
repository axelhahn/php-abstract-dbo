<?php
namespace axelhahn;

require_once 'pdo-db-base.class.php';

class pdo_db_attachments extends pdo_db_base{

    /**
     * hash for a table
     * create database column, draw edit form
     * @var array 
     */
    protected $_aProperties = [
        'filename'       => ['create' => 'varchar(255)','overview'=>1,],
        'mime'           => ['create' => 'varchar(32)',],
        'description'    => ['create' => 'varchar(2048)',],
        'size'           => ['create' => 'int',],
    ];

    public function __construct($oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}