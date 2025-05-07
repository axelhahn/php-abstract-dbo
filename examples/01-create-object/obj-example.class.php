<?php

// ----- Step (1) 
//       Create an object by extending axelhahn\pdo_db_base

class objexample extends axelhahn\pdo_db_base{

    // ----- Step (2) 
    //       define properties of your object

    /**
     * @var array 
     */
    protected array $_aProperties = [
        'label'       => ['create' => 'TEXT',],
        'description' => ['create' => 'TEXT',],
    ];

    // ----- Step (3) 
    //       just use this constructor :-)

    public function __construct($oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}