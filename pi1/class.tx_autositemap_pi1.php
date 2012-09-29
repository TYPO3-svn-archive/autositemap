<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/



require_once(PATH_tslib.'class.tslib_pibase.php');

  // Define cases
define('TX_AUTOSITEMAP_PI1_MENUS_01',                 10 );
define('TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE',    11 );
define('TX_AUTOSITEMAP_PI1_MENUS_02',                 20 );
define('TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE',    21 );
define('TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE',    22 );
define('TX_AUTOSITEMAP_PI1_MENUS_03',                 30 );
define('TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE',    31 );
define('TX_AUTOSITEMAP_PI1_MENUS_04',                 40 );
define('TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE',    41 );
define('TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE',    42 );
define('TX_AUTOSITEMAP_PI1_MENUS_05',                 50 );
define('TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE',    51 );
define('TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE',    52 );
define('TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS',  100 );
define('TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK',    200 );
define('TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK',   300 );


/**
 * Plugin 'Login' for the 'autositemap' extension.
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  autositemap
 *
 * @version 0.0.4
 * @since 0.0.1
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   68: class tx_autositemap_pi1 extends tslib_pibase
 *
 *              SECTION: Main Process
 *  142:     public function main( $content, $conf )
 *
 *              SECTION: DRS - Development Reporting System
 *  203:     private function init_accessByIP( )
 *  232:     private function initDrs( )
 *
 *              SECTION: Flexform
 *  316:     private function initTypoScript( )
 *  335:     private function initTypoScriptSheetPowermail( )
 *
 *              SECTION: SOAP
 *  407:     private function soapUpdate( $content )
 *
 *              SECTION: Session
 *  537:     private function sessionSetForPowermail( $content )
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_autositemap_pi1 extends tslib_pibase
{

 /**
  * Class name
  *
  * @var string
  */
  public $prefixId  = 'tx_autositemap_pi1';

 /**
0  * Path to this script relative to the extension directory
  *
  * @var string
  */
  public $scriptRelPath  = 'pi1/class.tx_autositemap_pi1.php';

 /**
  * Extension key
  *
  * @var string
  */
  public $extKey = 'autositemap';

 /**
  * Configuration by the extension manager
  *
  * @var array
  */
  private $arr_extConf;

 /**
  * Current IP is met allowed IPs
  *
  * @var boolean
  */
  private $bool_accessByIP;

 /**
  * Current TypoScript configuration
  *
  * @var array
  */
  public $conf;
  
 /**
  * Structure of the pagetree (ids)
  *
  * @var array
  */
  private $pagetreeStructure;







  /***********************************************
   *
   * Main Process
   *
   **********************************************/



/**
 * main( ): Main method of your PlugIn
 *
 * @param    string        $content: The content of the PlugIn
 * @param    array        $conf: The PlugIn Configuration
 * @return    string        The content that should be displayed on the website
 * @version 0.0.2
 * @since   0.0.1
 */
  public function main( $content, $conf )
  {
      // Globalise TypoScript configuration
    $this->conf = $conf;
      // Init localisation
    $this->pi_loadLL();
      // Get the values from the localconf.php file
    $this->arr_extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey] );
    
      // RETURN : requirements methods failed
    $arr_return = $this->requirements( );
    if( $arr_return['error']['status'] )
    {
      $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
      return $this->pi_wrapInBaseClass( $content );
    }
      // RETURN : requirements methods failed
    
      // RETURN : init methods failed
    $arr_return = $this->init( );
    if( $arr_return['error']['status'] )
    {
      $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
      return $this->pi_wrapInBaseClass( $content );
    }
      // RETURN : init methods failed
    
      // RETURN : Rendering of the sitemap failed
    $arr_return = $this->sitemap( );
    if( $arr_return['error']['status'] )
    {
      $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
      return $this->pi_wrapInBaseClass( $content );
    }
      // RETURN : Rendering of the sitemap failed

      // RETURN : content (the sitemap)
    $content = $arr_return['data']['content'];
    return $this->pi_wrapInBaseClass( $content );
  }






  /***********************************************
   *
   * Calculators
   *
   **********************************************/



/**
 * calc( ): Set global vars 
 *
 * @return    array        $arr_return  : data[columns] contain the columns
 * @version 0.0.2
 * @since   0.0.2
 */
  private function calc( )
  {
      // Set priority
    $this->calcPriority( );
      // Set totalNumberOfMainMenus
    $this->calcTotalNumberOfMainMenus( );
      // Set $sumOfLevels
    $this->calcSumOfLevels( );
      // Set $greatestMenu
    $this->calcGreatestMenu( );
      // Set $menuIdForLineBreak
    $this->calcMenusWithLinebreak( );
      // Set $menusForCase
    $this->calcMenusForCase( );
      // Set columnsCase
    $this->calcCase( );
      // Set excludeUidLists
    $this->calcExcludeUidList( );
  }



/**
 * calcCase( ):
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.2
 */
  private function calcCase( )
  {
      // SWITCH : priority
    switch( $this->priority )
    {
      case( TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS ):
        $this->calcCasePriorityMaxlinebreaks( );
        break;
      case( TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK ):
        $this->calcCasePriorityNolinebreak( );
        break;
      case( TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK ):
        $this->calcCasePriorityOnelinebreak( );
        break;
    }
      // SWITCH : priority
  }



/**
 * calcCasePriorityDRS( ):
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.3
 */
  private function calcCasePriorityDRS( )
  {
      // RETURN : DRS is disabled
    if( ! $this->b_drs_calc )
    {
      return;
    }
      // RETURN : DRS is disabled
    
      // SWITCH : case
    switch( $this->case )
    {
      case( TX_AUTOSITEMAP_PI1_MENUS_01 ):
        $prompt = 'Sum of menus is #1. ' .
                  'There is one menu for a single column only. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_01';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE ):
        $prompt = 'Sum of menus is #1. ' .
                  'There is one menu for a double column only. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_02 ):
        $prompt = 'Sum of menus are #2. ' .
                  'There are two menus. Both are for a single column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_02';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE ):
        $prompt = 'Sum of menus are #2. ' .
                  'There are two menus. One is for a single column, the other is for a double coloum. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE ):
        $prompt = 'Sum of menus are #2. ' .
                  'There are two menus. Both are for a double column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_03 ):
        $prompt = 'Sum of menus are #3. ' .
                  'There are three menus. All three are for a single column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_03';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE ):
        $prompt = 'Sum of menus are #3. ' .
                  'There are three menus. One menu is for a double column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_04 ):
        $prompt = 'Sum of menus are #4. ' .
                  'There are four menus. All four are for a single column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_04';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE ):
        $prompt = 'Sum of menus are #4. ' .
                  'There are four menus. Two of the first three menus are for a single column. ' . 
                  'One of the first three menus is for a double column. ' .
                  'The forth menu is for the fifth column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE ):
        $prompt = 'Sum of menus are #4. ' .
                  'There are four menus. The first two menus are for double columns. ' . 
                  'Menus from the third menu are for the fifth column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_05 ):
        $prompt = 'Sum of menus are #5 at least. ' .
                  'There are five menus at least. The first four are for the first four single columns. ' .
                  'All others will rendered in the fifth column - the margin column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_05';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE ):
        $prompt = 'Sum of menus are #5 at least. ' .
                  'There are five menus at least. Two of the first three menus are for a single column. ' . 
                  'One of the first three menus is for a double column. ' .
                  'Menus from the forth menu are for the fifth column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE ):
        $prompt = 'Sum of menus are #5 at least. ' .
                  'There are five menus at least. The first two menus are for double columns. ' . 
                  'Menus from the third menu are for the fifth column. ' .
                  'Internal case is TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE';
        t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
        break;
    }
      // SWITCH : case
      
  }



