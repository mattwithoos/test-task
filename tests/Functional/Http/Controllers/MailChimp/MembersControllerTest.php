<?php
declare(strict_types=1);

namespace Tests\App\Functional\Http\Controllers\MailChimp;

use Tests\App\TestCases\MailChimp\MemberTestCase;

class MembersControllerTest extends MemberTestCase
{
    /**
     * Test application creates successfully list and returns it back with id from MailChimp.
     *
     * @return void
     */
    public function testCreateMemberSuccessfully(): void
    {
        $this->post('/mailchimp/members', $this->sampleMemberData());

        $content = \json_decode($this->response->getContent(), true);

        $this->assertResponseOk();
        self::assertArrayHasKey('member_id', $content);
        self::assertNotNull($content['member_id']);

        $this->createdMemberIds[] = $content['member_id']; // Store internal member id for cleaning purposes
    }

    /**
     * Test application returns error response with errors when member validation fails.
     *
     * @return void
     */
    public function testCreateMemberValidationFailed(): void
    {
        $this->post('/mailchimp/members');

        $content = \json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(400);
        self::assertArrayHasKey('message', $content);
        self::assertArrayHasKey('errors', $content);
        self::assertEquals('Invalid data given', $content['message']);

        foreach (\array_keys($this->sampleMemberData()) as $key) {
            self::assertArrayHasKey($key, $content['errors']);
        }
    }

    /**
     * Test application returns error response when member not found.
     *
     * @return void
     */
    public function testRemoveMemberNotFoundException(): void
    {
        $this->delete('/mailchimp/members/invalid-member-id');

        $this->assertResponseStatus(405);
    }

    /**
     * Test application returns empty successful response when removing existing member.
     *
     * @return void
     */
    public function testRemoveMemberSuccessfully(): void
    {
        $this->post('/mailchimp/members', $this->sampleMemberData());
        $member = \json_decode($this->response->content(), true);

        $this->delete(\sprintf('/mailchimp/members/%s/%s', $member['member_id'], $member['list_id']));

        $this->assertResponseOk();
        self::assertEmpty(\json_decode($this->response->content(), true));
    }

    /**
     * Test application returns error response when member not found.
     *
     * @return void
     */
    public function testShowMemberNotFoundException(): void
    {
        $this->get('/mailchimp/members/invalid-member-id');

        $this->assertMemberNotFoundResponse('invalid-member-id');
    }

    /**
     * Test application returns successful response with member data when requesting existing member.
     *
     * @return void
     */
    public function testShowMemberSuccessfully(): void
    {
        $member = $this->createMember($this->sampleMemberData());

        $this->get(\sprintf('/mailchimp/members/%s', $member->getId()));
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseOk();

        foreach ($member as $key => $value) {
            self::assertArrayHasKey($key, $content);
            self::assertEquals($value, $content[$key]);
        }
    }

    /**
     * Test application returns error response when member not found.
     *
     * @return void
     */
    public function testUpdateMemberNotFoundException(): void
    {
        $this->put('/mailchimp/members/invalid-member-id');

        $this->assertMemberNotFoundResponse('invalid-member-id');
    }

    /**
     * Test application returns successfully response when updating existing member with updated values.
     *
     * @return void
     */
    public function testUpdateMemberSuccessfully(): void
    {
        $newData = $this->sampleMemberData();
        $newData['vip'] = true;

        $this->post('/mailchimp/members', $newData);
        $member = \json_decode($this->response->content(), true);

        if (isset($member['mailchimp_member_id'])) {
            $this->createdMemberIds[] = $member['member_id']; // Store MailChimp member id for cleaning purposes
        }

        $this->put(\sprintf('/mailchimp/members/%s', $member['member_id']), $newData);
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseOk();
        $this->assertEquals($content['vip'], true);
    }

    /**
     * Test application returns error response with errors when member validation fails.
     *
     * @return void
     */
    public function testUpdateMemberValidationFailed(): void
    {
        $member = $this->createMember($this->sampleMemberData());

        $this->sampleMemberData()['vip'] = 'hello there';

        $this->put(\sprintf('/mailchimp/members/%s', $member->getId()), $this->sampleMemberData());
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseStatus(400);
        self::assertArrayHasKey('message', $content);
    }
}
