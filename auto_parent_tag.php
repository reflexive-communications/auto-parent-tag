<?php

use Civi\API\Exception\UnauthorizedException;

require_once 'auto_parent_tag.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function auto_parent_tag_civicrm_config(&$config)
{
    _auto_parent_tag_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function auto_parent_tag_civicrm_xmlMenu(&$files)
{
    _auto_parent_tag_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function auto_parent_tag_civicrm_install()
{
    _auto_parent_tag_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function auto_parent_tag_civicrm_postInstall()
{
    _auto_parent_tag_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function auto_parent_tag_civicrm_uninstall()
{
    _auto_parent_tag_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function auto_parent_tag_civicrm_enable()
{
    _auto_parent_tag_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function auto_parent_tag_civicrm_disable()
{
    _auto_parent_tag_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function auto_parent_tag_civicrm_upgrade($op, CRM_Queue_Queue $queue = null)
{
    return _auto_parent_tag_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function auto_parent_tag_civicrm_managed(&$entities)
{
    _auto_parent_tag_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function auto_parent_tag_civicrm_caseTypes(&$caseTypes)
{
    _auto_parent_tag_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function auto_parent_tag_civicrm_angularModules(&$angularModules)
{
    _auto_parent_tag_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function auto_parent_tag_civicrm_alterSettingsFolders(
    &$metaDataFolders = null
) {
    _auto_parent_tag_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function auto_parent_tag_civicrm_entityTypes(&$entityTypes)
{
    _auto_parent_tag_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function auto_parent_tag_civicrm_themes(&$themes)
{
    _auto_parent_tag_civix_civicrm_themes($themes);
}

/*
 * Extension code
 */

/**
 * Add parent tag if a child tag is added to a contact
 *
 * @param $op
 * @param $objectName
 * @param $objectId
 * @param $objectRef
 *
 * @throws API_Exception
 * @throws UnauthorizedException
 */
function auto_parent_tag_civicrm_postCommit($op, $objectName, $objectId, &$objectRef)
{
    // Only when creating entity tags
    if ($objectName !== 'EntityTag' || $op !== 'create') {
        return;
    }

    $contact_id = $objectRef[0][0];

    // Check for valid contact_id
    if (!is_numeric($contact_id)) {
        return;
    }

    $proc = new CRM_AutoParentTag_Processor();

    $parent_id = $proc->getParentTagId($objectId);

    // Tag has no parent
    if (is_null($parent_id)) {
        return;
    }

    // Add parent tag to contact
    $proc->addTagToContact($contact_id, $parent_id);
}