/**
 * calcCasePriorityMaxlinebreaks( ):
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.3
 */
  private function calcCasePriorityMaxlinebreaks( )
  {
      // DIE: method is called with wrong priority
    if( $this->priority != TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS )
    {
      $prompt = 'ERROR: Don\'t call this method, if priority is  TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS. <br />' .
                'It doesn\'t make any sense! <br />' . 
                '<br />' .
                'TYPO3 autositemap ' . __METHOD__ . ' (line: ' . __LINE__ . ').';
      die( $prompt );
    }
      // DIE: method is called with wrong priority

      // DRS
    if( $this->b_drs_calc )
    {
      $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS. ';
      t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
    }
      // DRS
    
      // Default is 4 main columns and 1 margin column
    $this->case = TX_AUTOSITEMAP_PI1_MENUS_05;
    
      // SWITCH : totalNumberOfMainMenus. Set the case.
    switch( true )
    {
      case( $this->totalNumberOfMainMenus == 1 ):
        switch( true )
        {
          case( ! empty( $this->greatestMenu['idForLineBreak'] ) ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE;
            break;
          default:
            $this->numberOfMenusForMainColumns = 1;
            break;
        }
        $this->numberOfMenusForMainColumns = 1;
        break;
      case( $this->totalNumberOfMainMenus == 2 ):
        switch( true )
        {
          case( count ( ( array ) $this->menusWithLinebreak ) == 2 ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE;
            break;
          case( ! empty( $this->greatestMenu['idForLineBreak'] ) ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE;
            break;
          default:
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_02;
            break;
        }
        $this->numberOfMenusForMainColumns = 2;
        break;
      case( $this->totalNumberOfMainMenus == 3 ):
        switch( true )
        {
          case( ! empty( $this->greatestMenu['idForLineBreak'] ) ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE;
            break;
          default:
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_03;
            break;
        }
        $this->numberOfMenusForMainColumns = 3;
        break;
      case( $this->totalNumberOfMainMenus == $this->tsRulesNumberofcolumnsMain ):
        switch( true )
        {
          case( $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak ):
          case( $this->oneOfTheFirstThreeMenusIsTheGreatest ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE;
            $this->numberOfMenusForMainColumns = 3;
            break;
          case( $this->theFirstTwoMenusAreMenusWithLineBreak ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE;
            $this->numberOfMenusForMainColumns = 2;
            break;
          default:
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_04;
            $this->numberOfMenusForMainColumns = 4;
            break;
        }
        break;
      case( $this->totalNumberOfMainMenus > $this->tsRulesNumberofcolumnsMain ):
      default:
        switch( true )
        {
          case( $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE;
            $this->numberOfMenusForMainColumns = 3;
            break;
          case( $this->theFirstTwoMenusAreMenusWithLineBreak ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE;
            $this->numberOfMenusForMainColumns = 2;
            break;
          default:
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_05;
            $this->numberOfMenusForMainColumns = 4;
            break;
        }
        break;
    }
      // SWITCH : totalNumberOfMainMenus. Set the case.
      
      // DRS
    $this->calcCasePriorityDRS( );
  }



/**
 * calcCasePriorityNolinebreak( ):
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.3
 */
  private function calcCasePriorityNolinebreak( )
  {
      // DIE: method is called with wrong priority
    if( $this->priority != TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK )
    {
      $prompt = 'ERROR: Don\'t call this method, if priority is  TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. <br />' .
                'It doesn\'t make any sense! <br />' . 
                '<br />' .
                'TYPO3 autositemap ' . __METHOD__ . ' (line: ' . __LINE__ . ').';
      die( $prompt );
    }
      // DIE: method is called with wrong priority

      // calcCasePriorityNolinebreak is similar to calcCasePriorityOnelinebreak
    $this->priority = TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK;
    $this->calcCasePriorityOnelinebreak( );
    $this->priority = TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK;
    
      // SWITCH : case
    switch( $this->case )
    {
      case( TX_AUTOSITEMAP_PI1_MENUS_01 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_03 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05 ):
          // No line break, any recalculating isn't needed
          // DRS
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'And no column contains a double column. Internal case won\'t recalculated.';
          t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, 0 );
        }
          // DRS
        return;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_01.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_01;
        $this->numberOfMenusForMainColumns = 1;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_02.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_02;
        $this->numberOfMenusForMainColumns = 2;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_02.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_02;
        $this->numberOfMenusForMainColumns = 2;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_03.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_03;
        $this->numberOfMenusForMainColumns = 3;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_04.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_04;
        $this->numberOfMenusForMainColumns = 4;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_04.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_04;
        $this->numberOfMenusForMainColumns = 4;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_05.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_04;
        $this->numberOfMenusForMainColumns = 4;
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE ):
        if( $this->b_drs_calc )
        {
          $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK. ' . 
                    'Double columns aren\'t welcome. ' . 
                    'Internal case is set from TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE to ' . 
                    'TX_AUTOSITEMAP_PI1_MENUS_05.';
          t3lib_div::devlog(' [WARN/CALC] '. $prompt, $this->extKey, 2 );
        }
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_04;
        $this->numberOfMenusForMainColumns = 4;
        break;
    }
      // SWITCH : case
  }



/**
 * calcCasePriorityOnelinebreak( ):
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.3
 */
  private function calcCasePriorityOnelinebreak( )
  {
      // DIE: method is called with wrong priority
    if( $this->priority != TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK )
    {
      $prompt = 'ERROR: Don\'t call this method, if priority isn\'t  TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK. <br />' .
                'It doesn\'t make any sense! <br />' . 
                '<br />' .
                'TYPO3 autositemap ' . __METHOD__ . ' (line: ' . __LINE__ . ').';
      die( $prompt );
    }
      // DIE: method is called with wrong priority

      // DRS
    if( $this->b_drs_calc )
    {
      $prompt = 'Priority is TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK. ' . 
                'This hasn\'t any effect to the internal case.';
      t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, -1 );
    }
      // DRS
    
      // Default is 4 main columns and 1 margin column
    $this->case = TX_AUTOSITEMAP_PI1_MENUS_05;
    
      // SWITCH : totalNumberOfMainMenus. Set the case.
    switch( true )
    {
      case( $this->totalNumberOfMainMenus == 1 ):
        switch( true )
        {
          case( ! empty( $this->greatestMenu['idForLineBreak'] ) ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE;
            break;
          default:
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_01;
            break;
        }
        $this->numberOfMenusForMainColumns = 1;
        break;
      case( $this->totalNumberOfMainMenus == 2 ):
        switch( true )
        {
          case( count ( ( array ) $this->menusWithLinebreak ) == 2 ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE;
            break;
          case( ! empty( $this->greatestMenu['idForLineBreak'] ) ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE;
            break;
          default:
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_02;
            break;
        }
        $this->numberOfMenusForMainColumns = 2;
        break;
      case( $this->totalNumberOfMainMenus == 3 ):
        switch( true )
        {
          case( ! empty( $this->greatestMenu['idForLineBreak'] ) ):
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE;
            break;
          default:
            $this->case = TX_AUTOSITEMAP_PI1_MENUS_03;
            break;
        }
        $this->numberOfMenusForMainColumns = 3;
        break;
      case( $this->totalNumberOfMainMenus == $this->tsRulesNumberofcolumnsMain ):
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_04;
        $this->numberOfMenusForMainColumns = 4;
        break;
      case( $this->totalNumberOfMainMenus > $this->tsRulesNumberofcolumnsMain ):
      default:
        $this->case = TX_AUTOSITEMAP_PI1_MENUS_05;
        $this->numberOfMenusForMainColumns = 4;
        break;
    }
      // SWITCH : totalNumberOfMainMenus. Set the case.
      
      // DRS
    $this->calcCasePriorityDRS( );
  }
  
  
  
/**
 * calcPriority( ):
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.3
 */
  private function calcPriority( )
  {
      // Default is 4 main columns and 1 margin column
    $this->priority = TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK;
    
      // SWITCH : tsRulesPriority. Set the case.
    switch( true )
    {
      case( $this->tsRulesPriority == 'oneLineBreak' ):
        $this->priority = TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK;
        break;
      case( $this->tsRulesPriority == 'maxLineBreaks' ):
        $this->priority = TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS;
        break;
      case( $this->tsRulesPriority == 'noLineBreak' ):
        $this->priority = TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK;
        break;
    }
      // SWITCH : tsRulesPriority. Set the case.
      
      // DRS
    if( $this->b_drs_calc )
    {
        // SWITCH : case
      switch( $this->priority )
      {
        case( TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK ):
          $prompt = 'Internal priority is TX_AUTOSITEMAP_PI1_PRIORITY_ONELINEBREAK';
          t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, 0 );
          break;
        case( TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS ):
          $prompt = 'Internal priority is TX_AUTOSITEMAP_PI1_PRIORITY_MAXLINEBREAKS';
          t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, 0 );
          break;
        case( TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK ):
          $prompt = 'Internal priority is TX_AUTOSITEMAP_PI1_PRIORITY_NOLINEBREAK';
          t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, 0 );
          break;
      }
        // SWITCH : case
      
    }
      // DRS
  }

  
  
/**
 * calcTotalNumberOfMainMenus( ): 
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.2
 */
  private function calcTotalNumberOfMainMenus( )
  {
      // Set the total number of main menus
    $this->totalNumberOfMainMenus = count( $this->pagetreeStructure[$this->tsRootlineRootpageid] );

      // DRS
    if( $this->b_drs_calc )
    {
      $prompt = 'Total number of main menus: ' . $this->totalNumberOfMainMenus;
      t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, 0 );
    }
      // DRS
  }



/**
 * calcExcludeUidList( ): 
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.2
 */
    // 120716, dwildt-
//  private function calcExcludeUidList( $mainOrMargin )
  private function calcExcludeUidList( )
  {
    $this->calcExcludeUidListMain( );
    $this->calcExcludeUidListMargin( );
  }



/**
 * calcExcludeUidListMain( ): 
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.2
 */
  private function calcExcludeUidListMain( )
  {
      // 0.0.4, 120929
    $arrUidListForMain            = null;
    $arrExcludeUidListForMain     = null;
    $arrExcludeUidListForMainLast = null;

      // Short variable
    $pagetreeStructure = $this->pagetreeStructure[$this->tsRootlineRootpageid]; 
    
      // Area for proper page uids
    $start  = 0;
      // 120716, dwildt-
    //$end    = $this->tsRulesNumberofcolumnsMain;
      // 120716, dwildt+
    $end    = $this->numberOfMenusForMainColumns;
//var_dump( __CLASS__, __LINE__, array_keys( $pagetreeStructure ), $this->numberOfMenusForMainColumns );    
      // FOREACH  : Get excludeUidList
    foreach( array_keys( $pagetreeStructure ) as $key )
    {
        // CONTINUE : menu item should displayed
      if( $start < $end )
      {
        $arrUidListForMain[ ] = $pagetreeStructure[$key]['uid'];
        $start++;
        continue;
      }
        // CONTINUE : menu item should displayed

        // Menu item shouldn't displayed
      $arrExcludeUidListForMain[ ] = $pagetreeStructure[$key]['uid'];
      $start++;
    }
      // FOREACH  : Get excludeUidList
    
      // 0.0.4, 120929, +
      // Get list of uid of the menu margin
    $uidListMargin      = implode( ',', $arrExcludeUidListForMain );
      // Get uid of the last main menu
    $uidOfMainLast      = $arrUidListForMain[ count( $arrUidListForMain ) -1 ];
      // Set exclude uid list for the main menu (without the last main menu)
    $excludeUidsInMain  = $uidOfMainLast . ',' . $uidListMargin;
    
      // Unset the uid of the last main menu
    unset( $arrUidListForMain[ count( $arrUidListForMain ) -1 ] );
      // Get list of uid of the menu main
    $uidListMain            = implode( ',', $arrUidListForMain );
      // Set exclude uid list for the main menu (without the last main menu)
    $excludeUidsInMainLast  = $uidListMain . ',' . $uidListMargin;
    
      // Set excludeUidList
    $this->conf['menu_main.']['excludeUidList']       = $excludeUidsInMain;
      // 0.0.4, 120929, dwildt, 1+
    $this->conf['menu_main_last.']['excludeUidList']  = $excludeUidsInMainLast;

      // DRS
    if( $this->b_drs_calc )
    {
        // Menu Main
      if( empty( $excludeUidsInMain ) )
      {
        $prompt = 'menu_main.excludeUidList is left empty.';
      }
      if( ! empty( $excludeUidsInMain ) )
      {
        $prompt = 'menu_main.excludeUidList is set to ' . $excludeUidsInMain;
      }
      t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
        // Menu Main
        
        // 0.0.4, 120929, dwildt, +
        // Menu Main Last
      if( empty( $excludeUidsInMainLast ) )
      {
        $prompt = 'menu_main_last.excludeUidList is left empty.';
      }
      if( ! empty( $excludeUidsInMainLast ) )
      {
        $prompt = 'menu_main_last.excludeUidList is set to ' . $excludeUidsInMainLast;
      }
      t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
        // Menu Main Last
        // 0.0.4, 120929, dwildt, +
    }
      // DRS
  }



/**
 * calcExcludeUidListMargin( ): 
 *
 * @return    void
 * @version 0.0.2
 * @since   0.0.2
 */
  private function calcExcludeUidListMargin( )
  {
      // Short variable
    $pagetreeStructure = $this->pagetreeStructure[$this->tsRootlineRootpageid]; 
    
      // Area for proper page uids
    $start  = 0;
      // 120716, dwildt-
    //$end    = $this->tsRulesNumberofcolumnsMain;
      // 120716, dwildt+
    $end    = $this->numberOfMenusForMainColumns;
   
      // FOREACH  : Get excludeUidList
    foreach( array_keys( $pagetreeStructure ) as $key )
    {
        // CONTINUE : menu item should displayed
      if( $start < $end )
      {
        $arrExcludeUidList[] = $pagetreeStructure[$key]['uid'];
        $start++;
        continue;
      }
        // CONTINUE : menu item should displayed

        // Menu item shouldn't displayed
      break;
      $start++;
    }
      // FOREACH  : Get excludeUidList
    
      // Get excludeUidList
    $excludeUidList = implode( ',', $arrExcludeUidList );
    
      // Set excludeUidList
    $this->conf['menu_margin.']['excludeUidList'] = $excludeUidList;

      // DRS
    if( $this->b_drs_calc )
    {
      $prompt = 'menu_margin.excludeUidList is set to ' . $excludeUidList;
      t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
    }
      // DRS
  }



/**
 * calcGreatestMenu( ): 
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.3
 */
  private function calcGreatestMenu( )
  {
    
    $maxItems = 0;
    
      // FOREACH  : main menu
    $position = 0;
    foreach( $this->sumOfLevels as $uid => $arrLevel )
    {
      if( $position > 3 )
      {
        break;
      }
        // Current number of items is greater than former value
      if( $arrLevel['sum'] > $maxItems )
      {
        $maxItems = $arrLevel['sum'];
        $this->greatestMenu['uid'] = $uid;
        $this->greatestMenu['sum'] = $arrLevel['sum'];
      }
      $position++;
        // Current number of items is greater than former value
    }
      // FOREACH  : main menu
    
      // Set main menu title
    $keyRootLevel = key( $this->pagetreeStructure );
    $firstLevel   = $this->pagetreeStructure[$keyRootLevel];
    $uid          = $this->greatestMenu['uid'];
    $sum          = $this->greatestMenu['sum'];
    $title        = $firstLevel[$uid]['title'];
    $this->greatestMenu['titleMainMenu'] = $title;
      // Set main menu title

      // DRS
    if( $this->b_drs_calc )
    {
      $prompt = 'Greatest menu is "' . $title . '" (uid : ' . $uid . ') with #' . $sum . ' items.' ;
      t3lib_div :: devLog( '[OK/CALC] ' . $prompt , $this->extKey, -1 );
    }
      // DRS
  }



/**
 * calcMenusForCase( ): 
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.3
 */
  private function calcMenusForCase( )
  {
    $arrMenuHasLineBreak = null; 
    
      // RETURN : Less than four menus
    if( count ( $this->sumOfLevels ) < 4 )
    {
        // DRS
      if( $this->b_drs_calc )
      {
        $prompt = 'calcMenusForCase( ) doesn\'t calculate, because number of menus are less than 4.' ;
        t3lib_div :: devLog( '[OK/CALC] ' . $prompt , $this->extKey, 0 );
      }
        // DRS
      return;
    }
      // RETURN : Less than four menus

      // FOREACH  : Menus with line break
    $positionMenu = 0;
    foreach( array_keys( $this->sumOfLevels ) as $uidMainMenu )
    {
      $arrMenuHasLineBreak[$positionMenu]['linebreak'] = false;
      if( in_array( $uidMainMenu, array_keys( $this->menusWithLinebreak ) ) )
      {
        $arrMenuHasLineBreak[$positionMenu]['linebreak'] = true;
      }
      $positionMenu++;
    }
      // FOREACH  : Menus with line break

      // FOREACH  : Set $this->theFirstTwoMenusAreMenusWithLineBreak
    $this->theFirstTwoMenusAreMenusWithLineBreak = true;
    $sumOfTheFirstThreeDouble = 0;
    foreach( $arrMenuHasLineBreak as $positionMenu => $element )
    {
      if( $positionMenu < 2 )
      {
        if( ! $element['linebreak'] )
        {
          $this->theFirstTwoMenusAreMenusWithLineBreak = false;
        }
      }
      if( $positionMenu < 3 )
      {
        if( $element['linebreak'] )
        {
          $sumOfTheFirstThreeDouble++;
        }
      }
    }
      // FOREACH  : Set $this->theFirstTwoMenusAreMenusWithLineBreak
    
      // IF : Set $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak
    $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak = true;
    if( $sumOfTheFirstThreeDouble != 1 )
    {
      $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak = false;
    }
      // IF : Set $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak

    $this->oneOfTheFirstThreeMenusIsTheGreatest = false;
    if( ! $this->theFirstTwoMenusAreMenusWithLineBreak && ! $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak )
    {
      if( ! empty( $this->greatestMenu ) )
      {
        $this->oneOfTheFirstThreeMenusIsTheGreatest = true;
      }
    }

      // DRS
    if( $this->b_drs_calc )
    {
      switch( $this->theFirstTwoMenusAreMenusWithLineBreak )
      {
        case( true ):
          $prompt = 'The first two menus have a line break.' ;
          break;
        case( false ):
        default:
          $prompt = 'One or both of the first two menus hasn\'t any line break.' ;
          break;
      }
      t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
      switch( $this->onlyOneOfTheFirstThreeMenusIsWithLineBreak )
      {
        case( true ):
          $prompt = 'Only one of the first three menus is with a line break only.' ;
          break;
        case( false ):
        default:
          $prompt = 'No one or more than one of the first three menus is with a line break.' ;
          break;
      }
      t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
      switch( $this->oneOfTheFirstThreeMenusIsTheGreatest )
      {
        case( true ):
          $prompt = 'One of the first three menus is the greatest menu.' ;
          break;
        case( false ):
        default:
          $prompt = 'No one of the first three menus isn\'t the greatest menu.' ;
          break;
      }
      t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
    }
      // DRS
  }



/**
 * calcMenusWithLinebreak( ): 
 *
 * @return    void
 * @version 0.0.2
 * @since   0.0.2
 */
  private function calcMenusWithLinebreak( )
  {
//var_dump( __METHOD__, __LINE__, $this->sumOfLevels );
    foreach( array_keys( $this->sumOfLevels ) as $menuId )
    {
      if( $this->sumOfLevels[$menuId]['sum'] >= $this->tsRulesMenuItemslinebreak )
      {
//var_dump( __METHOD__, __LINE__, $menuId );
        $uid = $menuId;
        $sum = $this->sumOfLevels[$menuId]['sum'];
        $lineBreakBeforeItem = ( int ) ( ( $sum + 1 ) / 2 );

        $sumItems               = 0;
        $menuIdForLineBreak     = 0;
        $takeCurrentIdAndBreak  = false;

          // FOREACH  : second level menu item
        foreach( $this->sumOfLevels[$uid] as $uidSecendLevel => $sumSecondLevel )
        {
          if( $uidSecendLevel == 'sum' )
          {
            continue;
          }
          if( $takeCurrentIdAndBreak )
          {
            $menuIdForLineBreak = $uidSecendLevel;
            break;
          }
          $sumItems = $sumItems + $sumSecondLevel;
          if( $sumItems > $lineBreakBeforeItem )
          {
            $takeCurrentIdAndBreak = true;
          }
        }
          // FOREACH  : second level menu item

          // Set menu title
        $keyRootLevel = key( $this->pagetreeStructure );
        $firstLevel   = $this->pagetreeStructure[$keyRootLevel];
        $title        = $firstLevel[$uid]['title'];
          // Set menu title
          
          // CONTINUE : Left column would be shorter than right column
        if( ( int ) $menuIdForLineBreak < 1 )
        {
            // DRS
          if( $this->b_drs_warn )
          {
            $prompt = 'The menu "' . $title . '" can\'t get a line break.  ' . 
                      'The left column would be shorter than the right column. ';
            t3lib_div :: devLog( '[WARN/CALC] ' . $prompt , $this->extKey, 2 );
            $prompt = 'If you don\'t like the result, please decrease the value of rules.menuItems.lineBreak.';
            t3lib_div :: devLog( '[HELP/CALC] ' . $prompt , $this->extKey, 1 );
          }
            // DRS
          continue;
        }
          // CONTINUE : Left column would be shorter than right column
          
          // Set sub menu title
        $subTitle     = $firstLevel[$uid][$menuIdForLineBreak]['title'];
        $this->menusWithLinebreak[$uid]['uid']             = $uid;
        $this->menusWithLinebreak[$uid]['sum']             = $sum;
        $this->menusWithLinebreak[$uid]['titleMainMenu']   = $title;
        $this->menusWithLinebreak[$uid]['titleSubMenu']    = $subTitle;
        $this->menusWithLinebreak[$uid]['idForLineBreak']  = $menuIdForLineBreak;
          // Set sub menu title

          // DRS
        if( $this->b_drs_calc )
        {
          $prompt = 'The menu "' . $title . ' > ' . $subTitle . '" (uid : ' . $menuIdForLineBreak . ')  ' . 
                    'will get the line break before.';
          t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
        }
          // DRS

        
        if( $this->greatestMenu['uid'] == $uid )
        {
          $this->greatestMenu['titleSubMenu']   = $subTitle;
          $this->greatestMenu['idForLineBreak'] = $menuIdForLineBreak;
        }
      }
    }

//var_dump( __METHOD__, __LINE__, $this->sumOfLevels, $this->menusWithLinebreak, $this->greatestMenu );

    return;
  }



/**
 * calcSumOfLevels( ): 
 *
 * @return    void
 * @version 0.0.2
 * @since   0.0.2
 */
  private function calcSumOfLevels( )
  {
      // Short variable $firstLevel
    $keyRootLevel = key( $this->pagetreeStructure );
    $firstLevel   = $this->pagetreeStructure[$keyRootLevel];
      // Short variable $firstLevel
    
      // Area for proper page uids
    $start  = 0;
    $end    = $this->tsRulesNumberofcolumnsMain;
   
      // Array for sum of levels
    $sumLevels = array( );
    
      // FOREACH : first level > second level > third level
    foreach( $firstLevel as $keyFirstLevel => $secondLevel )
    {
        // CONTINUE : menu item should displayed
      if( $start >= $end )
      {
        break;
      }
        // CONTINUE : menu item should displayed

        // CONTINUE : there is no submenu
      if( ! is_array( $secondLevel ) )
      {
        continue;
      }
        // CONTINUE : there is no submenu
        // Count items
      $sumLevels[$keyFirstLevel]['sum']++;
        // FOREACH : second level > third level
      foreach( $secondLevel as $keySecondLevel => $thirdLevel )
      {
          // CONTINUE : there is no submenu
        if( ! is_array( $thirdLevel ) )
        {
          continue;
        }
          // CONTINUE : there is no submenu
          // Count items
        $sumLevels[$keyFirstLevel]['sum']++;
        $sumLevels[$keyFirstLevel][$keySecondLevel]++;
          // Count items
          // FOREACH : third level
        foreach( $thirdLevel as $forthLevel )
        {
            // CONTINUE : there is no submenu
          if( ! is_array( $forthLevel ) )
          {
            continue;
          }
            // CONTINUE : there is no submenu
            // Count items
          $sumLevels[$keyFirstLevel]['sum']++;
          $sumLevels[$keyFirstLevel][$keySecondLevel]++;
            // Count items
        }
          // FOREACH : third level
      }
        // FOREACH : second level > third level
      $start++;
    }
      // FOREACH : first level > second level > third level
    
    $this->sumOfLevels = $sumLevels;

    if( $this->b_drs_calc )
    {
      $prompt = 'sumOfLevels: ' . var_export( $this->sumOfLevels, true );
      t3lib_div :: devLog( '[INFO/CALC] ' . $prompt , $this->extKey, 0 );
    }
//var_dump( __METHOD__, __LINE__, $sumLevels, $this->pagetreeStructure );
  }






  /***********************************************
  *
  * cObject
  *
  **********************************************/



  /**
 * cObjDataAddRow( ):
 *
 * @param    array
 * @return    void
 * @version 0.0.1
 * @since   0.0.1
 */
  private function cObjDataAddRow( $row )
  {
    static $first_loop = true;

    foreach( ( array ) $row as $key => $value )
    {
      $this->cObj->data[ $key ] = $value;
    }

    if( $first_loop )
    {
      if( $this->b_drs_all )
      {
        $prompt = 'This fields are added to cObject: ' . implode( ', ', array_keys( $row ) );
        t3lib_div :: devLog( '[INFO/ALL] ' . $prompt , $this->extKey, 0 );
        $prompt = 'I.e: you can use the content in TypoScript with: field = ' . key( $row );
        t3lib_div :: devLog( '[INFO/ALL] ' . $prompt , $this->extKey, 0 );
      }
      $first_loop = false;
    }
  }



  /**
 * cObjDataRemoveRow( ):
 *
 * @param    array
 * @return    void
 * @version 0.0.1
 * @since   0.0.1
 */
  private function cObjDataRemoveRow( $row )
  {
    foreach( array_keys( ( array ) $row ) as $key )
    {
      unset( $this->cObj->data[ $key ] );
    }
  }






  /***********************************************
   *
   * Columns
   *
   **********************************************/



/**
 * columnsCssClass( ): 
 *
 * @return    void
 * @version 0.0.3
 * @since   0.0.2
 */
  private function columnsCssClass( )
  {
    $this->cssClass = array( );
    

      // SWITCH : case
    switch( $this->case )
    {
      case( TX_AUTOSITEMAP_PI1_MENUS_01 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_03 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05 ):
        $this->cssClass['main']   = $this->tsCssClasses[$this->case]['mainSingle'];
        $this->cssClass['margin'] = $this->tsCssClasses[$this->case]['margin'];
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE ):
        $this->cssClass = $this->tsCssClasses[$this->case];
        break;
      default:
        $prompt = 'ERROR: case isn\'t defined: "' . $this->case . '"  <br />' .
                  '<br />' .
                  'TYPO3 autositemap ' . __METHOD__ . ' (line: ' . __LINE__ . ').';
        die( $prompt );
    }
      // SWITCH : case

      // DRS
    if( $this->b_drs_calc )
    {
      $prompt = 'Values for css classes: ' . var_export( $this->cssClass, true );
      t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, 0 );
    }
      // DRS
    
//var_dump( __METHOD__, __LINE__, $this->tsCssClasses, $this->case, $this->cssClass );
  }



/**
 * columnsCssReplaceMainWithPriorityDouble( ) : Replace CSS class marker %main% with priority 
 *                                              mainDouble, mainSingle, main
 *
 * @return    string        $hmenu  : 
 * @version 0.0.3
 * @since   0.0.3
 */
  private function columnsCssReplaceMainWithPriorityDouble( $hmenu )
  {
    // SWITCH : set main with priority main, mainSingle, mainDouble
    switch( true )
    {
      case( isset( $this->cssClass['mainDouble'] ) ):
        $main = $this->cssClass['mainDouble'];
        break;
      case( isset( $this->cssClass['mainSingle'] ) ):
        $main = $this->cssClass['mainSingle'];
        break;
      case( isset( $this->cssClass['main'] ) ):
        $main = $this->cssClass['main'];
        break;
    }
      // SWITCH : set main with priority main, mainSingle, mainDouble

//var_dump( __METHOD__, __LINE__, $main, $this->cssClass );
      // DRS
    if( empty( $main ) )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Sorry, there isn\'t any value for the css marker %main% in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [ERROR/CSS] '. $prompt, $this->extKey, 3 );
        $prompt = '%main% will removed. You will get unexpected results in context with your CSS!';
        t3lib_div::devlog(' [WARN/CSS] '. $prompt, $this->extKey, 2 );
        $prompt = 'Please check plugin.tx_autositemap_pi1.css.' . $this->case . '.';
        t3lib_div::devlog(' [HELP/CSS] '. $prompt, $this->extKey, 1 );
      }
    }
    if( ! empty( $main ) )
    {
      if( $this->b_drs_css )
      {
        $prompt = 'css marker %main% will replaced with "' . $main . '" in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [OK/CSS] '. $prompt, $this->extKey, 1 );
        $prompt = 'css marker %mainDouble% will replaced with "' . $main . '" in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [OK/CSS] '. $prompt, $this->extKey, 1 );
      }
    }
      // DRS

    $hmenu = str_replace( '%main%', $main, $hmenu );
    $hmenu = str_replace( '%mainDouble%', $main, $hmenu );
    return $hmenu;
  }



