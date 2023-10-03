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

use PDO, PDOException;

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
     * @array
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

        if (isset($aOptions['showdebug'])) {
            $this->setDebug($aOptions['showdebug']);
        }
        if (isset($aOptions['showerrors'])) {
            $this->showErrors($aOptions['showerrors']);
        }
        $sDbConfig = (isset($aOptions['cfgfile']) && is_file($aOptions['cfgfile']))
            ? $aOptions['cfgfile']
            : __DIR__ . '/pdo-db.config.php';

        $aDefaults = file_exists($sDbConfig) ? include $sDbConfig : [];

        if (isset($aOptions['db'])) {
            $aDefaults=$aOptions['db'];
        }

        return $this->setDatabase($aDefaults);
    }

    // ----------------------------------------------------------------------
    // PRIVATE FUNCTIONS
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // SETTER
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
                (isset($aOptions['options'])  ? $aOptions['options'] : NULL)
            );
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
     * get name of the current driver, eg. "mysql"
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
     * - sql - {string} executed sql query
     * - data - {array} data array (when using prepare statement)
     * - time - {float} execution time in ms
     * - records - {integer} count of returned records
     * - error - {string} PDO error message
     * @return array
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
     * get an array with all queries
     * @return array
     */
    public function queries()
    {
        return $this->_aQueries;
    }
}

// ----------------------------------------------------------------------
