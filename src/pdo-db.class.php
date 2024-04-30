<?php

/**
 * ======================================================================
 * 
 * INIT A PDO OBJECT
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

/**
 * class for a single PDO connection
 *
 * @author hahn
 */
class pdo_db
{

    /**
     * object of pdo database instance
     * @var object
     */
    public $db;


    /**
     * collected array of log messages
     * var @array
     */
    protected $_aLogmessages = [];

    /**
     * flag: show mysql errors and debug information?
     * @var boolean
     */
    protected $_bShowErrors = false;

    /**
     * flag: show mysql errors and debug information?
     * @var boolean
     */
    protected $_bDebug = false;

    protected $_iLastError = false;

    /**
     * executed queries and metadata or error
     * @var array
     */
    public $_aQueries = [];

    // ----------------------------------------------------------------------

    /**
     * sql statements for different database types
     * @var array
     */
    protected $_aSql = [
        'sqlite' => [
            "gettables"=>'SELECT name FROM sqlite_schema WHERE type = "table" AND name NOT LIKE "sqlite_%";',
            "getcreate"=>'SELECT sql FROM sqlite_master WHERE name = "%s" ',
            'tableexists' => "SELECT name FROM sqlite_schema WHERE type ='table' AND name = '%s';",

        ],
        'mysql' => [
            "gettables"=>'SHOW TABLES;',
            "getcreate"=>'SHOW CREATE TABLE %s"',
            'tableexists' => "SHOW TABLES LIKE '%s';"
        ]
    ];

    // ----------------------------------------------------------------------
    // CONSTRUCTOR
    // ----------------------------------------------------------------------

    /**
     * constructor - sets internal environment variables and checks existence 
     * of the database
     * @param  array $aOptions  array with these keys
     *                          - cfgfile {string} file name of config file for db connection
     *                          - db {array} db connection data for PDO with subkeys
     *                                        - dsn eg. 'mysql:host=addons-web-db;dbname=addons-web;charset=utf8'
     *                                              or 'sqlite:'.__DIR__.'/../../../protected/data/my-example-app.sqlite3'
     *                                        - user
     *                                        - password
     *                                        - options
     *                          - showdebug {bool} enable debug? default: false
     *                          - showerrors {bool} enable error messages? default: false
     * @return boolean
     */
    public function __construct($aOptions=[])
    {

        $sDbConfig = (isset($aOptions['cfgfile']) && is_file($aOptions['cfgfile']))
            ? $aOptions['cfgfile']
            : __DIR__ . '/pdo-db.config.php';

        $aDefaults = file_exists($sDbConfig) ? include $sDbConfig : [];

        if (isset($aOptions['showdebug'])) {
            $this->setDebug($aOptions['showdebug']);
        }
        if (isset($aOptions['showerrors'])) {
            $this->showErrors($aOptions['showerrors']);
        }

        if (isset($aOptions['db'])) {
            $aDefaults=$aOptions['db'];
        }

        return $this->setDatabase($aDefaults);
    }

    // ----------------------------------------------------------------------
    // PRIVATE FUNCTIONS
    // ----------------------------------------------------------------------

    /**
     * write debug output if enabled by flag
     * @param  string  $s       string to show
     * @param  string  $sTable  optional: table
     */
    public function _wd($s, $sTable = false)
    {
        if ($this->_bDebug) {
            echo '<div style="color: #888; background: #f8f8f8;">DEBUG: ' . ($sTable ? '{' . $sTable . '}' : '') . '  - ' . $s . "</div>" . PHP_EOL;
        }
        return true;
    }
    /**
     * add a log message for current object
     * @param  string  $sLevel    loglevel; one of inf|warn|error
     * @param  string  $sMethod   the method where the error occured
     * @param  string  $sMessage  the error message
     */
    public function _log($sLevel, $sTable, $sMethod, $sMessage)
    {
        $this->_aLogmessages[] = [
            'loglevel' => $sLevel,
            'table' => $sTable,
            'method' => $sMethod,
            'message' => $sMessage,
        ];
        if ($sLevel == 'error') {
            $this->_iLastError = count($this->_aLogmessages) - 1;
            if ($this->_bShowErrors) {
                echo '<div style="color: #a00; background: #fc2;">ERROR: [' . $sMethod . '] ' . $sMessage . "</div>" . PHP_EOL;
            }
        }
        return true;
    }