/**
 * columnsCssReplaceMainWithPrioritySingle( ) : Replace CSS class marker %main% with priority 
 *                                              main, mainSingle, mainDouble
 *
 * @return    string        $hmenu  : 
 * @version 0.0.3
 * @since   0.0.3
 */
  private function columnsCssReplaceMainWithPrioritySingle( $hmenu )
  {
      // SWITCH : set main with priority main, mainSingle, mainDouble
    switch( true )
    {
      case( isset( $this->cssClass['main'] ) ):
        $main = $this->cssClass['main'];
        break;
      case( isset( $this->cssClass['mainSingle'] ) ):
        $main = $this->cssClass['mainSingle'];
        break;
      case( isset( $this->cssClass['mainDouble'] ) ):
        $main = $this->cssClass['mainDouble'];
        break;
    }
      // SWITCH : set main with priority main, mainSingle, mainDouble

      // DRS
    if( empty( $main ) )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Sorry, there isn\'t any value for the css marker %main% in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [ERROR/CSS] '. $prompt, $this->extKey, 3 );
        $prompt = '%main% will removed. You will get unexpected results in context with your CSS!';
        t3lib_div::devlog(' [WARN/CSS] '. $prompt, $this->extKey, 2 );
        $prompt = 'Please check plugin.tx_autositemap_pi1.css.' . $this->case . '.';
        t3lib_div::devlog(' [HELP/CSS] '. $prompt, $this->extKey, 1 );
      }
    }
    if( ! empty( $main ) )
    {
      if( $this->b_drs_css )
      {
        $prompt = 'css marker %main% will replaced with "' . $main . '" in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [OK/CSS] '. $prompt, $this->extKey, 1 );
        $prompt = 'css marker %mainSingle% will replaced with "' . $main . '" in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [OK/CSS] '. $prompt, $this->extKey, 1 );
      }
    }
      // DRS

    $hmenu = str_replace( '%main%', $main, $hmenu );
    $hmenu = str_replace( '%mainSingle%', $main, $hmenu );
    return $hmenu;
  }



