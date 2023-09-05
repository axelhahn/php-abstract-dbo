<?php
/**
 * ======================================================================
 * 
 * base class with database CRUD actions and other general methods for 
 * any custom database objects
 * 
 * ----------------------------------------------------------------------
 * 
 * TODO:
 * 
 * - validate values in method set() - WIP
 * - handle relations - WIP
 *     - update delete() and flush()
 * - better handling of errors - see  $this->_aLastError (set in 
 *   makeQuery()) vs $this->_sLastError
 * - More useful debugging _wd()
 * - detect change in table definitions
 * - find a sexy name
 * 
 * ----------------------------------------------------------------------
 * Author: Axel Hahn
 * Licence: GNU GPL 3.0
 * ----------------------------------------------------------------------
 * 2023-08-26  0.1  ah  first lines
 * ======================================================================
 */


namespace axelhahn;
use Exception, PDO, PDOException;

// for relation table
require_once 'pdo-db-relations.class.php';
require_once 'pdo-db-base.constants.php';

/**
 * class for basic CRUD actions
 *
 * @author hahn
 */
class pdo_db_base
{

    /**
     * name of the database table
     * @var string
     */
    protected $_table = false;

    /**
     * object of pdo database instance
     * @var object
     */
    private $_pdo;

    /**
     * a single object
     * @var array
     */
    protected $_aItem = [];

    /**
     * hash for a single announcement item with related data to
     * create database column, draw edit form
     * @var array 
     */
    protected $_aProperties = [];

    /**
     * relations of the current object
     * @var array
     */
    private $_relations = [];

    /**
     * default columns for each object type
     */
    protected $_aDefaultColumns = [
        'id'          => [
            'create' => 'INTEGER',
            'extra' =>  'primary key autoincrement',
            // 'label' => 'ID',                    'descr' => '', 'type' => 'hidden',         'edit' => false,
            'dummyvalue' => 'automatic'
        ],
        'timecreated' => ['create' => 'DATETIME', 'dummyvalue' => 'automatic'],
        'timeupdated' => ['create' => 'DATETIME', 'dummyvalue' => 'automatic'],
        'deleted'     => ['create' => 'INTEGER',  'dummyvalue' => '0'],
    ];

    /**
     * database types for create statement
     * links:
     * - https://www.sqlite.org/datatype3.html
     * - https://www.w3schools.com/mysql/mysql_datatypes.asp
     * 
     * @return array
     */
    private $_aDbTypes=[

    ];

    // ----------------------------------------------------------------------
    // CONSTRUCTOR
    // ----------------------------------------------------------------------

    /**
     * constructor - sets internal environment variables and checks existence 
     * of the database
     * @param  string $sObjectname  object name to generate a tablename from it
     * @param  string $sDbConfig    database config file
     * @return boolean
     */
    public function __construct($sObjectname, $oDB)
    {

        $this->_table = $this->_getTablename($sObjectname);

        $this->_pdo=$oDB;
        if (!$this->_tableExists($this->_table)) {
            $this->_wd(__METHOD__.' Need to create table.');
            $this->_createDbTable();
        }

        // generate item
        $this->_relations=($sObjectname == 'axelhahn\pdo_db_relations') ? NULL : [];
        $this->new();

        return true;
    }

    // ----------------------------------------------------------------------
    // PRIVATE FUNCTIONS
    // ----------------------------------------------------------------------

    /**
     * get a table name of a given class name
     * @see reverse function _getObjectFromTablename()
     * @param  string  $s      input string to generate a table name from
     * @return string
     */
    protected function _getTablename($s)
    {
        return basename(str_replace('\\', '/', $s));
    }
    /**
     * get a class name from a given table name
     * @see reverse function _getTablename()
     * @param  string  $s      input string to generate a table name from
     * @return string
     */
    protected function _getObjectFromTablename($s)
    {
        return __NAMESPACE__.'\\'.$s;
    }

    /**
     * write debug output if enabled by flag
     * @param  string  $s  string to show
     */
    protected function _wd($s)
    {
        return $this->_pdo->_wd($s, $this->_table);
    }
    /**
     * helper function to insert timestamp for creation and update
     * @return string
     */
    protected function _getCurrentTime()
    {
        return date("Y-m-d H:i:s");
    }


