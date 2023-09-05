<?php
namespace axelhahn;

require_once "../src/pdo-db-base.class.php";

class objexample extends pdo_db_base{

    /**
     * hash for a table
     * create database column, draw edit form
     * @var array 
     */
    protected $_aProperties = [
        // 'label'       => ['create' => 'TEXT',     'label' => 'Label',                 'descr' => '', 'type' => 'text',           'edit' => true, 'required' => true],
        // 'description' => ['create' => 'TEXT',     'label' => 'Beschreibung',          'descr' => '', 'type' => 'textarea',       'edit' => true],
        'label'       => ['create' => 'TEXT',],
        'description' => ['create' => 'TEXT',],
        # ^                            ^                      ^                                                 ^
        # db column                    sql create             label in editor                                   input type in editor
    ];

    public function __construct($oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}