/**
 * columnsCssReplaceMargin( ) : Replace CSS class marker %margin%
 *
 * @return    string        $hmenu  : 
 * @version 0.0.3
 * @since   0.0.3
 */
  private function columnsCssReplaceMargin( $hmenu )
  {
    $margin = $this->cssClass['margin'];

      // DRS
    if( empty( $margin ) )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Sorry, there isn\'t any value for the css marker %margin% in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [ERROR/CSS] '. $prompt, $this->extKey, 3 );
        $prompt = '%margin% will removed. You will get unexpected results in context with your CSS!';
        t3lib_div::devlog(' [WARN/CSS] '. $prompt, $this->extKey, 2 );
        $prompt = 'Please check plugin.tx_autositemap_pi1.css.' . $this->case . '.';
        t3lib_div::devlog(' [HELP/CSS] '. $prompt, $this->extKey, 1 );
      }
    }
    if( ! empty( $margin ) )
    {
      if( $this->b_drs_css )
      {
        $prompt = 'css marker %margin% will replaced with "' . $margin . '" in method ' . __METHOD__ . '.';
        t3lib_div::devlog(' [OK/CSS] '. $prompt, $this->extKey, 1 );
      }
    }
      // DRS

    $hmenu = str_replace( '%margin%', $margin, $hmenu );
    return $hmenu;
  }



///**
// * columnsCssWidth( ): 
// *
// * @return    void
// * @version 0.0.2
// * @since   0.0.2
// */
//  private function columnsCssWidth( )
//  {
//    $this->cssWidth = array( );
//    
//      // SWITCH : case
//    switch( $this->case )
//    {
//      case( TX_AUTOSITEMAP_PI1_MENUS_01 ):
//      case( TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE ):
//        $cObj_name  = $this->conf['css.']['1.']['main'];
//        $cObj_conf  = $this->conf['css.']['1.']['main.'];
//        $this->cssWidth['main'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        break;
//        break;
//      case( TX_AUTOSITEMAP_PI1_MENUS_02 ):
//      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE ):
//        $cObj_name  = $this->conf['css.']['2.']['main'];
//        $cObj_conf  = $this->conf['css.']['2.']['main.'];
//        $this->cssWidth['main'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        break;
//      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE ):
//        $cObj_name  = $this->conf['css.']['2.']['main_wo_inner_double'];
//        $cObj_conf  = $this->conf['css.']['2.']['main_wo_inner_double.'];
//        $this->cssWidth['main'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        $cObj_name  = $this->conf['css.']['2.']['mainDouble'];
//        $cObj_conf  = $this->conf['css.']['2.']['main_wi_inner_double.'];
//        $this->cssWidth['mainDouble'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        break;
//      case( TX_AUTOSITEMAP_PI1_MENUS_03 ):
//        $cObj_name  = $this->conf['css.']['3.']['main'];
//        $cObj_conf  = $this->conf['css.']['3.']['main.'];
//        $this->cssWidth['main'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        break;
//      case( TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE ):
//        $cObj_name  = $this->conf['css.']['3.']['main'];
//        $cObj_conf  = $this->conf['css.']['3.']['main.'];
//        $this->cssWidth['main'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        $cObj_name  = $this->conf['css.']['3.']['mainDouble'];
//        $cObj_conf  = $this->conf['css.']['3.']['main_wi_inner_double.'];
//        $this->cssWidth['mainDouble'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        break;
//      case( TX_AUTOSITEMAP_PI1_MENUS_04 ):
//        $cObj_name  = $this->conf['css.']['4.']['main'];
//        $cObj_conf  = $this->conf['css.']['4.']['main.'];
//        $this->cssWidth['main'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        break;
//      case( TX_AUTOSITEMAP_PI1_MENUS_05 ):
//      default:
//        $cObj_name  = $this->conf['css.']['5.']['main'];
//        $cObj_conf  = $this->conf['css.']['5.']['main.'];
//        $this->cssWidth['main'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        $cObj_name  = $this->conf['css.']['5.']['margin'];
//        $cObj_conf  = $this->conf['css.']['5.']['margin.'];
//        $this->cssWidth['margin'] = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        break;
//    }
//      // SWITCH : case
//
//      // DRS
//    if( $this->b_drs_calc )
//    {
//      $prompt = 'Values for css width: ' . var_export( $this->cssWidth, true );
//      t3lib_div::devlog(' [INFO/CALC] '. $prompt, $this->extKey, 0 );
//    }
//      // DRS
//    
//  }



/**
 * columnsRenderMain( ): 
 *
 * @return    array        $arr_return  : data[content] contains the hierachical menu
 * @version 0.0.4
 * @since   0.0.2
 */
  private function columnsRenderMain( )
  {
    $arr_return = array( );
    
      // Render the hierachical menu (main menu)
      // Get TypoScript configuration for the hierachical menu
    $cObj_name  = $this->conf['menu_main'];
    $cObj_conf  = $this->conf['menu_main.'];
    $hmenuMain  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // Render the hierachical menu (main menu)

      // 0.0.4, dwildt, +
      // Render the hierachical menu (main menu last)
      // Get TypoScript configuration for the hierachical menu
    $cObj_name      = $this->conf['menu_main_last'];
    $cObj_conf      = $this->conf['menu_main_last.'];
    $hmenuMainLast  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // Render the hierachical menu (main menu last)
    
    $hmenu = $hmenuMain . $hmenuMainLast;
      // 0.0.4, dwildt, +

      // SWITCH : case
    switch( $this->case )
    {
      case( TX_AUTOSITEMAP_PI1_MENUS_01 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_03 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05 ):
          // Follow the workflow
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_01_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE ):
          // Wrap the sub menu
        $arr_return = $this->htmlMenu( $hmenu );
        $hmenu      = $arr_return['data']['content'];
        break;
      default:
        $prompt = 'ERROR: case isn\'t defined: "' . $this->case . '"  <br />' .
                  '<br />' .
                  'TYPO3 autositemap ' . __METHOD__ . ' (line: ' . __LINE__ . ').';
        die( $prompt );
    }
      // SWITCH : case

      // Replace CSS class variables with values
    $hmenu = $this->columnsCssReplaceMainWithPrioritySingle( $hmenu );

      // RETURN : content - the hierachical menu
    $arr_return['data']['content'] = $hmenu;
    return $arr_return;
  }



