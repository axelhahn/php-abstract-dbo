<?php
namespace axelhahn;

class pdo_db_relations extends pdo_db_base{

    /**
     * hash for a table
     * create database column, draw edit form
     * @var array 
     */
    protected $_aProperties = [
        // 'label'       => ['create' => 'TEXT',     'label' => 'Label',                 'descr' => '', 'type' => 'text',           'edit' => true, 'required' => true],
        'from_table'       => ['create' => 'TEXT',   ],
        'from_id'          => ['create' => 'INTEGER',],
        'to_table'         => ['create' => 'TEXT',   ],
        'to_id'            => ['create' => 'INTEGER',],
        'uuid'             => ['create' => 'TEXT NOT NULL UNIQUE',],
        'remark'           => ['create' => 'TEXT',   ],
        # ^                            ^                      ^                                                 ^
        # db column                    sql create             label in editor                                   input type in editor

    ];

    public function __construct($oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}