<?php

use Civi\Test\ContactTestTrait;

/**
 * Test AutoParentTag Processor
 *
 * @group headless
 */
class CRM_AutoParentTag_ProcessorHeadlessTest extends CRM_AutoParentTag_HeadlessTestCase
{
    use ContactTestTrait;

    /**
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     */
    public function testAddParentTag()
    {
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

        // Add parent tag
        $entity_tag_id_returned = CRM_AutoParentTag_Processor::addParentTag($contact_id, $child_tag_id);

        // Check if contact actually has parent tag
        $id = CRM_RcBase_Test_Utils::cvApi4Get('EntityTag', ['id'], [
            'entity_table=civicrm_contact',
            "entity_id=${contact_id}",
            "tag_id=${parent_tag_id}",
        ]);
        self::assertCount(1, $id, 'Parent tag missing from contact');
        self::assertSame($id[0]['id'], $entity_tag_id_returned, 'Bad entity tag ID returned');

        // Add parent tag to parent
        self::assertNull(CRM_AutoParentTag_Processor::addParentTag($contact_id, $parent_tag_id), 'Not null returned for parent tag');
    }
}