/**
 * columnsRenderMainAndMargin( ): 
 *
 * @return    array        $arr_return  : data[content] contains the sitemap
 * @version 0.0.3
 * @since   0.0.2
 */
  private function columnsRenderMainAndMargin( )
  {
      // RETURN : rendering of the main columns failed
    $arr_return = $this->columnsRenderMain( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // RETURN : rendering of the main columns failed
    
      // Content : rendering of the main columns
    $main = $arr_return['data']['content'];

      // RETURN : rendering of the margin column failed
    $arr_return = $this->columnsRenderMargin( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // RETURN : rendering of the margin column failed
    
      // Content : rendering of the main columns
    $margin = $arr_return['data']['content'];

      // Content : rendering of the main columns plus margin column
    $arr_return['data']['content'] = $main . $margin;
    return $arr_return;
  }



/**
 * columnsRenderMargin( ): 
 *
 * @return    array        $arr_return  : data[content] contains the hierachical menu
 * @version 0.0.2
 * @since   0.0.2
 */
  private function columnsRenderMargin( )
  {
    $arr_return = array( );
    
      // Get TypoScript configuration for the hierachical menu
    $cObj_name  = $this->conf['menu_margin'];
    $cObj_conf  = $this->conf['menu_margin.'];
      // Get TypoScript configuration for the hierachical menu
      
      // Render the hierachical menu
    $hmenu = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );

      // Replace CSS class variables with values
    $hmenu = $this->columnsCssReplaceMargin( $hmenu );

      // RETURN : content - the hierachical menu
    $arr_return['data']['content'] = $hmenu;
    return $arr_return;
  }






  /***********************************************
   *
   * HTML
   *
   **********************************************/



/**
 * htmlCleanUpComments( ): 
 *
 * @return    string        $content  : Sitemap rendered in HTML 
 * @version 0.0.2
 * @since   0.0.2
 */
  private function htmlCleanUpComments( $content )
  {
      // RETURN : HTML comments should not removed
    if( $this->tsDebuggingDontremovehtmlcomments )
    {
      return $content;
    }
      // RETURN : HTML comments should not removed

      // Remove HTML comments
      // Pattern
    $htmlCommentPattern = '/(\<\!\-\-.*\-\-\>)/sU';
      // Replacement
    $content = preg_replace( $htmlCommentPattern, null, $content );
    
      // RETURN : content without HTML comments
    return $content;
  }



/**
 * htmlMenu( ): 
 *
 * @return    array        $arr_return  : data[content] contains the hierachical menu
 * @version 0.0.3
 * @since   0.0.3
 */
  private function htmlMenu( $hmenu)
  {
      // Wrap the sub menu
    $arr_return = $this->htmlMenuWrapSubmenu( $hmenu );
    $hmenu      = $arr_return['data']['content'];
      // Wrap the main menu
    $arr_return = $this->htmlMenuWrapMainmenu( $hmenu );
    $hmenu      = $arr_return['data']['content'];
//    $hmenu = str_replace( '%mainDouble%', $this->cssWidth['mainDouble'], $hmenu );

      // RETURN : content - the hierachical menu
    $arr_return['data']['content'] = $hmenu;
    return $arr_return;
  }



/**
 * htmlMenuWrapMainmenu( ): 
 *
 * @return    array        $arr_return  : data[content] contains the sitemap
 * @version 0.0.2
 * @since   0.0.2
 */
  private function htmlMenuWrapMainmenu( $content )
  {
      // SWITCH : case
    switch( $this->case )
    {
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE ):
        $arr_return = $this->htmlMenuWrapMainmenuMultiple( $content );
        break;
      default:
        $arr_return = $this->htmlMenuWrapMainmenuGreatest( $content );
        break;
    }
    return $arr_return;
  }



/**
 * htmlMenuWrapMainmenuGreatest( ): 
 *
 * @return    array        $arr_return  : data[content] contains the sitemap
 * @version 0.0.2
 * @since   0.0.2
 */
  private function htmlMenuWrapMainmenuGreatest( $content )
  {
    $arr_return = array( );
    $arr_return['data']['content'] = $content;
      
      // Id of the main menu, which should wrapped for a double column
    $uid = $this->greatestMenu['uid'];

      
      // Outer marker
    $hashMarkerOuter          = '###OUTER-UID-' . $uid . '###';    
    $subpartMarkerOuterBegin  = '<!-- ' . $hashMarkerOuter . ' begin -->';
    $subpartMarkerOuterEnd    = '<!-- ' . $hashMarkerOuter . ' end -->';
      // Outer marker

      // Inner marker
    $hashMarkerInner          = '###INNER-UID-' . $uid . '###';    
    $subpartMarkerInnerBegin  = '<!-- ' . $hashMarkerInner . ' begin -->';
    $subpartMarkerInnerEnd    = '<!-- ' . $hashMarkerInner . ' end -->';
      // Inner marker

      // RETURN : error. Content doesn't contain the current hashMarker
    $pos = strpos( $content, $hashMarkerInner );
    if( $pos === false )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'The sitemap code doesn\'t contains the marker "' . $hashMarkerInner . '"!';
        t3lib_div::devlog(' [ERROR/HTML] '. $prompt, $this->extKey, 3 );
      }
      return $arr_return;
    }
      // RETURN : error. Content doesn't contain the current hashMarker

      // RETURN : error. Content doesn't contain the current hashMarker
    $pos = strpos( $content, $hashMarkerOuter );
    if( $pos === false )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'The sitemap code doesn\'t contains the marker "' . $hashMarkerOuter . '"!';
        t3lib_div::devlog(' [ERROR/HTML] '. $prompt, $this->extKey, 3 );
      }
      return $arr_return;
    }
      // RETURN : error. Content doesn't contain the current hashMarker

      // Get the inner wrap for the current menu
    $cObj_name  = $this->conf['html.']['menuMainInnerWrap'];
    $cObj_conf  = $this->conf['html.']['menuMainInnerWrap.'];
    $wrapInner  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // Get the inner wrap for the current menu
      
      // Get the outer wrap for the current menu
    $cObj_name  = $this->conf['html.']['menuMainOuterWrap'];
    $cObj_conf  = $this->conf['html.']['menuMainOuterWrap.'];
    $wrapOuter  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      // Get the outer wrap for the current menu
      
    
      // Get the whole menu for the double column - main and subs - inner wrap
    $subpart = $this->cObj->getSubpart( $content, $hashMarkerInner );
      // Wrap it with the inner wrap
    $subpart = str_replace( '%content%', $subpart, $wrapInner );
      // Wrap it with the subpart marker for the inner wrap
    $subpart = $subpartMarkerInnerBegin . $subpart . $subpartMarkerInnerEnd;
      // Wrap it with the double column wrap
    $subpart = str_replace( '%content%', $subpart, $wrapOuter );
      // Wrap it with the subpart marker for the outer wrap
    $subpart = $subpartMarkerOuterBegin . $subpart . $subpartMarkerOuterEnd;
      // Replace CSS class variables with values
    $subpart = $this->columnsCssReplaceMainWithPriorityDouble( $subpart );
      // Replace the outer wrapped subpart with the outer wrapped wrapped subpart
    $content = $this->cObj->substituteSubpart( $content, $hashMarkerOuter, $subpart, true);

//var_dump( __METHOD__, __LINE__, $this->greatestMenu, $subpart );

    if( $this->b_drs_html )
    {
      $titleMainMenu  = $this->greatestMenu['titleMainMenu'];
      $prompt = 'The main menu "' . $titleMainMenu . '" (id ' . $uid . ') ' .
                'is wrapped with the value from html.wrapDoubleColumn.';
      t3lib_div::devlog(' [OK/HTML] '. $prompt, $this->extKey, -1 );
    }

      // RETURN : the handled sitemap
    $arr_return['data']['content'] = $content;
    return $arr_return;
  }