    // ----------------------------------------------------------------------
    // SETTER
    // ----------------------------------------------------------------------

    /**
     * create a PDO connection
     * @return bool
     */
    public function setDatabase($aOptions)
    {
        $this->db = false;

        // echo '<pre>'.print_r($aOptions, 1).'</pre>';
        if (!$aOptions || !is_array($aOptions)) {
            $this->_log('error', '[DB]', __METHOD__, 'To init a database you need an array as parameter.');
            return false;
        }

        $sDsn = '';
        if (!isset($aOptions['dsn'])) {
            $this->_log('error', '[DB]', __METHOD__, 'No key [dsn] was found in the options.');
            return false;
        } else {
            $sDsn = $aOptions['dsn'];
        }
        try {
            $this->_wd(__METHOD__ . " new PDO($sDsn,[...])");
            $this->db = new PDO(
                $sDsn,
                (isset($aOptions['user'])     ? $aOptions['user']     : NULL),
                (isset($aOptions['password']) ? $aOptions['password'] : NULL),
                (isset($aOptions['options'])  ? $aOptions['options']  : NULL)
            );
            $type = $this->driver();
            // If the database type is not supported, throw an exception
            if (!isset($this->_aSql[$type])) {
                throw new Exception("Ooops: " . __CLASS__ . " does not support db type [" . $type . "] yet :-/");
            }
    
        } catch (PDOException $e) {
            $this->_log('error', '[DB]', __METHOD__, 'Failed to initialize the database connection. PDO ERROR: ' . $e->getMessage());
            return false;
        }
        return true;
    }
    /**
     * enable/ disable debug; database error is visible on enabled debug only
     * @param  string|bool  $bNewValue  new debug mode; 0|false = off; any value=true
     * @return boolean
     */
    public function setDebug($bNewValue)
    {
        if ($this->_bDebug && !$bNewValue) {
            $this->_wd(__METHOD__ . " - Debug will be turned OFF.");
        }
        $this->_bDebug = !!$bNewValue;
        if ($bNewValue) {
            $this->_wd(__METHOD__ . " - Debug is now ON.");
        }
        return true;
    }
    /**
     * enable/ disable debug; show error message if they occur
     * @param  string|bool  $bNewValue  new debug mode; 0|false = off; any value=true
     * @return boolean
     */
    public function showErrors($bNewValue)
    {
        $this->_bShowErrors = !!$bNewValue;
        // echo(__METHOD__." - ShowErrors is now ".($this->_bShowErrors ? "ON" : "OFF"));
        $this->_wd(__METHOD__ . " - ShowErrors is now " . ($this->_bShowErrors ? "ON" : "OFF"));
        return true;
    }

    // ----------------------------------------------------------------------
    // GETTER
    // ----------------------------------------------------------------------

    /**
     * get name of the current driver, eg. "mysql" or "sqlite"
     * @return string
     */
    public function driver()
    {
        return $this->db ? $this->db->getAttribute(PDO::ATTR_DRIVER_NAME) : false;
    }

    /**
     * get the last error message.
     * @return string
     */
    public function error()
    {
        if ($this->_iLastError !== false) {
            return $this->_aLogmessages[$this->_iLastError]['message'];
        }
        return "";
    }

