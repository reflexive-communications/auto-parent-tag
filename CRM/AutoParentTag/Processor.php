<?php

use Civi\API\Exception\UnauthorizedException;
use Civi\Api4\EntityTag;
use Civi\Api4\Tag;

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
     * Check if tag is applied to a contact
     *
     * @param  int  $contact_id  Contact ID
     * @param  int  $tag_id  Tag ID
     *
     * @return bool
     *
     * @throws API_Exception
     * @throws UnauthorizedException
     */
    public function isTagPresent(int $contact_id, int $tag_id): bool
    {
        $result = EntityTag::get()
            ->addSelect('id')
            ->addWhere('entity_id', '=', $contact_id)
            ->addWhere('tag_id', '=', $tag_id)
            ->addWhere('entity_table', '=', 'civicrm_contact')
            ->setLimit(1)
            ->execute();

        if ($result->count() === 1) {
            return true;
        }

        return false;
    }

    /**
     * Get parent tag id
     *
     * @param  int  $tag_id  Tag ID
     *
     * @return int|null
     *
     * @throws API_Exception
     * @throws UnauthorizedException
     */
    public function getParentTagId(int $tag_id): ?int
    {
        $result = Tag::get()
            ->addSelect('parent_id')
            ->addWhere('id', '=', $tag_id)
            ->setLimit(1)
            ->execute();

        return $result->first()['parent_id'];
    }

    /**
     * Add tag to contact
     *
     * @param  int  $contact_id  Contact ID
     * @param  int  $tag_id  Tag ID
     *
     * @throws API_Exception
     * @throws UnauthorizedException
     */
    public function addTagToContact(int $contact_id, int $tag_id)
    {
        // Tag already present
        if ($this->isTagPresent($contact_id, $tag_id)) {
            return;
        }

        // Add tag
        EntityTag::create()
            ->addValue('entity_id', $contact_id)
            ->addValue('tag_id', $tag_id)
            ->addValue('entity_table', 'civicrm_contact')
            ->execute();
    }
}