/**
 * htmlMenuWrapMainmenuMultiple( ): 
 *
 * @return    array        $arr_return  : data[content] contains the sitemap
 * @version 0.0.4
 * @since   0.0.2
 */
  private function htmlMenuWrapMainmenuMultiple( $content )
  {
    $arr_return = array( );
    $arr_return['data']['content'] = $content;
      
    $arrUids = array_keys( $this->menusWithLinebreak );
    $uidLast = $arrUids[ count( $arrUids ) - 1 ];
    foreach( $arrUids as $uid )
    {
        // Outer marker
      $hashMarkerOuter          = '###OUTER-UID-' . $uid . '###';    
      $subpartMarkerOuterBegin  = '<!-- ' . $hashMarkerOuter . ' begin -->';
      $subpartMarkerOuterEnd    = '<!-- ' . $hashMarkerOuter . ' end -->';
        // Outer marker

//        // 0.0.4, 120929, 5+
//        // Outer marker last
//      $hashMarkerOuterLast          = '###OUTER-LAST UID-' . $uid . '###';    
//      $subpartMarkerOuterLastBegin  = '<!-- ' . $hashMarkerOuter . ' begin -->';
//      $subpartMarkerOuterLastEnd    = '<!-- ' . $hashMarkerOuter . ' end -->';
//        // Outer marker last

        // Inner marker
      $hashMarkerInner          = '###INNER-UID-' . $uid . '###';    
      $subpartMarkerInnerBegin  = '<!-- ' . $hashMarkerInner . ' begin -->';
      $subpartMarkerInnerEnd    = '<!-- ' . $hashMarkerInner . ' end -->';
        // Inner marker

        // RETURN : error. Content doesn't contain the current hashMarker
      $pos = strpos( $content, $hashMarkerInner );
      if( $pos === false )
      {
        if( $this->b_drs_error )
        {
          $prompt = 'The sitemap code doesn\'t contains the marker "' . $hashMarkerInner . '"!';
          t3lib_div::devlog(' [ERROR/HTML] '. $prompt, $this->extKey, 3 );
        }
        return $arr_return;
      }
        // RETURN : error. Content doesn't contain the current hashMarker

        // RETURN : error. Content doesn't contain the current hashMarker
      $pos = strpos( $content, $hashMarkerOuter );
      if( $pos === false )
      {
        if( $this->b_drs_error )
        {
          $prompt = 'The sitemap code doesn\'t contains the marker "' . $hashMarkerOuter . '"!';
          t3lib_div::devlog(' [ERROR/HTML] '. $prompt, $this->extKey, 3 );
        }
        return $arr_return;
      }
        // RETURN : error. Content doesn't contain the current hashMarker

//        // 0.0.4, 120929, 12+
//        // RETURN : error. Content doesn't contain the current hashMarker
//      $pos = strpos( $content, $hashMarkerOuterLast );
//      if( $pos === false )
//      {
//        if( $this->b_drs_error )
//        {
//          $prompt = 'The sitemap code doesn\'t contains the marker "' . $hashMarkerOuterLast . '"!';
//          t3lib_div::devlog(' [ERROR/HTML] '. $prompt, $this->extKey, 3 );
//        }
//        return $arr_return;
//      }
//        // RETURN : error. Content doesn't contain the current hashMarker

        // Get the inner wrap for the current menu
      $cObj_name  = $this->conf['html.']['menuMainInnerWrap'];
      $cObj_conf  = $this->conf['html.']['menuMainInnerWrap.'];
      $wrapInner  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        // Get the inner wrap for the current menu

        // Get the outer wrap for the current menu
      switch( $uid )
      {
        case( $uidLast ):
          $cObj_name      = $this->conf['html.']['menuMainOuterWrapLast'];
          $cObj_conf      = $this->conf['html.']['menuMainOuterWrapLast.'];
          break;
        default:
          $cObj_name  = $this->conf['html.']['menuMainOuterWrap'];
          $cObj_conf  = $this->conf['html.']['menuMainOuterWrap.'];
          break;
      }
      $wrapOuter  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        // Get the outer wrap for the current menu

//        // 0.0.4, 120929, 5+
//      $wrapOuterLast  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//        // Get the outer wrap for the current menu


        // Get the whole menu for the double column - main and subs - inner wrap
      $subpart = $this->cObj->getSubpart( $content, $hashMarkerInner );
        // Wrap it with the inner wrap
      $subpart = str_replace( '%content%', $subpart, $wrapInner );
        // Wrap it with the subpart marker for the inner wrap
      $subpart = $subpartMarkerInnerBegin . $subpart . $subpartMarkerInnerEnd;
        // Wrap it with the double column wrap
      $subpart = str_replace( '%content%', $subpart, $wrapOuter );
//        // 0.0.4, 120929, 4+
//        // Wrap it with the subpart marker for the outer wrap
//      $subpart = $subpartMarkerOuterLastBegin . $subpart . $subpartMarkerOuterLastEnd;
//        // Wrap it with the double column wrap
//      $subpart = str_replace( '%content%', $subpart, $wrapOuterLast );
        // Wrap it with the subpart marker for the outer wrap
      $subpart = $subpartMarkerOuterBegin . $subpart . $subpartMarkerOuterEnd;
        // Replace CSS class variables with values
      $subpart = $this->columnsCssReplaceMainWithPriorityDouble( $subpart );
        // Replace the outer wrapped subpart with the outer wrapped wrapped subpart
      $content = $this->cObj->substituteSubpart( $content, $hashMarkerOuter, $subpart, true);

  //var_dump( __METHOD__, __LINE__, $this->menusWithLinebreak[$uid], $subpart );

      if( $this->b_drs_html )
      {
        $titleMainMenu  = $this->menusWithLinebreak[$uid]['titleMainMenu'];
        $prompt = 'The main menu "' . $titleMainMenu . '" (id ' . $uid . ') ' .
                  'is wrapped with the value from html.wrapDoubleColumn.';
        t3lib_div::devlog(' [OK/HTML] '. $prompt, $this->extKey, -1 );
      }
    }

      // RETURN : the handled sitemap
    $arr_return['data']['content'] = $content;
    return $arr_return;
  }



/**
 * htmlMenuWrapSubmenu( ): 
 *
 * @return    array        $arr_return  : data[content] contains the sitemap
 * @version 0.0.2
 * @since   0.0.2
 */
  private function htmlMenuWrapSubmenu( $content )
  {
      // SWITCH : case
    switch( $this->case )
    {
      case( TX_AUTOSITEMAP_PI1_MENUS_02_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE ):
        $arr_return = $this->htmlMenuWrapSubmenuMultiple( $content );
        break;
      default:
        $arr_return = $this->htmlMenuWrapSubmenuGreatest( $content );
        break;
    }
    return $arr_return;
  }



/**
 * htmlMenuWrapSubmenuGreatest( ): 
 *
 * @return    array        $arr_return  : data[content] contains the sitemap
 * @version 0.0.2
 * @since   0.0.2
 */
  private function htmlMenuWrapSubmenuGreatest( $content )
  {
    $arr_return = array( );
    $arr_return['data']['content'] = $content;
//var_dump( __METHOD__, __LINE__, $content );      
      // Id of the submenu, which should prepended with a line break
    $idForLineBreak = $this->greatestMenu['idForLineBreak'];

      // Outer marker
    $hashMarkerOuter          = '###OUTER-UID-' . $idForLineBreak . '###';    
    $subpartMarkerOuterBegin  = '<!-- ' . $hashMarkerOuter . ' begin -->';
    $subpartMarkerOuterEnd    = '<!-- ' . $hashMarkerOuter . ' end -->';
      // Outer marker

      // RETURN : error. Content doesn't contain the current hashMarker
    $pos = strpos( $content, $hashMarkerOuter );
    if( $pos === false )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'The sitemap code doesn\'t contains the marker "' . $hashMarkerOuter . '"!';
        t3lib_div::devlog(' [ERROR/HTML] '. $prompt, $this->extKey, 3 );
      }
      return $arr_return;
    }
      // RETURN : error. Content doesn't contain the current hashMarker

      // Get the wrap for a double column
    $cObj_name  = $this->conf['html.']['menuSubOuterWrap'];
    $cObj_conf  = $this->conf['html.']['menuSubOuterWrap.'];
    $wrapOuter  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
      
      // Get the whole menu for the double column - main and subs - inner wrap
    $subpart = $this->cObj->getSubpart( $content, $hashMarkerOuter );
      // Wrap it with the double column wrap
    $subpart = str_replace( '%content%', $subpart, $wrapOuter );
      // Wrap it with the subpart marker for the outer wrap
    $subpart = $subpartMarkerOuterBegin . $subpart . $subpartMarkerOuterEnd;
      // Replace the outer wrapped subpart with the outer wrapped wrapped subpart
    $content = $this->cObj->substituteSubpart( $content, $hashMarkerOuter, $subpart, true);

    if( $this->b_drs_html )
    {
      $titleMainMenu  = $this->greatestMenu['titleMainMenu'];
      $titleSubMenu   = $this->greatestMenu['titleSubMenu'];
      $prompt = 'The sub menu "' . $titleMainMenu . ' > ' . $titleSubMenu . '" ' .
                ' (id ' . $idForLineBreak . ') is prepended with the value from html.menuSubOuterWrap.';
      t3lib_div::devlog(' [OK/HTML] '. $prompt, $this->extKey, -1 );
    }
//var_dump( __METHOD__, __LINE__, $subpart );

      // RETURN : the handled sitemap
    $arr_return['data']['content'] = $content;
    return $arr_return;
  }



/**
 * htmlMenuWrapSubmenuMultiple( ): 
 *
 * @return    array        $arr_return  : data[content] contains the sitemap
 * @version 0.0.2
 * @since   0.0.2
 */
  private function htmlMenuWrapSubmenuMultiple( $content )
  {
    $arr_return = array( );
    $arr_return['data']['content'] = $content;
      
    foreach( array_keys( $this->menusWithLinebreak ) as $uid )
    {
        // Id of the submenu, which should prepended with a line break
      $idForLineBreak = $this->menusWithLinebreak[$uid]['idForLineBreak'];

        // Outer marker
      $hashMarkerOuter          = '###OUTER-UID-' . $idForLineBreak . '###';    
      $subpartMarkerOuterBegin  = '<!-- ' . $hashMarkerOuter . ' begin -->';
      $subpartMarkerOuterEnd    = '<!-- ' . $hashMarkerOuter . ' end -->';
        // Outer marker

        // CONTINUE : error. Content doesn't contain the current hashMarker
      $pos = strpos( $content, $hashMarkerOuter );
      if( $pos === false )
      {
        if( $this->b_drs_error )
        {
          $prompt = 'The sitemap code doesn\'t contains the marker "' . $hashMarkerOuter . '"!';
          t3lib_div::devlog(' [ERROR/HTML] '. $prompt, $this->extKey, 3 );
        }
        continue; 
      }
        // CONTINUE : error. Content doesn't contain the current hashMarker

        // Get the wrap for a double column
      $cObj_name  = $this->conf['html.']['menuSubOuterWrap'];
      $cObj_conf  = $this->conf['html.']['menuSubOuterWrap.'];
      $wrapOuter  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );

        // Get the whole menu for the double column - main and subs - inner wrap
      $subpart = $this->cObj->getSubpart( $content, $hashMarkerOuter );
        // Wrap it with the double column wrap
      $subpart = str_replace( '%content%', $subpart, $wrapOuter );
        // Wrap it with the subpart marker for the outer wrap
      $subpart = $subpartMarkerOuterBegin . $subpart . $subpartMarkerOuterEnd;
        // Replace the outer wrapped subpart with the outer wrapped wrapped subpart
      $content = $this->cObj->substituteSubpart( $content, $hashMarkerOuter, $subpart, true);

      if( $this->b_drs_html )
      {
        $titleMainMenu  = $this->menusWithLinebreak[$uid]['titleMainMenu'];
        $titleSubMenu   = $this->menusWithLinebreak[$uid]['titleSubMenu'];
        $prompt = 'The sub menu "' . $titleMainMenu . ' > ' . $titleSubMenu . '" ' .
                  ' (id ' . $idForLineBreak . ') is prepended with the value from html.menuSubOuterWrap.';
        t3lib_div::devlog(' [OK/HTML] '. $prompt, $this->extKey, -1 );
      }
    }

      // RETURN : the handled sitemap
    $arr_return['data']['content'] = $content;
    return $arr_return;
  }






  /***********************************************
   *
   * Initials
   *
   **********************************************/



/**
 * init( ): 
 *
 * @return    array        $arr_return  : data[rootpageId] contain the id of the root page
 * @version 0.0.3
 * @since   0.0.3
 */
  private function init( )
  {
    $arr_return = null;
    
      // Init DRS - Development Reporting System
    $this->initDrs( );
      // Init TypoScript properties
    $this->initTypoScript( );
    
      // RETURN : Set root page id failed
    $arr_return = $this->initRootPageId( );
    if( $arr_return['error']['status'] )
    {
      $content = $arr_return['error']['header'] . $arr_return['error']['prompt'];
      return $this->pi_wrapInBaseClass( $content );
    }
      // RETURN : Set root page id failed

    return $arr_return;
  }



/**
 * initDrs( ): Set the booleans for Warnings, Errors and DRS - Development Reporting System
 *
 * version 0.0.2
 * since  0.0.1
 *
 * @return    void
 */
  private function initDrs( )
  {
    if ($this->arr_extConf['drs_mode'] == 'Enabled (for debugging only!)')
    {
      $this->b_drs_all        = true;
      $this->b_drs_error      = true;
      $this->b_drs_warn       = true;
      $this->b_drs_info       = true;
      $this->b_drs_calc       = true;
      $this->b_drs_css        = true;
      $this->b_drs_html       = true;
      $this->b_drs_sitemap    = true;
      $this->b_drs_sql        = true;
      $this->b_drs_todo       = true;
      $this->b_drs_typoscript = true;
      $prompt = 'DRS - Development Reporting System: ' . $this->arr_extConf['drs_mode'];
      t3lib_div::devlog('[INFO/DRS] '. $prompt, $this->extKey, 0);
    }
  }



