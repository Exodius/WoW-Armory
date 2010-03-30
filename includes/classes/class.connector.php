<?php

/**
 * @package World of Warcraft Armory
 * @version Release Candidate 1
 * @revision 122
 * @copyright (c) 2009-2010 Shadez  
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 **/

if(!defined('__ARMORY__')) {
    die('Direct access to this file not allowed!');
}

Class Connector {
    
    /** Armory database handler **/
    public $aDB;
    
    /** Character database hanlder **/
    public $cDB;
    
    /** Realm/accounts database handler **/
    public $rDB;
    
    /** Mangos/world database handler **/
    public $wDB;
    
    /** MySQL connection configs **/
    public $mysqlconfig;
    
    /** Armory configs **/
    public $armoryconfig;
    
    /** Current armory locale (ru_ru, en_gb or es_es) **/
    public $_locale;
    
    /** Locale (0 - en_gb, 8 - ru_ru, etc.)**/
    public $_loc;
    
    /**
     * Initialize database & template handlers, sets up sql/site configs
     * @category Main system functions
     * @example Connector::__construct()
     * @return bool
     **/
    public function __construct() {
        if(!@include('configuration.php')) {
            die('<b>Error</b>: unable to load configuration file!');
        }
        if(!@require_once('libs/DbSimple/Generic.php')) {
            die('<b>Error</b>: unable to load database class!');
        }        
        $this->mysqlconfig  = $ArmoryConfig['mysql'];
        $this->armoryconfig = $ArmoryConfig['settings'];
        $this->aDB = DbSimple_Generic::connect('mysql://'.$this->mysqlconfig['user_armory'].':'.$this->mysqlconfig['pass_armory'].'@'.$this->mysqlconfig['host_armory'].'/'.$this->mysqlconfig['name_armory']);
        $this->cDB = DbSimple_Generic::connect('mysql://'.$this->mysqlconfig['user_characters'].':'.$this->mysqlconfig['pass_characters'].'@'.$this->mysqlconfig['host_characters'].'/'.$this->mysqlconfig['name_characters']);
        $this->rDB = DbSimple_Generic::connect('mysql://'.$this->mysqlconfig['user_realmd'].':'.$this->mysqlconfig['pass_realmd'].'@'.$this->mysqlconfig['host_realmd'].'/'.$this->mysqlconfig['name_realmd']);
        $this->wDB = DbSimple_Generic::connect('mysql://'.$this->mysqlconfig['user_mangos'].':'.$this->mysqlconfig['pass_mangos'].'@'.$this->mysqlconfig['host_mangos'].'/'.$this->mysqlconfig['name_mangos']);
        $this->aDB->query("SET NAMES ?", $this->mysqlconfig['charset_armory']);
        $this->cDB->query("SET NAMES ?", $this->mysqlconfig['charset_characters']);
        $this->rDB->query("SET NAMES ?", $this->mysqlconfig['charset_realmd']);
        $this->wDB->query("SET NAMES ?", $this->mysqlconfig['charset_mangos']);
        $this->_locale = (isset($_SESSION['armoryLocale'])) ? $_SESSION['armoryLocale'] : $this->armoryconfig['defaultLocale'];
        switch($this->_locale) {
            case 'en_gb':
            case 'en_us':
                $this->_loc = 0;
                break;
            case 'ru_ru':
                $this->_loc = 8;
                break;
        }
    }
}
?>