    /**
     * generate a hash for a new empty item
     * @return hash
     */
    public function new()
    {
        $this->_aItem = [];

        foreach ($this->_aDefaultColumns as $sKey => $aData) {
            $this->_aItem[$sKey] = $aData['dummyvalue'];
        }
        foreach (array_keys($this->_aProperties) as $sKey) {
            $this->_aItem[$sKey] = false;
        }
        $this->_relations=isset($this->_relations) ? [] : NULL;
        return true;
    }

    /**
     * Check if a table exists in the current database.
     *
     * @param string $table Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     */
    function _tableExists($table)
    {
        // Try a select statement against the table
        // Run it in try-catch in case PDO is in ERRMODE_EXCEPTION.
        $this->_wd(__METHOD__);
        $aSql = [
            'sqlite' => "SELECT name FROM sqlite_schema WHERE type ='table' AND name = '$table';",
        ];

        $type=$this->_pdo->driver();

        if (!isset($aSql[$type])) {
            die("Ooops: " . __CLASS__ . " does not support db type [" . $type . "] yet :-/");
        }

        $result = $this->makeQuery($aSql[$type]);
        return $result ? !!count($result) : false;
    }

    /**
     * execute a sql statement
     * @param  string  $sSql   sql statement
     * @param  array   $aData  array with data items; if present prepare statement will be executed 
     * @return array|boolean
     */
    public function makeQuery($sSql, $aData = [])
    {
        $this->_wd(__METHOD__ . " ($sSql, ".(count($aData) ? "DATA[".count($aData)."]" : "NODATA").")");
        $aLastQuery = ['method' => __METHOD__, 'sql' => $sSql];
        $_timestart = microtime(true);
        try {
            if (is_array($aData) && count($aData)) {
                $aLastQuery['data'] = $aData;
                $result = $this->_pdo->db->prepare($sSql);
                $result->execute($aData);
            } else {
                $result = $this->_pdo->db->query($sSql);
            }
            $aLastQuery['time'] = number_format((float)(microtime(true) - $_timestart) / 1000, 3);
        } catch (PDOException $e) {
            $aLastQuery['error'] = 'PDO ERROR: ' . $e->getMessage();
            $this->_log('error', __METHOD__, "{'.$this->_table.'} Query [$sSql] failed: ". $aLastQuery['error'].' See $DB->queries().');
            $this->_pdo->_aQueries[]=$aLastQuery;
            return false;
        }
        $_aData = $result->fetchAll(PDO::FETCH_ASSOC);
        $aLastQuery['records'] = count($_aData);
        $this->_pdo->_aQueries[]=$aLastQuery;
        return $_aData;
    }

    /**
     * create database table
     * @return bool
     */
    private function _createDbTable()
    {
        if ($this->_tableExists($this->_table)) {
            $this->_log(PB_LOGLEVEL_INFO, __METHOD__.'()', '{'.$this->_table.'} Table already exists');
            return true;
        }

        // db columns are default colums + columns for my object
        $sSql='';
        foreach (array_merge($this->_aDefaultColumns, $this->_aProperties) as $sCol => $aData) {
            if(isset($aData['create'])){
                $sSql .= ($sSql ? ', ' : '')
                    . "`$sCol` " . $aData['create']
                    .(isset($aData['extra']) ? ' '.$aData['extra'] : '')
                    ;
            }
        }
        $sSql = "CREATE TABLE \"" . $this->_table . "\" ($sSql);";
        $this->makeQuery($sSql);
        if ($this->_tableExists($this->_table)) {
            echo __METHOD__ . ' created table ' . $this->_table . '<br>';
            return true;
        }
        $this->_log(PB_LOGLEVEL_ERROR, __METHOD__.'()', 'Unable to create {'.$this->_table.'}.');
        return false;

        // TODO: verify columns
        // PRAGMA table_info(table_name);
    }

