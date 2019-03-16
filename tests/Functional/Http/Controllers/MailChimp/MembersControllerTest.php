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
        $this->post('/mailchimp/lists/'.static::$memberData['list_id'].'/members', static::$memberData);

        $content = \json_decode($this->response->getContent(), true);

        $this->assertResponseOk();
        $this->seeJson(static::$memberData);
        self::assertArrayHasKey('mail_chimp_id', $content);
        self::assertNotNull($content['mail_chimp_id']);

        $this->createdMemberIds[] = $content['mail_chimp_id']; // Store MailChimp member id for cleaning purposes
    }

    /**
     * Test application returns error response with errors when member validation fails.
     *
     * @return void
     */
    public function testCreateMemberValidationFailed(): void
    {
        $this->post('/mailchimp/lists/'.static::$memberData['list_id'].'/members');

        $content = \json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(400);
        self::assertArrayHasKey('message', $content);
        self::assertArrayHasKey('errors', $content);
        self::assertEquals('Invalid data given', $content['message']);

        foreach (\array_keys(static::$memberData) as $key) {
            if (\in_array($key, static::$notRequired, true)) {
                continue;
            }

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
        $this->delete('/mailchimp/lists/invalid-list-id/members/invalid-member-id');

        $this->assertMemberNotFoundResponse('invalid-member-id');
    }

    /**
     * Test application returns empty successful response when removing existing list.
     *
     * @return void
     */
    public function testRemoveMemberSuccessfully(): void
    {
        $this->post('/mailchimp/lists/'.static::$memberData['list_id'].'/members', static::$memberData);
        $member = \json_decode($this->response->content(), true);

        $this->delete(\sprintf('/mailchimp/lists/%s/%s', $member['list_id'], $member['id']));

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
        $this->get('/mailchimp/lists/invalid-list-id/members/invalid-member-id');

        $this->assertListNotFoundResponse('invalid-member-id');
    }

    /**
     * Test application returns successful response with member data when requesting existing member.
     *
     * @return void
     */
    public function testShowMemberSuccessfully(): void
    {
        $member = $this->createMember(static::$memberData);

        $this->get(\sprintf('/mailchimp/lists/%s/members/%s', static::$memberData['list_id'], $member->getId()));
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseOk();

        foreach (static::$memberData as $key => $value) {
            self::assertArrayHasKey($key, $content);
            self::assertEquals($value, $content[$key]);
        }
    }

    /**
     * Test application returns error response when member not found.
     *
     * @return void
     */
    public function testUpdateListNotFoundException(): void
    {
        $this->put('/mailchimp/lists/invalid-list-id/members/invalid-member-id');

        $this->assertListNotFoundResponse('invalid-member-id');
    }

    /**
     * Test application returns successfully response when updating existing member with updated values.
     *
     * @return void
     */
    public function testUpdateMemberSuccessfully(): void
    {
        $this->post('/mailchimp/lists/'.static::$memberData['list_id'].'/members', static::$memberData);
        $member = \json_decode($this->response->content(), true);

        if (isset($member['mail_chimp_id'])) {
            $this->createdMemberIds[] = $member['mail_chimp_id']; // Store MailChimp member id for cleaning purposes
        }

        $this->put(\sprintf('/mailchimp/lists/%s/members/%s', $member['list_id'], $member['id']), ['vip' => true]);
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseOk();

        foreach (\array_keys(static::$memberData) as $key) {
            self::assertArrayHasKey($key, $content);
            self::assertEquals('updated', $content['vip']);
        }
    }

    /**
     * Test application returns error response with errors when member validation fails.
     *
     * @return void
     */
    public function testUpdateListValidationFailed(): void
    {
        $member = $this->createMember(static::$memberData);

        $this->put(\sprintf('/mailchimp/lists/%s/members/%s', $member->getListId(), $member->getId()), ['vip' => 'hello there']);
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseStatus(400);
        self::assertArrayHasKey('message', $content);
        self::assertArrayHasKey('errors', $content);
        self::assertArrayHasKey('visibility', $content['errors']);
        self::assertEquals('Invalid data given', $content['message']);
    }
}
