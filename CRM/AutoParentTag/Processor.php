<?php

/**
 * Processor
 *
 * @package  auto-parent-tag
 * @author   Sandor Semsey <sandor@es-progress.hu>
 * @license  AGPL-3.0
 */
class CRM_AutoParentTag_Processor
{
    /**
     * Add parent tag to contact
     *
     * @param int $contact_id Contact ID
     * @param int $tag_id Child tag ID
     *
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public static function addParentTag(int $contact_id, int $tag_id): ?int
    {
        $parent_id = CRM_RcBase_Api_Get::parentTagId($tag_id, true);

        // Tag has no parent or non-existent tag
        if (is_null($parent_id)) {
            return null;
        }

        return CRM_RcBase_Api_Save::tagContact($contact_id, $parent_id, true);
    }
}