    /**
     * ALPHA ... work in progress or to delete
     */
    private function _verifyColumns(){

        $this->_wd(__METHOD__);
        $aSql=[
            'sqlite' => 'PRAGMA table_info('.$this->_table.')',
            'mysql' => 'describe "'.$this->_table.'"',
        ];

        /*

        https://www.sqlite.org/lang_altertable.html
        sqlite supports alter table on column:
            - rename
            - add
            - drop

        https://www.w3schools.com/SQl/sql_alter.asp
        Mysql also allows to change datatype    
        
        */

        $type=$this->_pdo->driver();
        if (!isset($aSql[$type])) {
            die("Ooops: " . __CLASS__ . " does not support db type [" . $type . "] yet :-/");
        }

        $result = $this->makeQuery($aSql[$type]);
        if(!$result || !count($result)){
            $this->_log(PB_LOGLEVEL_ERROR, __METHOD__, '{'.$this->_table.'} Unable to get table infos by sql query: ' . $aSql[$type].'');
            return false;
        }
        $aReturn=[];
        $aCols=[];

        // put names into key
        foreach($result as $aColumndef){
            $aCols[$aColumndef['name']]=$aColumndef;
        }
        $sAlter='';
        foreach ($this->_aProperties as $sColumn => $aData){
            if ($aData['create']!==$aCols[$sColumn]['type']){
                $aReturn[$sColumn]=[
                    'is'=>$aData['create'],
                    'must'=>$aCols[$sColumn]['type'],
                ];
            };
        }

        /*
        echo '<pre>'; 
        print_r($aReturn); 
        die();
        */
        return $result ? !!count($result) : false;

    }

    // ----------------------------------------------------------------------
    // DEBUGGING GETTER
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // DEBUGGING SETTER
    // ----------------------------------------------------------------------

    /**
     * add a log message for current object
     * @param  string  $sLevel    loglevel; one of inf|warn|error
     * @param  string  $sMethod   the method where the error occured
     * @param  string  $sMessage  the error message
     */
    protected function _log($sLevel, $sMethod, $sMessage){
        return $this->_pdo->_log($sLevel, $this->_table, $sMethod, $sMessage);
    }


    // ----------------------------------------------------------------------
    // CRUD ACTIONS
    // ----------------------------------------------------------------------


    /**
     * create a new entry
     * @param  array  $aItem  new announcement data
     * @return bool
     */
    public function create()
    {
        $this->_wd(__METHOD__);

        // prepare default columns
        unset($this->_aItem['id']);
        $this->_aItem['timecreated'] = $this->_getCurrentTime();
        $this->_aItem['timeupdated'] = NULL;
        $this->_aItem['deleted'] = 0;

        // create db entry
        $sSql = 'INSERT INTO `' . $this->_table . '` (`' . implode('`, `', array_keys($this->_aItem)) . '`) VALUES (:' . implode(', :', array_keys($this->_aItem)) . ');';
        $result = $this->makeQuery($sSql, $this->_aItem);
        if (is_array($result)) {
            $this->_aItem['id'] = $this->_pdo->db->lastInsertId();
            return true;
        }
        $this->_log('error', __METHOD__, 'Creation of new database entry {'.$this->_table.'} failed.');
        return false;
    }

    /**
     * read an entry by given id
     * @param  array  $aItem           new announcement data
     * @param  bool   $bReadRelations  read relation while loading object? default: false
     * @return bool
     */
    public function read($iId, $bReadRelations=false)
    {
        $this->new();
        $sSql = 'SELECT * from `' . $this->_table . '` WHERE `id`=' . (int)$iId.' and deleted=0';
        $result = $this->makeQuery($sSql);
        if (isset($result[0])) {
            $this->_aItem = $result[0];

            // read relation while loading object? 
            if($bReadRelations) {
                $this->_relRead();
            }

            /*
            Example query to read item with all relations in a single query:
                SELECT  relfrom.* , relto.*
                from objlangtexts o 
                left join pdo_db_relations as relfrom  on ( relfrom.from_id  =o.id and relfrom.from_table="objlangtexts")   
                left join pdo_db_relations as relto on ( relto.to_id  =o.id and relto.to_table="objlangtexts")   
                where o.id=(int)$iId
            */

            return true;
        }
        $this->_log('error', __METHOD__, 'Unable to read {'.$this->_table.'} item with id ['.$iId.'].');
        return false;
    }