/**
 * initRootPageId( ): 
 *
 * @return    array        $arr_return  : data[rootpageId] contain the id of the root page
 * @version 0.0.4
 * @since   0.0.2
 */
  private function initRootPageId( )
  {
    $arr_return = null;
    
      // 0.0.4, 120929
//      // Set current page id from TypoScript
//    if( empty( $this->tsRootlineLookfrompageid ) )
//    {
//      $this->tsRootlineLookfrompageid = $GLOBALS['TSFE']->id;
//      if( $this->b_drs_typoscript )
//      {
//        $prompt = 'rootline.lookFromPageId was empty!';
//        t3lib_div::devlog(' [WARN/TYPOSCRIPT] '. $prompt, $this->extKey, 2 );
//        $prompt = 'rootline.lookFromPageId is overriden with current page id: ' .
//                  $this->tsRootlineLookfrompageid;
//        t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 1 );
//      }
//    }
//      // Set current page id from TypoScript

//      // Set root page id from TypoScript
//    $cObj_name                  = $this->conf['rootline.']['rootpageId'];
//    $cObj_conf                  = $this->conf['rootline.']['rootpageId.'];
//    $this->tsRootlineRootpageid = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//    if( $this->b_drs_typoscript )
//    {
//      $prompt = 'rootline.rootpageId: ' . $this->tsRootlineRootpageid;
//      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
//    }
      // Set root page id from TypoScript

//      // IF root page id is empty, take root page of current page by default
//    if( empty( $this->tsRootlineRootpageid ) )
//    {
//        // 0.0.4, 120929, 1-
//      //$arr_rowsOfAllPagesInRootLine = $GLOBALS['TSFE']->sys_page->getRootLine( $this->tsRootlineLookfrompageid );
//        // 0.0.4, 120929, 1+
      $arr_rowsOfAllPagesInRootLine = $GLOBALS['TSFE']->sys_page->getRootLine( $GLOBALS['TSFE']->id );
      $this->tsRootlineRootpageid = $arr_rowsOfAllPagesInRootLine[0]['uid'];
      if( $this->b_drs_typoscript )
      {
        $prompt = 'rootline.rootpageId : ' . $this->tsRootlineRootpageid;
        t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 1 );
      }
//    }
//      // IF root page id is empty, take root page of current page by default

    if( ( int ) $this->tsRootlineRootpageid == 0 )
    {
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] =  '<h1 style="color:red">
                                        ' . $this->pi_getLL( 'prompt.tsRootlineRootpageid.null.header' ) . '
                                        </h1>';
      $arr_return['error']['prompt'] =  '<p style="color:red">
                                        ' . $this->pi_getLL( 'prompt.tsRootlineRootpageid.null.prompt' ) . '
                                        </p>';
      return $arr_return;
    }

    $arr_return['data']['rootpageId'] =  $this->tsRootlineRootpageid;
    return $arr_return;
  }



/**
 * initTypoScript( ):
 *
 * version 0.0.4
 * since  0.0.2
 *
 * @return    void
 */
  private function initTypoScript( )
  {    
    $this->initTypoScriptCSS( );

      // debugging.dontRemoveHtmlComments
    $cObj_name                      = $this->conf['debugging.']['dontRemoveHtmlComments'];
    $cObj_conf                      = $this->conf['debugging.']['dontRemoveHtmlComments.'];
    $this->tsDebuggingDontremovehtmlcomments = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    if( $this->b_drs_typoscript )
    {
      $prompt = 'debugging.dontRemoveHtmlComments: ' . $this->tsDebuggingDontremovehtmlcomments;
      if( $this->tsDebuggingDontremovehtmlcomments )
      {
        t3lib_div::devlog(' [WARN/TYPOSCRIPT] '. $prompt, $this->extKey, 2 );
        $prompt = 'Set debugging.dontRemoveHtmlComments to false after debugging!';
        t3lib_div::devlog(' [HELP/TYPOSCRIPT] '. $prompt, $this->extKey, 1 );
      }
      else
      {
        t3lib_div::devlog(' [OK/TYPOSCRIPT] '. $prompt, $this->extKey, -1 );
      }
    }
      // debugging.dontRemoveHtmlComments

      // 0.0.4, 120929, -
//      // rootline.lookFromPageId
//    $cObj_name                      = $this->conf['rootline.']['lookFromPageId'];
//    $cObj_conf                      = $this->conf['rootline.']['lookFromPageId.'];
//    $this->tsRootlineLookfrompageid = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//    if( $this->b_drs_typoscript )
//    {
//      $prompt = 'rootline.lookFromPageId: ' . $this->tsRootlineLookfrompageid;
//      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
//    }
//      // rootline.lookFromPageId

      // rootline.rootpageId
    $cObj_name                  = $this->conf['rootline.']['rootpageId'];
    $cObj_conf                  = $this->conf['rootline.']['rootpageId.'];
    $this->tsRootlineRootpageid = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    if( $this->b_drs_typoscript )
    {
      $prompt = 'rootline.rootpageId: ' . $this->tsRootlineRootpageid;
        // 0.0.4, 120929, +
      if( empty( $this->tsRootlineRootpageid ) )
      {
        $prompt = 'rootline.rootpageId is empty. This is OK.';
      }
        // 0.0.4, 120929, +
      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
    }
      // rootline.rootpageId

      // rules.menuItems.lineBreak
    $cObj_name                = $this->conf['rules.']['menuItems.']['lineBreak'];
    $cObj_conf                = $this->conf['rules.']['menuItems.']['lineBreak.'];
    $this->tsRulesMenuItemslinebreak = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    if( $this->b_drs_typoscript )
    {
      $prompt = 'rules.menuItems.lineBreak: ' . $this->tsRulesMenuItemslinebreak;
      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
    }
      // rules.menuItems.lineBreak

      // rules.numberOfColumns.main
    $cObj_name                = $this->conf['rules.']['numberOfColumns.']['main'];
    $cObj_conf                = $this->conf['rules.']['numberOfColumns.']['main.'];
    $this->tsRulesNumberofcolumnsMain = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    if( $this->b_drs_typoscript )
    {
      $prompt = 'rules.numberOfColumns.main: ' . $this->tsRulesNumberofcolumnsMain;
      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
    }
    if( $this->tsRulesNumberofcolumnsMain != 4 )
    {
      if( $this->b_drs_warn )
      {
        $prompt = 'Sorry, the value ' . $this->tsRulesNumberofcolumnsMain . ' isn\'t supported as yet.';
        t3lib_div::devlog(' [WARN/TYPOSCRIPT] '. $prompt, $this->extKey, 3 );
        $prompt = 'rules.numberOfColumns.main is set to 4.';
        t3lib_div::devlog(' [WARN/TYPOSCRIPT] '. $prompt, $this->extKey, 2 );
      }
      $this->tsRulesNumberofcolumnsMain = 4;
    }
      // rules.numberOfColumns.main

    // 0.0.4, 120929, -
//      // rules.numberOfColumns.margin
//    $cObj_name                  = $this->conf['rules.']['numberOfColumns.']['margin'];
//    $cObj_conf                  = $this->conf['rules.']['numberOfColumns.']['margin.'];
//    $this->tsRulesNumberofcolumnsMargin = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
//    if( $this->b_drs_typoscript )
//    {
//      $prompt = 'rules.numberOfColumns.margin: ' . $this->tsRulesNumberofcolumnsMargin;
//      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
//    }
//      // rules.numberOfColumns.margin
    // 0.0.4, 120929, -
      
      // rules.priority
    $cObj_name                  = $this->conf['rules.']['priority'];
    $cObj_conf                  = $this->conf['rules.']['priority.'];
    $this->tsRulesPriority = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    if( $this->b_drs_typoscript )
    {
      $prompt = 'rules.priority: ' . $this->tsRulesPriority;
      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
    }
    if( $this->b_drs_typoscript )
    {
      switch( $this->tsRulesPriority )
      {
        case( 'maxLineBreaks' ):
        case( 'oneLineBreak' ):
        case( 'noLineBreak' ):
          break;
        default:
          $prompt = 'ERROR: TypoScript property has an undefined value. <br />' .
                    'rules.priority is "' . $this->tsRulesPriority . '" <br />' . 
                    'But it must be one of this three values: <br />' . 
                    '* maxLineBreaks <br />' . 
                    '* oneLineBreak <br />' . 
                    '* noLineBreak <br />' .
                    '<br />' .
                    'TYPO3 autositemap ' . __METHOD__ . ' (line: ' . __LINE__ . ').';
          die( $prompt );
      }
    }
      // rules.priority
      
      // sql.pages.andWhere
    $cObj_name                = $this->conf['sql.']['pages.']['andWhere'];
    $cObj_conf                = $this->conf['sql.']['pages.']['andWhere.'];
    $this->tsSqlPagesAndwhere = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    if( $this->b_drs_warn )
    {
      $prompt = 'sql.pages.andWhere: ' . $this->tsSqlPagesAndwhere;
      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
      $prompt = 'Be aware: the andWhere statement from above must correspond with your HMENU TypoScript ' .
                'configuration!';
      t3lib_div::devlog(' [WARN/TYPOSCRIPT] '. $prompt, $this->extKey, 2 );
      $prompt = 'Example: If you display pages with another doktype too, you have to adapt the andWhere statement.';
      t3lib_div::devlog(' [HELP/TYPOSCRIPT] '. $prompt, $this->extKey, 1 );
    }
      // sql.pages.andWhere
  }
  
  
  
