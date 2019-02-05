<?php

namespace App\Component\Model;

class Provider extends AbstractModelWithDates
{
    /**
     * @var bool
     */
    public $published;

    /**
     * @var bool
     */
    public $flagged;

    /**
     * @var string|null
     */
    public $venueId;

    /**
     * @var string|null
     */
    public $contactId;

    /**
     * @var string|null
     */
    public $description;

    protected $responseItems = [
        'id' => 'id',
        'name' => 'name',
        'description' => 'description',
    ];

    /**
     * Provider constructor.
     *
     * @param object $params
     *
     * @throws \Exception
     */
    public function __construct(object $params)
    {
        parent::__construct($params);
        $this->setName($this->getNamedDataItem('name'));
        $this->setPublished();
        $this->setFlagged();
        $this->setContactId($this->getNamedDataItem('contact_id'));
        $this->setVenueId($this->getNamedDataItem('venue_id'));
        $this->setDescription($this->getNamedDataItem('description'));
    }

    /**
     * Is the provider published?
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * Set the provider published flag.
     *
     * return void
     */
    public function setPublished()
    {
        $this->published = !empty($this->getNamedDataItem('published'));
    }

    /**
     * Is the provider flagged?
     *
     * @return bool
     */
    public function isFlagged()
    {
        return $this->flagged;
    }

    /**
     * Set the provider flagged flag.
     *
     * return void
     */
    public function setFlagged()
    {
        $this->flagged = !empty($this->getNamedDataItem('flagged'));
    }

    /**
     * Get the venue UUID
     *
     * @return string|null
     */
    public function venueId()
    {
        return $this->venueId;
    }

    /**
     * Set the venue UUID.
     *
     * @param string $venueId
     */
    public function setVenueId($venueId): void
    {
        $this->venueId = $venueId;
    }

    /**
     * Get the contact UUID
     *
     * @return string|null
     */
    public function contactId()
    {
        return $this->contactId;
    }

    /**
     * Set the contact UUID.
     *
     * @param string|null $contactId
     */
    public function setContactId($contactId): void
    {
        $this->contactId = $contactId;
    }

    /**
     * @return string|null
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