    /**
     * get the last query as array that can have these keys
     *   - method  {string}  name of the method that triggered the query
     *   - sql     {string}  executed sql query
     *   - data    {array}   optional: data array (when using prepare statement)
     *   - time    {float}   execution time in ms
     *   - records {integer} count of returned records on SELECT or affected rows on INSERT, UPDATE or DELETE
     *   - error   {string}  optional:PDO error message
     * @return array|bool
     */
    public function lastquery()
    {
        if (count($this->_aQueries)) {
            return $this->_aQueries[count($this->_aQueries) - 1];
        }
        return false;
    }

    /**
     * get an array with all log messages
     */
    public function logs()
    {
        return $this->_aLogmessages;
    }


    /**
     * get an array with all queries. Each entry can have these keys:
     *   - method  {string}  name of the method that triggered the query
     *   - sql     {string}  executed sql query
     *   - data    {array}   optional: data array (when using prepare statement)
     *   - time    {float}   execution time in ms
     *   - records {integer} count of returned records on SELECT or affected rows on INSERT, UPDATE or DELETE
     *   - error   {string}  optional:PDO error message
     * @return array
     */
    public function queries()
    {
        return $this->_aQueries;
    }


    // ----------------------------------------------------------------------
    // db functions
    // ----------------------------------------------------------------------


    /**
     * Check if a table exists in the current database.
     *
     * @param string $table Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     */
    function tableExists($table)
    {
        // Output debug information
        $this->_wd(__METHOD__);
        
        // Get the database type
        $type = $this->driver();

        // If the database type is not supported, throw an exception
        if (!isset($this->_aSql[$type]['tableexists'])) {
            throw new Exception("Ooops: " . __CLASS__ . " has no SQL for [tableexists] for type [" . $type . "] yet :-/");
        }

        // Check table
        $result = $this->makeQuery(sprintf($this->_aSql[$type]['tableexists'], $table, 1));
        return $result ? (bool)count($result) : false;
    }

    public function showTables(){
        // $_aTableList = $this->makeQuery($this->_aSql[$_sDriver]['gettables']);
        $type = $this->driver();
        // If the database type is not supported, throw an exception
        if (!isset($this->_aSql[$type]['gettables'])) {
            throw new Exception("Ooops: " . __CLASS__ . " has no SQL for [gettables] for type [" . $type . "] yet :-/");
        }

        // TODO: use makeQuery() to see it in log
        // difficulty: query result is incompatible FETCH_ASSOC
        $odbtables = $this->db->query($this->_aSql[$type]['gettables']);
        $_aTableList = $odbtables->fetchAll(PDO::FETCH_COLUMN);
        return $_aTableList;
    }
    /**
     * execute a sql statement and put metadata / error messages into the log
     * @param  string  $sSql   sql statement
     * @param  array   $aData  array with data items; if present prepare statement will be executed 
     * @param  string  $_table optional: table name to add to log
     * @return array|boolean
     */
    public function makeQuery($sSql, $aData = [], $_table='')
    {
        $this->_wd(__METHOD__ . " ($sSql, " . (count($aData) ? "DATA[" . count($aData) . "]" : "NODATA") . ")");
        $aLastQuery = ['method' => __METHOD__, 'sql' => $sSql];
        $_timestart = microtime(true);
        try {
            if (is_array($aData) && count($aData)) {
                $aLastQuery['data'] = $aData;
                $result = $this->db->prepare($sSql);
                $result->execute($aData);
            } else {
                $result = $this->db->query($sSql);
            }
            $aLastQuery['time'] = number_format((float)(microtime(true) - $_timestart) / 1000, 3);
        } catch (PDOException $e) {
            $aLastQuery['error'] = 'PDO ERROR: ' . $e->getMessage();
            $this->_log('error', $_table, __METHOD__, "{'.$_table.'} Query [$sSql] failed: " . $aLastQuery['error'] . ' See $DB->queries().');
            $this->_aQueries[] = $aLastQuery;
            return false;
        }
        $_aData = $result->fetchAll(PDO::FETCH_ASSOC);
        $aLastQuery['records'] = count($_aData) ? count($_aData) : $result->rowCount();
        
        $this->_aQueries[] = $aLastQuery;
        return $_aData;
    }