/**
 * initTypoScriptCSS( ):
 *
 * version 0.0.3
 * since  0.0.3
 *
 * @return    void
 */
  private function initTypoScriptCSS( )
  {
    $this->tsCssClasses = null; 
    
    foreach( array_keys( $this->conf['css.'] ) as $phpConstant )
    {
      if( substr( $phpConstant, -1 ) == '.' )
      {
        continue;
      }
      if( ! isset ( $this->conf['css.'][$phpConstant . '.'] ) )
      {
        continue;
      }
      foreach( array_keys( $this->conf['css.'][$phpConstant . '.'] ) as $cssProperty )
      {
        if( substr( $cssProperty, -1 ) == '.' )
        {
          continue;
        }
        $cObj_name  = $this->conf['css.'][$phpConstant . '.'][$cssProperty];
        $cObj_conf  = $this->conf['css.'][$phpConstant . '.'][$cssProperty . '.'];
        $cssValue   = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        $this->tsCssClasses[$phpConstant][$cssProperty] = $cssValue;
      }
    }

      // DRS
    if( empty ( $this->tsCssClasses ) )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Array based on conf[css] is empty!';
        t3lib_div::devlog(' [ERROR/TYPOSCRIPT] '. $prompt, $this->extKey, 3 );
      }
      return;
    }
      // DRS
    
      // DRS
    $menuCases = null;
    if( $this->b_drs_typoscript )
    {
        // Get defined constants for the menu cases
      $definedConstants = get_defined_constants( true );

        // FOREACH  : count defined constants for the menu cases
      $numberOfMenuCases = 0;
      foreach( array_keys( $definedConstants['user'] ) as $userDefinedConstant )
      {
        $pos = strpos( $userDefinedConstant, 'TX_AUTOSITEMAP_PI1_MENUS_' );
        if( ! ( $pos === false ) )
        {
          $menuCases[$userDefinedConstant] = $definedConstants['user'][$userDefinedConstant];
          $numberOfMenuCases++;
        }
      }
        // FOREACH  : count defined constants for the menu cases

        // ERROR  : Number of CSS classes doesn't correspondent with number of defined menu cases
      $numberOfCssClasses = count( $this->tsCssClasses );
      if( $numberOfCssClasses != $numberOfMenuCases )
      {
        $prompt = 'Number of CSS classes (#' . $numberOfCssClasses . ') doesn\'t correspondent ' . 
                  'with number of defined menu cases (#' . $numberOfMenuCases . ')!';
        t3lib_div::devlog(' [ERROR/TYPOSCRIPT] '. $prompt, $this->extKey, 3 );
        $csvMenuCases = var_export( $menuCases, true );
        $prompt = 'plugin.tx_autositemap_pi1.css doesn\'t correspondent with ' . $csvMenuCases . '.';
        t3lib_div::devlog(' [ERROR/TYPOSCRIPT] '. $prompt, $this->extKey, 3 );
        $prompt = 'Probably you will get unexpected CSS results!';
        t3lib_div::devlog(' [WARN/TYPOSCRIPT] '. $prompt, $this->extKey, 2 );
        $prompt = 'Please take care of a proper TypoScript!';
        t3lib_div::devlog(' [HELP/TYPOSCRIPT] '. $prompt, $this->extKey, 1 );
        return;
      }
        // ERROR  : Number of CSS classes doesn't correspondent with number of defined menu cases
      
        // OK : Number of CSS classes corresponds with number of defined menu cases
      $prompt = 'The number of CSS classes (#' . $numberOfCssClasses . ') corresponds ' . 
                'with the number of defined menu cases (#' . $numberOfMenuCases . ').';
      t3lib_div::devlog(' [OK/TYPOSCRIPT] '. $prompt, $this->extKey, -1 );
      $csvMenuCases = var_export( $menuCases, true );
      $prompt = 'Defined menu cases are: ' . $csvMenuCases . '.';
      t3lib_div::devlog(' [INFO/TYPOSCRIPT] '. $prompt, $this->extKey, 0 );
        // OK : Number of CSS classes corresponds with number of defined menu cases
//      var_dump( __METHOD__, __LINE__, $this->tsCssClasses, $menuCases );
    }
      // DRS
  }






  /***********************************************
   *
   * Pagetree
   *
   **********************************************/



/**
 * pagetreeStructure( ): 
 *
 * @return    void
 * @version 0.0.2
 * @since   0.0.2
 */
  private function pagetreeStructure( )
  {
    $arr1stLevel = array( );
    $arr2ndLevel = array( );
    $arr3rdLevel = array( );
    
//    $number = 0;
    
      // Short variable
    $rootPid = $this->tsRootlineRootpageid;
    
      // 1. level
    $arr2ndLevel  = $this->pagetreeStructureSql( $rootPid );
//    $arr1stLevel[$rootPid]['number'] = $number;
//    $arr1stLevel[$rootPid] = $arr1stLevel[$rootPid] + $arr2ndLevel[$rootPid];
    $arr1stLevel[$rootPid] = $arr2ndLevel[$rootPid];
//    $number++;
      // 1. level

      // 2. level + 3. level
    $pids2ndLevel = implode( ',', array_keys( $arr1stLevel[$rootPid] ) );
    $arr2ndLevel  = $this->pagetreeStructureSql( $pids2ndLevel );
    foreach( array_keys( $arr2ndLevel ) as $pid2ndLevel )
    {
//      $arr1stLevel[$rootPid][$pid2ndLevel] = $arr2ndLevel[$pid2ndLevel];
//      $arr1stLevel[$rootPid][$pid2ndLevel]['number'] = $number;
//      $number++;
      $arr1stLevel[$rootPid][$pid2ndLevel] = 
        $arr1stLevel[$rootPid][$pid2ndLevel] + $arr2ndLevel[$pid2ndLevel];
        // 3. level
      $pids3rdLevel = implode( ',', array_keys( $arr2ndLevel[$pid2ndLevel] ) );
      $arr3rdLevel  = $this->pagetreeStructureSql( $pids3rdLevel );
      foreach( array_keys( $arr3rdLevel ) as $pid3rdLevel )
      {
//        $arr1stLevel[$rootPid][$pid2ndLevel][$pid3rdLevel] = $arr3rdLevel[$pid3rdLevel];
//        $arr1stLevel[$rootPid][$pid2ndLevel][$pid3rdLevel]['number'] = $number;
//        $number++;
        $arr1stLevel[$rootPid][$pid2ndLevel][$pid3rdLevel] = 
          $arr1stLevel[$rootPid][$pid2ndLevel][$pid3rdLevel] + $arr3rdLevel[$pid3rdLevel];
//          // 4. level
//        foreach( array_keys( $arr3rdLevel[$pid3rdLevel] ) as $key4thLevel )
//        {
//  //        $arr1stLevel[$rootPid][$pid2ndLevel][$pid3rdLevel] = $arr3rdLevel[$pid3rdLevel];
//          $arr1stLevel[$rootPid][$pid2ndLevel][$pid3rdLevel][$key4thLevel]['number'] = $number;
//          $number++;
//        }
//          // 4. level
      }
        // 3. level
    }
      // 2. level + 3. level

    $this->pagetreeStructure = $arr1stLevel;
//var_dump( __METHOD__, __LINE__, $this->pagetreeStructure );
  }



/**
 * pagetreeStructureSql( ): 
 *
 * @return    array        $arr_return  : data[...
 * @version 0.0.2
 * @since   0.0.2
 */
  private function pagetreeStructureSql( $pidList )
  {
    //static $number = 0;
    
    $subPages = null;
    
      // Query
    $select_fields  = 'uid, pid, title';
    $from_table     = 'pages';
    $where_clause   = "FIND_IN_SET( pid, '" . $pidList . "') " . $this->pageSetSqlAndWhere( );
    $groupBy        = '';
    $orderBy        = 'sorting';
    $limit          = '';
      // Query

      // DRS prompt
    if ( $this->b_drs_sql )
    {
      $query  = $GLOBALS['TYPO3_DB']->SELECTquery
                (
                  $select_fields,
                  $from_table,
                  $where_clause,
                  $groupBy,
                  $orderBy,
                  $limit
                );
      $prompt = $query;
      t3lib_div::devlog( '[INFO/SQL] ' . $prompt, $this->extKey, 0 );
    }
      // DRS prompt

      // SELECT
    $res =  $GLOBALS['TYPO3_DB']->exec_SELECTquery
            (
              $select_fields,
              $from_table,
              $where_clause,
              $groupBy,
              $orderBy,
              $limit
            );
      // SELECT

      // Get array with TYPO3Groups
    while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
    {
      $subPages[$row['pid']][$row['uid']]['uid']    = $row['uid'];
      $subPages[$row['pid']][$row['uid']]['pid']    = $row['pid'];
      $subPages[$row['pid']][$row['uid']]['title']  = $row['title'];
//      $subPages[$row['pid']][$row['uid']]['number'] = $number;
//      $number++;
    }
      // Get array with TYPO3Groups
//var_dump( __METHOD__, __LINE__, $subPages );

    switch( true )
    {
      case( is_array( $subPages ) ):
        return $subPages;
        break;
      case( ! is_array( $subPages ) ):
      default:
        return true;
        break;
    }
  }



/**
 * pageSetSqlAndWhere( ): 
 *
 * @return    string        $pageSqlWhere  : 
 * @version 0.0.2
 * @since   0.0.2
 */
  private function pageSetSqlAndWhere( )
  {
      // RETURN : $pageSqlWhere is set
    if( $this->pageSqlAndWhere != null )
    {
      return $this->pageSqlAndWhere;
    }
      // RETURN : $pageSqlAndWhere is set
    
    $this->pageSqlAndWhere = $this->cObj->enableFields( 'pages' );
    $this->pageSqlAndWhere = $this->pageSqlAndWhere . $this->tsSqlPagesAndwhere;

    return $this->pageSqlAndWhere;
  }



/**
 * requirements
 *
 * version 0.0.2
 * since  0.0.2
 *
 * @return    void
 */
  private function requirements( )
  {    
    $arr_return = null;
    
      // RETURN : requirements methods failed
    $arr_return = $this->requirementsTsMenumain( );
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // RETURN : requirements methods failed
    
    return $arr_return;
  }



/**
 * requirementsTsMenumain
 *
 * @return    void
 * 
 * version 0.0.3
 * since  0.0.3
 */
  private function requirementsTsMenumain( )
  {    
    $arr_return = null;
    
      // ERROR  : menu_main is empty
    if( empty( $this->conf['menu_main.'] ) )
    {
      $arr_return['error']['status'] = true;
      $arr_return['error']['header'] =  '<h1 style="color:red">
                                        ' . $this->pi_getLL( 'prompt.requirements.ts.menumain.header' ) . '
                                        </h1>';
      $arr_return['error']['prompt'] =  $this->pi_getLL( 'prompt.requirements.ts.menumain.prompt');
      return $arr_return;
    }
      // ERROR  : menu_main is empty

    return $arr_return;
  }






  /***********************************************
   *
   * Sitemap
   *
   **********************************************/



/**
 * sitemap( ): Returns the sitemap
 *
 * @return    string        $content  : Sitemap rendered in HTML 
 * @version 0.0.2
 * @since   0.0.2
 */
  private function sitemap( )
  {
    $arr_return = array( );
    
      // Set $pagetreeStructure
    $this->pagetreeStructure( );

      // Set global vars
    $this->calc( );
    
      // Get CSS values for columns classes
    $this->columnsCssClass( );

      // SWITCH : case
    switch( $this->case )
    {
      case( TX_AUTOSITEMAP_PI1_MENUS_01 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_02 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_03 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_03_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04 ):
        if( $this->b_drs_sitemap )
        {
          $prompt = 'sitemap with 4 columns is taken.';
          t3lib_div::devlog(' [INFO/SITEMAP] '. $prompt, $this->extKey, -1 );
        }
        $arr_return = $this->columnsRenderMain( );
        break;
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_04_WI_02_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05 ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_01_DOUBLE ):
      case( TX_AUTOSITEMAP_PI1_MENUS_05_WI_02_DOUBLE ):
      default:
        if( $this->b_drs_sitemap )
        {
          $prompt = 'sitemap with 5 columns is taken.';
          t3lib_div::devlog(' [INFO/SITEMAP] '. $prompt, $this->extKey, -1 );
        }
        $arr_return = $this->columnsRenderMainAndMargin( );
        break;
    }
      // SWITCH : case

      // RETURN : Rendering of the sitemap failed
    if( $arr_return['error']['status'] )
    {
      return $arr_return;
    }
      // RETURN : Rendering of the sitemap failed

      // Get the html sitemap frame
    $cObj_name    = $this->conf['html.']['sitemap'];
    $cObj_conf    = $this->conf['html.']['sitemap.'];
    $htmlSitemap  = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
    
      // Get the current sitemap html code
    $content = $arr_return['data']['content'];
      // Insert current sitemap code into the html sitemap frame 
    $content = str_replace( '%content%', $content, $htmlSitemap );

      // Clean up all html comments
    $content = $this->htmlCleanUpComments( $content );
    
      // RETURN : the HTML sitemap code
    $arr_return['data']['content'] = $content;
    return $arr_return;
  }
  
  
  
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/autositemap/pi1/class.tx_autositemap_pi1.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/autositemap/pi1/class.tx_autositemap_pi1.php']);
}

?>