    /**
     * update entry; the field "id" is required to identify a single row in the table
     * @param  array  $aItem  data with fields to modify
     * @return bool
     */
    public function update()
    {
        // prepare default columns
        $this->_aItem['timeupdated'] = $this->_getCurrentTime();

        // update existing db entry
        $sSql = '';
        foreach (array_keys($this->_aItem) as $sCol) {
            $sSql .= ($sSql ? ', ' : '') . "`$sCol` = :$sCol";
        }
        $sSql = 'UPDATE `' . $this->_table . '` ' . 'SET ' . $sSql . ' WHERE `id` = :id';
        $return = $this->makeQuery($sSql, $this->_aItem);
        if (is_array($return)) {
            return true;
        }
        return false;
    }

    /**
     * delete entry by a given id or current item
     * @param  integer  $iId   optional: id of the entry to delete; default: delete current item
     * @return bool
     */
    public function delete($iId = false)
    {
        $iId = (int)$iId ? (int)$iId : (int)$this->id();
        if ($iId) {
            if($this->relDeleteAll($iId)){

                $sSql = 'DELETE from `' . $this->_table . '` WHERE `id`=' . (int)$iId;
                $result = $this->makeQuery($sSql);
                if (is_array($result)) {
                    // TODO: delete relations
                    // - delete relations from_table+from_id
                    // - delete relations to_table+to_id
                    if ($iId == $this->id()) {
                        $this->new();
                    }
                    return true;
                } else {
                    $this->_log(PB_LOGLEVEL_ERROR, __METHOD__, '{'.$this->_table.'} Deletion if item with id ['.$iId.'] failed.');
                    return false;
                };
            } else {
                $this->_log(PB_LOGLEVEL_ERROR, __METHOD__, '{'.$this->_table.'} Deletion if relations for id ['.$iId.'] failed. Item was not deleted.');
            }
        }
        return false;
    }


    // ----------------------------------------------------------------------
    // ACTIONS
    // ----------------------------------------------------------------------

    /**
     * !!! DANGEROUS !!!
     * Drop table of current object type. It deletes all items of a type and
     * removes the schema from database
     * @return bool
     */
    public function flush(){
        // - delete relations from_table and to_table
        if (!$this->relFlush()){
            $this->_log(PB_LOGLEVEL_ERROR, __METHOD__, 'Unable to delete all relations.');
            return false;
        }
        $sSql = 'DROP TABLE IF EXISTS `' . $this->_table . '`';
        if(!is_array($this->makeQuery($sSql))){
            $this->_log(PB_LOGLEVEL_ERROR, __METHOD__, 'Unable to drop table [' . $this->_table . '].');
            return false;
        }
        return true;
    }

    /**
     * save item
     * @return array
     */
    public function save()
    {
        return $this->id()
            ? $this->update()
            : $this->create()
        ;
    }

    // ----------------------------------------------------------------------
    // RELATIONS
    // ----------------------------------------------------------------------

    /**
     * generate a key for a relation to another table and its id
     * @param  string   $sTable   target table
     * @param  integer  $iId      target id
     */
    protected function _getRelationKey($sToTable, $iToId){
        return $sToTable.':'.$iToId;
    }

    /**
     * generate a key for a relation to another table and its id
     * The tables here are sorted already (see _getRelationSortorder)
     * @param  string   $sFromTable  table name
     * @param  integer  $iFromId     table id
     * @param  string   $sToTable    second table
     * @param  integer  $iToId       second table id
     * @return string
     */
    protected function _getRelationUuid($sFromTable, $iFromId, $sToTable, $iToId){
        return md5( $sFromTable.':'.$iFromId .'-->'.$sToTable.':'.$iToId  );
    }

    /**
     * generate a relation item in the wanted sort order of given tables including uuid
     * The tables here are unsorted
     * @param  string   $sTable1  first table name
     * @param  integer  $iId1     first table id
     * @param  string   $sTable2  second table
     * @param  integer  $iId2     second table id
     * @return array
     */
    protected function _getRelationSortorder($sTable1, $iId1, $sTable2, $iId2){
        $aReturn=$sTable1<$sTable2
            ? [
                'from_table'       => $sTable1,
                'from_id'          => $iId1,
                'to_table'         => $sTable2,
                'to_id'            => $iId2,
            ]
            : [
                'from_table'       => $sTable2,
                'from_id'          => $iId2,
                'to_table'         => $sTable1,
                'to_id'            => $iId1,
            ];            
        $aReturn['uuid']=$this->_getRelationUuid( $aReturn['from_table'], $aReturn['from_id'], $aReturn['to_table'], $aReturn['to_id'] );
        return $aReturn;
    }

