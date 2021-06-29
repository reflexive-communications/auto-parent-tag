<?php

use Civi\Api4\Contact;
use Civi\Api4\UFMatch;

/**
 * Test AutoParentTag hooks
 *
 * @group headless
 */
class CRM_AutoParentTag_HookTest extends CRM_AutoParentTag_HeadlessTestCase
{
    /**
     * Simulate a logged in user
     *
     * @return int Contact ID
     *
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function createLoggedInUser(): int
    {
        $contact = Contact::create()
            ->addValue('contact_type', 'Individual')
            ->execute()
            ->first();

        // Create UF match, uf_id is the ID of the user in the CMS
        // Now it is 42, it don't have to be a real user ID
        UFMatch::create()
            ->addValue('uf_id', 42)
            ->addValue('contact_id', $contact['id'])
            ->execute()
            ->first();

        // Set ID in session
        $session = CRM_Core_Session::singleton();
        $session->set('userID', $contact['id']);
        return $contact['id'];
    }

    /**
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     */
    public function testPostCommitHookWithLoggedInUser()
    {
        $this->createLoggedInUser();

        // Create tags
        $tag = [
            'name' => 'Parent tag',
        ];
        $parent_tag_id = CRM_RcBase_Test_Utils::cvApi4Create('Tag', $tag);
        $tag = [
            'name' => 'Child tag',
            'parent_id' => $parent_tag_id,
        ];
        $child_tag_id = CRM_RcBase_Test_Utils::cvApi4Create('Tag', $tag);

        // Create contact
        $contact_id = Contact::create()
            ->addValue('contact_type', 'Individual')
            ->execute()
            ->first()['id'];

        // Add child tag to contact
        civicrm_api4('EntityTag', 'create', [
            'values' => [
                'entity_table' => 'civicrm_contact',
                'entity_id' => $contact_id,
                'tag_id' => $child_tag_id,
            ],
        ]);

        // Check if contact has parent tag
        $id = CRM_RcBase_Test_Utils::cvApi4Get('EntityTag', ['id'], [
            'entity_table=civicrm_contact',
            "entity_id=${contact_id}",
            "tag_id=${parent_tag_id}",
        ]);
        self::assertCount(1, $id, 'Parent tag missing from contact');
    }

    /**
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     */
    public function testPostCommitHookWithoutLoggedInUser()
    {
        // Create tags
        $tag = [
            'name' => 'Parent tag not logged in',
        ];
        $parent_tag_id = CRM_RcBase_Test_Utils::cvApi4Create('Tag', $tag);
        $tag = [
            'name' => 'Child tag not logged in',
            'parent_id' => $parent_tag_id,
        ];
        $child_tag_id = CRM_RcBase_Test_Utils::cvApi4Create('Tag', $tag);

        // Create contact
        $contact_id = Contact::create()
            ->addValue('contact_type', 'Individual')
            ->execute()
            ->first()['id'];

        // Add child tag to contact
        civicrm_api4('EntityTag', 'create', [
            'values' => [
                'entity_table' => 'civicrm_contact',
                'entity_id' => $contact_id,
                'tag_id' => $child_tag_id,
            ],
        ]);

        // Check if contact has no parent tag
        $id = CRM_RcBase_Test_Utils::cvApi4Get('EntityTag', ['id'], [
            'entity_table=civicrm_contact',
            "entity_id=${contact_id}",
            "tag_id=${parent_tag_id}",
        ]);
        self::assertCount(0, $id, 'Parent tag added to contact');
    }
}
