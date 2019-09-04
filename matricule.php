<?php

require_once 'matricule.civix.php';
use CRM_Matricule_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function matricule_civicrm_config(&$config) {
  _matricule_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function matricule_civicrm_xmlMenu(&$files) {
  _matricule_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function matricule_civicrm_install() {
  _matricule_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function matricule_civicrm_postInstall() {
  _matricule_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function matricule_civicrm_uninstall() {
  _matricule_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function matricule_civicrm_enable() {
  _matricule_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function matricule_civicrm_disable() {
  _matricule_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function matricule_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _matricule_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function matricule_civicrm_managed(&$entities) {
  _matricule_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function matricule_civicrm_caseTypes(&$caseTypes) {
  _matricule_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function matricule_civicrm_angularModules(&$angularModules) {
  _matricule_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function matricule_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _matricule_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function matricule_civicrm_entityTypes(&$entityTypes) {
  _matricule_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_validateForm().
 * Checks if the Luxembourg matricule is 13 digits and valid.
 * The field label *must* be: Matricule
 * Example valid number: 1893120105732
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_validateForm/
 * @throws API_Exception when no Matricule field found
 */
function matricule_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
    if ( $formName == 'CRM_Contact_Form_Contact' or
         $formName == 'CRM_Contact_Form_Inline_CustomData' or
         $formName == 'CRM_Contribute_Form_Contribution_Main'
        ) {
        // do we have a field with a Matricule label?
        $matriculefield = civicrm_api3('CustomField', 'getsingle', array('label' => 'Matricule'));
       if(! $matriculefield){
            throw new API_Exception("Could not find custom field with 'Matricule' as its label");
        }
        // find out custom field form identifier, like custom_2_1
        $matriculefieldid = 'custom_'.$matriculefield['id'].'_'.$matriculefield['custom_group_id'];
      
         if ( $formName == 'CRM_Contribute_Form_Contribution_Main') {
             # Sometimes, field id is like custom_1
             $matriculefieldid = 'custom_'.$matriculefield['id'];
         } else{
             $customRecId = $osm = CRM_Utils_Array::value( "customRecId", $fields, FALSE );
             # And the rest of the time, field id is like custom_1_329. Go figure!
             $matriculefieldid = 'custom_'.$matriculefield['id'].'_'.$customRecId;
         }
        
        $matricule = CRM_Utils_Array::value( $matriculefieldid, $fields );
        
        if(!preg_match('/^[0-9]{13}$/', $matricule)) {
          $errors[$matriculefieldid] = ts( 'Matricule should 13 digit number' );
        } else {
            $shortmat = substr((string)$matricule, 0, -2);
            if (!($matricule == $shortmat._calcLuhn($shortmat)._calcVerhoeff($shortmat))){
                $errors[$matriculefieldid] = ts( 'Invalid matricule' );
            } 
        }
    }
    return;
}
  
//Method to calculate the Verhoeff checksum
// @see https://en.wikipedia.org/wiki/Verhoeff_algorithm
function _calcVerhoeff($num) {
    $_multiplicationTable = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(1,2,3,4,0,6,7,8,9,5),
        array(2,3,4,0,1,7,8,9,5,6),
        array(3,4,0,1,2,8,9,5,6,7),
        array(4,0,1,2,3,9,5,6,7,8),
        array(5,9,8,7,6,0,4,3,2,1),
        array(6,5,9,8,7,1,0,4,3,2),
        array(7,6,5,9,8,2,1,0,4,3),
        array(8,7,6,5,9,3,2,1,0,4),
        array(9,8,7,6,5,4,3,2,1,0)
    );
    $_permutationTable = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(1,5,7,6,2,8,3,0,9,4),
        array(5,8,0,3,7,9,6,1,4,2),
        array(8,9,1,6,0,4,3,5,2,7),
        array(9,4,5,3,1,2,6,8,7,0),
        array(4,2,8,6,5,7,3,9,0,1),
        array(2,7,9,3,8,0,6,4,1,5),
        array(7,0,4,6,9,1,3,2,5,8)
    );
    $_inverseTable = array(0,4,3,2,1,5,6,7,8,9);
    $r = 0;
    foreach(array_reverse(str_split($num)) as $n => $N) {
        $r = $_multiplicationTable[$r][$_permutationTable[($n+1)%8][$N]];
    }
    return $_inverseTable[$r];
}

//Method to calculate the Luhn checksum
// @see http://en.wikipedia.org/wiki/Luhn_algorithm
function _calcLuhn($num) {
    $stack = 0;
    $num = str_split(strrev($num) , 1);
    foreach($num as $key => $value) {
        if ($key % 2 == 0) {
            $value = array_sum(str_split($value * 2, 1));
        }
        $stack+= $value;
    }
    $stack%= 10;
    if ($stack != 0) {
        $stack-= 10;
    }
    return abs($stack);
}