    protected function _addRelationToItem($aRelitem=[]){
        $this->_wd(__METHOD__.'()');
        if(!isset($this->_relations)){
            $this->_log(PB_LOGLEVEL_ERROR, __METHOD__, "Releations are not allowed for ". $this->_table);
            return false;
        }
        if(!isset($aRelitem['uuid'])){
            $this->_log(PB_LOGLEVEL_ERROR, __METHOD__, "Target item is no array or has no key [uuid]");
            return false;
        }
        $aTarget=$aRelitem['from_table']==$this->_table 
            ? [
                'to_table'=>$aRelitem['to_table'],
                'to_id'=>$aRelitem['to_id'],
            ] : [
                'to_table'=>$aRelitem['from_table'],
                'to_id'=>$aRelitem['from_id'],
            ]
            ;
        $sKey=$this->_getRelationKey($aTarget['to_table'], $aTarget['to_id']);
        $this->_relations[$sKey]=[
            'target'=>$aTarget,
            'db'=>$aRelitem,
        ];
    }
    /**
     * create a relation from the current item to an id of a target object
     * @param  string  $sToTable  target object
     * @param  string  $sToTable  target object
     */
    public function relCreate($sToTable, $iToId){
        $this->_wd(__METHOD__."($sToTable, $iToId)");
        if(!$this->id()){
            $this->_log('error', __METHOD__."($sToTable, $iToId)", '{'.$this->_table.'} The current item was not saved yet. We need an id in a table to create a relation with it.');
            return false;
        }
        if (!isset($this->_relations)){
            $this->_log('error', __METHOD__."($sToTable, $iToId)", "{'.$this->_table.'} The relation is disabled.");
            return false;
        }

        // helper function:
        $aTmp=$this->_getRelationSortorder($this->_table, $this->id(), $sToTable, $iToId);
        $sKey=$this->_getRelationKey($sToTable, $iToId);
        if (isset($this->_relations[$sKey])){
            $this->_log('error', __METHOD__."($sToTable, $iToId)", '{'.$this->_table.'} The relation already exists. It has the key [$sKey].');
            return false;
        }

        $this->_wd(__METHOD__." Creating new relation");
        $oRelation = new pdo_db_relations($this->_pdo);

        $oRelation->setItem($aTmp);
        if ($oRelation->save()) {
            $this->_addRelationToItem($aTmp);
            return true;
        }
        // print_r($this->error());
        $this->_log('error', __METHOD__."($sToTable, $iToId)", '{'.$this->_table.'} Unable to save relation '.print_r($aTmp, 1));
        return false;
    }

    /**
     * Method to read relations for the current object from relations table.
     * It sets the protected var $this->_relations.
     * This function is used in methods read() and relRead()
     * @return bool
     */
    protected function _relRead(){
        if (!isset($this->_relations)){
            return false;
        }
        $this->_relations=[];
        $oRelation = new pdo_db_relations($this->_pdo);
        $aRelations=$oRelation->search([
            'columns'=>'*',
            'where'=>'
                (`from_table`="'.$this->_table.'" AND `from_id`="'.$this->id().'")
                OR 
                (`to_table`="'.$this->_table.'" AND `to_id`="'.$this->id().'")
                AND `deleted`=0',
            'order'=>[
                'to_table ASC',
                'to_id ASC'
            ],
        ]);
        // $this->_aQueries[]=$oRelation->lastquery();
        if(is_array($aRelations) && count($aRelations)){
            foreach($aRelations as $aEntry){
                $aTmp=$this->_getRelationSortorder($aEntry['from_table'], $aEntry['from_id'], $aEntry['to_table'], $aEntry['to_id']);

                $sRelKey=$this->_getRelationKey($aTmp['to_table'], $aTmp['to_id']);
                $this->_relations[$sRelKey]=[
                    'table' => $aEntry['to_table'], 
                    'id' => $aEntry['to_id'],
                    '_relid' => $aEntry['id']
                ];
            }
        }
        return true;
    }

