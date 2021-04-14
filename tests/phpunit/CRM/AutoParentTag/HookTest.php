<?php

use Civi\Test\Api3DocTrait;
use Civi\Test\ContactTestTrait;
use Civi\Test\DbTestTrait;
use Civi\Test\GenericAssertionsTrait;

/**
 * Test AutoParentTag hooks
 *
 * @group headless
 */
class CRM_AutoParentTag_HookTest extends CRM_AutoParentTag_HeadlessTestCase
{
    use Api3DocTrait;
    use GenericAssertionsTrait;
    use DbTestTrait;
    use ContactTestTrait;

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
        $contact_id = $this->individualCreate();

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
        $contact_id = $this->individualCreate();

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