    /**
     * Dump a database to an array.
     * Optional it can write a json file to disk
     * 
     * @see import()
     * @param string $sOutfile  optional: output file name
     * @return mixed  array of data on success or false on error
     */
    public function dump($sOutfile=false){

        $aResult=[];
        $aResult['timestamp']=date("Y-m-d H:i:s");
        $aResult['driver']=$this->driver();
        $aResult['tables']=[];

        $this->_wd(__METHOD__);
        if (!$this->db){
            $this->_log('warning', '[DB]', __METHOD__, 'Cannot dump. Database was not set yet.');
            return false;
        }
        $_sDriver=$this->driver();
        if (!isset($this->_aSql[$_sDriver])){
            $this->_log('warning', '[DB]', __METHOD__, 'Cannot dump. Unknown database driver "'.$_sDriver.'".');
            return false;
        }

        // ----- get all tables

        $_aTableList = $this->showTables();
        if(!$_aTableList || !count($_aTableList)){
            $this->_log('warning', '[DB]', __METHOD__, 'Cannot dump. No tables were found.');
            return false;
        }
        // ----- read each table
        foreach($_aTableList as $sTablename){
            $this->_wd(__METHOD__.' Reading table '.$sTablename);
            $aResult[$sTablename]=[];

            $sSqlCreate=sprintf($this->_aSql[$this->driver()]['getcreate'], $sTablename, 1);
            $oCreate = $this->db->query($sSqlCreate);
            // $oCreate = $this->db->query('SELECT sql FROM sqlite_master');
            $aResult['tables'][$sTablename]['create']=$oCreate->fetchAll(PDO::FETCH_COLUMN)[0];

            $odbtables = $this->db->query('SELECT * FROM `' . $sTablename . '` ');
            $aResult['tables'][$sTablename]['data']=$odbtables->fetchAll(PDO::FETCH_ASSOC);
        }
        //print_r($aResult);

        // ----- optional: write to file
        if($sOutfile){
            $this->_wd(__METHOD__. ' Writing to '.$sOutfile);
            file_put_contents($sOutfile, json_encode($aResult, JSON_PRETTY_PRINT));
        }
        return $aResult;
    }

    /**
     * Import data from a json file; reverse function of dump()
     * @see dump()
     * @param  string   $sFile  json file
     * @return boolean
     */
    public function import($sFile){
        $this->_wd(__METHOD__);
        if (!$this->db){
            $this->_log('warning', '[DB]', __METHOD__, 'Cannot import. Database was not set yet.');
            return false;
        }
        if(!file_exists($sFile)){
            $this->_log('error', '[DB]', __METHOD__, 'Cannot import. Given file does nt extist ['.$sFile.'].');
            return false;
        }
        $aResult = json_decode(file_get_contents($sFile), true);
        if(!$aResult){
            $this->_log('warning', '[DB]', __METHOD__, 'Cannot import. No data in file.');
            return false;
        }

        // ----- read each table
        foreach($aResult['tables'] as $sTablename => $aTable){
            $this->_wd(__METHOD__.' Importing table '.$sTablename);

            // (1) if table exists then skip creation
            if ($this->tableExists($sTablename)) {
                $this->_log('info', '[DB]', __METHOD__, 'Table ['.$sTablename.'] already exists. Skipping.');
            } else {
                $sSql = $aTable['create'];
                $this->makeQuery($sSql);    
            }

            // (2) insert data item by item
            foreach($aTable['data'] as $aRow){

                $sSql = 'INSERT INTO `' . $sTablename . '` ('.implode(',',array_keys($aRow)).') VALUES (:'.implode(', :',array_keys($aRow)).');';
                $this->makeQuery($sSql, $aRow);
            }
        }
        return true;
    }
}

// ----------------------------------------------------------------------