    /**
     * get relations of the current item
     * @return array
     */
    public function relRead(){
        $this->_wd(__METHOD__. '() reading relations for '.$this->_table.' item id '.$this->id());
        if(is_array($this->_relations) && !count($this->_relations)){
            $this->_relRead();
        }
        return $this->_relations;
    }
    /**
     * delete a single relation
     * @return array
     */
    public function relDelete($sRelKey){
        if(!isset($this->_relations[$sRelKey])){
            $this->_log('error', __METHOD__."($sRelKey)", '{'.$this->_table.'} The given key does not exist.');
            return false;
        }
        if(!isset($this->_relations[$sRelKey]['_relid'])){
            $this->_log('error', __METHOD__."($sRelKey)", '{'.$this->_table.'} The key [_relid] was not found.');
            return false;

        }
        $oRelation = new pdo_db_relations($this->_pdo);
        return $oRelation->delete($this->_relations[$sRelKey]['_relid']);
    }

    /**
     * delete all relations of a single item
     * called by delete(ID) before deleting the item itself
     * @param  integer  $iId  if of an item; default: false (=current item)
     */
    public function relDeleteAll($iId=false){
        $this->_wd(__METHOD__."($iId)");
        if(!isset($this->_relations)){
            return true;
        }
        if($iId && $iId!==$this->id()){
            $tmpItem=$this->_aItem;
            $tmpRel=$this->_relations;
            $this->read($iId, true);
        }

        foreach(array_keys($this->_relations) as $sRelKey ){
            if(!$this->relDelete($sRelKey)){
                if(isset($tmpItem)){
                    $this->_aItem=$this->_aItem;
                    $this->_relations=$tmpRel;
                }
                return false;
            };
        }
        if(isset($tmpItem)){
            $this->_aItem=$this->_aItem;
            $this->_relations=$tmpRel;
        }
        return true;
    }

    /**
     * delete all relations of type
     * called by flush() before deleting all items of a type
     * @return bool
     */
    public function relFlush(){
        $sSql = 'DELETE FROM `pdo_db_relations` WHERE `from_table`="'.$this->_table.'" OR `to_table`="'.$this->_table.'"';
        return is_array($this->makeQuery($sSql));
    }

    // ----------------------------------------------------------------------
    // GETTER
    // ----------------------------------------------------------------------
    /**
     * get count of existing items
     * @return integer
     */
    public function count()
    {
        $aTmp = $this->makeQuery('select count(id) as count from `' . $this->_table . '` WHERE deleted=0');
        return isset($aTmp[0]['count']) ? $aTmp[0]['count'] : 0;
    }
    /**
     * get id of the current item
     * @return integer
     */
    public function id()
    {
        return (int)$this->_aItem['id'] ? (int)$this->_aItem['id'] : false;
    }

    /**
     * get a single property of an item.
     * opposite function of set(KEY, VALUE)
     * @param  string  $sKey2Get  key of your object to set
     * @return *
     */
    public function get($sKey2Get)
    {
        if (array_key_exists($sKey2Get, $this->_aItem)) {
            return $this->_aItem[$sKey2Get];
        } else {
            return false;
        }
    }

    /**
     * get array of attribute names
     * @return array
     */
    public function getAttributes()
    {
        return array_keys($this->_aProperties);
    }

    /**
     * get current item
     * @return integer
     */
    public function getItem()
    {
        return $this->_aItem;
    }

    // ----------------------------------------------------------------------
    // SEARCH
    // ----------------------------------------------------------------------

