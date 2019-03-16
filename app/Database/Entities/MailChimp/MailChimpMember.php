<?php
declare(strict_types=1);

namespace App\Database\Entities\MailChimp;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Utils\Str;

/**
 * @ORM\Entity()
 */
class MailChimpMember extends MailChimpEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    private $memberId;

    /**
     * @ORM\Column(name="email_address", type="string")
     *
     * @var string
     */
    private $emailAddress;

    /**
     * @ORM\Column(name="email_type", type="string", nullable=true)
     *
     * @var string
     */
    private $emailType;

    /**
     * @ORM\Column(name="status", type="string")
     *
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(name="merge_fields", type="array", nullable=true)
     *
     * @var array
     */
    private $mergeFields;

    /**
     * @ORM\Column(name="interests", type="array", nullable=true)
     *
     * @var array
     */
    private $interests;

    /**
     * @ORM\Column(name="language", type="string", nullable=true)
     *
     * @var string
     */
    private $language;

    /**
     * @ORM\Column(name="vip", type="boolean", nullable=true)
     *
     * @var boolean
     */
    private $vip;

    /**
     * @ORM\Column(name="location", type="array", nullable=true)
     *
     * @var array
     */
    private $location;

    /**
     * @ORM\Column(name="marketing_permissions", type="array", nullable=true)
     *
     * @var array
     */
    private $marketingPermissions;

    /**
     * @ORM\Column(name="ip_signup", type="string", nullable=true)
     *
     * @var string
     */
    private $ipSignup;

    /**
     * @ORM\Column(name="timestamp_signup", type="string", nullable=true)
     *
     * @var string
     */
    private $timestampSignup;

    /**
     * @ORM\Column(name="ip_opt", type="string", nullable=true)
     *
     * @var string
     */
    private $ipOpt;

    /**
     * @ORM\Column(name="timestamp_opt", type="string", nullable=true)
     *
     * @var string
     */
    private $timestampOpt;

    /**
     * @ORM\Column(name="tags", type="array", nullable=true)
     *
     * @var array
     */
    private $tags;

    /**
     * @ORM\Column(name="list_id", type="string", nullable=true)
     *
     * @var string
     */
    private $listId;

    /**
     * @ORM\Column(name="mailchimp_member_id", type="string", nullable=true)
     *
     * @var string
     */
    private $mailchimpMemberId;

    /**
     * Get id.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->memberId;
    }

    /**
     * Get mailchimp member id.
     *
     * @return null|string
     */
    public function getMailchimpMemberId(): ?string
    {
        return $this->mailchimpMemberId;
    }

    /**
     * Get list id.
     *
     * @return null|string
     */
    public function getListId(): ?string
    {
        return $this->listId;
    }

    /**
     * Get validation rules for mailchimp entity.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'email_address' => 'required|string',
            'email_type' => 'sometimes|string',
            'status' => 'required|string',
            'merge_fields' => 'sometimes|array',
            'interests' => 'sometimes|array',
            'language' => 'sometimes|string',
            'vip' => 'sometimes|boolean',
            'location' => 'sometimes|array',
            'location.longitude' => 'sometimes|numeric',
            'location.latitude' => 'sometimes|numeric',
            'marketing_permissions' => 'sometimes|array',
            'marketing_permissions.marketing_permission_id' => 'nullable|string',
            'marketing_permissions.enabled' => 'nullable|boolean',
            'ip_signup' => 'sometimes|string',
            'timestamp_signup' => 'sometimes|string',
            'ip_opt' => 'sometimes|string',
            'timestamp_opt' => 'sometimes|string',
            'tags' => 'sometimes|array',
            'list_id' => 'required'
        ];
    }

    /**
     * Set email address.
     *
     * @param string $emailAddress
     *
     * @return MailChimpMember
     */
    public function setEmailAddress(string $emailAddress): MailChimpMember
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Set email type.
     *
     * @param string $emailType
     *
     * @return MailChimpMember
     */
    public function setEmailType(string $emailType): MailChimpMember
    {
        $this->emailType = $emailType;

        return $this;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return MailChimpMember
     */
    public function setStatus(string $status): MailChimpMember
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set merge fields.
     *
     * @param array $mergeFields
     *
     * @return MailChimpMember
     */
    public function setMergeFields(array $mergeFields): MailChimpMember
    {
        $this->mergeFields = $mergeFields;

        return $this;
    }

    /**
     * Set interests.
     *
     * @param array $interests
     *
     * @return MailChimpMember
     */
    public function setInterests(array $interests): MailChimpMember
    {
        $this->interests = $interests;

        return $this;
    }

    /**
     * Set language.
     *
     * @param string $language
     *
     * @return MailChimpMember
     */
    public function setLanguage(string $language): MailChimpMember
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Set vip.
     *
     * @param bool $vip
     *
     * @return MailChimpMember
     */
    public function setVip(bool $vip): MailChimpMember
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Set location.
     *
     * @param array $location
     *
     * @return MailChimpMember
     */
    public function setLocation(array $location): MailChimpMember
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Set marketing permissions.
     *
     * @param array $marketingPermissions
     *
     * @return MailChimpMember
     */
    public function setMarketingPermissions(array $marketingPermissions): MailChimpMember
    {
        $this->marketingPermissions = $marketingPermissions;

        return $this;
    }

    /**
     * Set ip signup.
     *
     * @param string $ipSignup
     *
     * @return MailChimpMember
     */
    public function setIpSignup(string $ipSignup): MailChimpMember
    {
        $this->ipSignup = $ipSignup;

        return $this;
    }

    /**
     * Set timestamp signup.
     *
     * @param string $timestampSignup
     *
     * @return MailChimpMember
     */
    public function setTimestampSignup(string $timestampSignup): MailChimpMember
    {
        $this->timestampSignup = $timestampSignup;

        return $this;
    }

    /**
     * Set ip opt.
     *
     * @param string $ipOpt
     *
     * @return MailChimpMember
     */
    public function setIpOpt(string $ipOpt): MailChimpMember
    {
        $this->ipOpt = $ipOpt;

        return $this;
    }

    /**
     * Set timestamp opt.
     *
     * @param string $timestampOpt
     *
     * @return MailChimpMember
     */
    public function setTimestampOpt(string $timestampOpt): MailChimpMember
    {
        $this->timestampOpt = $timestampOpt;

        return $this;
    }

    /**
     * Set tags.
     *
     * @param array $tags
     *
     * @return MailChimpMember
     */
    public function setTags(array $tags): MailChimpMember
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Set list id.
     *
     * @param string $listId
     *
     * @return MailChimpMember
     */
    public function setListId(string $listId): MailChimpMember
    {
        $this->listId = $listId;

        return $this;
    }

    /**
     * Set mailchimp member id.
     *
     * @param string $mailchimpMemberId
     *
     * @return MailChimpMember
     */
    public function setMailchimpMemberId(string $mailchimpMemberId): MailChimpMember
    {
        $this->mailchimpMemberId = $mailchimpMemberId;

        return $this;
    }

    /**
     * Get array representation of entity.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $str = new Str();

        foreach (\get_object_vars($this) as $property => $value) {
            $array[$str->snake($property)] = $value;
        }

        return $array;
    }
}