    /**
     * search
     * @param  array  $aOptions  array with search options
     *                          - columns - array|string
     *                          - where   - array|string
     *                          - order   - array|string
     *                          - limit   - string
     * @return array
     */
    public function search($aOptions)
    {

        $sColumns = '';
        if (isset($aOptions['columns'])) {
            if (is_array($aOptions['columns'])) {
                $sColumns .= implode(",", $aOptions['columns']);
            }
            if (is_string($aOptions['columns'])) {
                $sColumns .= $aOptions['columns'];
            }
        } else {
            $sColumns .= '* ';
        }

        $sWhere = '';
        if (isset($aOptions['where'])) {
            if (is_array($aOptions['where']) && count($aOptions['where'])) {
                foreach ($aOptions['where'] as $sStatement) {
                    $sWhere .= $sStatement . ' ';
                }
            }
            if (is_string($aOptions['where']) && $aOptions['where']) {
                $sWhere .= $aOptions['where'] . ' ';
            }
        }
        $sOrder = '';
        if (isset($aOptions['order'])) {
            if (is_array($aOptions['order']) && count($aOptions['order'])) {
                foreach ($aOptions['order'] as $sStatement) {
                    $sOrder .= ($sOrder ? ', ' : '')
                        . $sStatement . ' ';
                }
                $sOrder = 'ORDER BY ' . $sOrder;
            }
            if (is_string($aOptions['order']) && $aOptions['order']) {
                $sOrder .= $aOptions['order'] . ' ';
            }
        }
        $sLimit = '';
        if (isset($aOptions['limit'])) {
            if (is_string($aOptions['limit']) && $aOptions['limit']) {
                $sLimit .= 'LIMIT ' . $aOptions['limit'] . ' ';
            }
        }

        $sSql = 'SELECT ' . $sColumns
            . ' FROM `' . $this->_table . '` '
            . ($sWhere ? 'WHERE ' . $sWhere . ' ' : '')
            . $sOrder
            . $sLimit;
        $result = $this->makeQuery($sSql);
        if (is_array($result) && count($result)) {
            return $result;
        }
        return false;
    }

    // ----------------------------------------------------------------------
    // SETTER
    // ----------------------------------------------------------------------

    /**
     * Set a single property of an item.
     * - The general fields (id, timecreated, timeupdated, delete) cannot be set.
     * - validate a field if validate_is set a tyoe
     * - validate a field if validate_regex set regex
     * Opposite function of get()
     * @param  string  $sKey2Set  key of your object to set
     * @param  any     $value     new value to set
     * @return integer
     */
    public function set($sKey2Set, $value)
    {
        if (isset($this->_aProperties[$sKey2Set])) {

            $_bValError=false;
            $_bValOK=true;

            // echo "-- validation for attribute '$sKey2Set' => '$value'<br>";

            if(isset($this->_aProperties[$sKey2Set]['validate_is'])){
                $sFunc="is_".$this->_aProperties[$sKey2Set]['validate_is'];
                // echo "Check $sFunc($value) ... ";
                switch($sFunc){
                    case 'is_string': 
                        // echo "found<br>";
                        $_bValOK=$_bValOK       && is_string($value);
                        $_bValError=$_bValError || !is_string($value);
                        break;
                    case 'is_integer': 
                        // echo "found<br>";
                        $_bValOK=$_bValOK       && is_integer($value);
                        $_bValError=$_bValError || !is_integer($value);
                        break;
                    default:
                        echo "ERROR: [$sFunc] is not supported yet.<br>";
                    }
            } else {
                // echo "Skip 'validate_is'<br>";
            }

            if(isset($this->_aProperties[$sKey2Set]['validate_regex'])){
                // echo "Check Regex ".$this->_aProperties[$sKey2Set]['validate_regex']."<br>";
                $_bValOK=$_bValOK       && preg_match($this->_aProperties[$sKey2Set]['validate_regex'], $value);
                $_bValError=$_bValError || !preg_match($this->_aProperties[$sKey2Set]['validate_regex'], $value);
            } else {
                // echo "Skip 'validate_regex'<br>";
            }

            // echo "--> OK: " .($_bValOK ? 'true':'false')." | Error: ".($_bValError ? 'true':'false')."<br>";
            if($_bValOK && !$_bValError){
                // echo "SET<br>";
                $this->_aItem[$sKey2Set] = $value;
                return true;
            } else {
                echo "SKIP '$sKey2Set' => '$value' -- validation failed<br>";
                $this->_log('warn', __METHOD__, '{'.$this->_table.'} value for '.$sKey2Set.' was not set because validaten failed');
            }
            
        } else {
            throw new Exception(__METHOD__ . " - ERROR: The key [$sKey2Set] cannot be set for [" . $this->_table . "].");
            return false;
        }
    }

    /**
     * set new values for an item.
     * The general fields (id, created, updated, delete) cannot be set.
     * Opposite function if getItem()
     * @return integer
     */
    public function setItem($aNewValues)
    {
        foreach (array_keys($aNewValues) as $sKey) {
            if (!isset($this->_aDefaultColumns[$sKey])) {
                $this->set($sKey, $aNewValues[$sKey]);
            }
        }
        // return $this->save();
        return true;
    }
}

// ----------------------------------------------------------------------
