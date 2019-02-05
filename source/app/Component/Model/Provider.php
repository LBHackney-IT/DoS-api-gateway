<?php

namespace App\Component\Model;

class Provider extends AbstractModelWithDates
{
    /**
     * @var bool
     */
    protected $published;

    /**
     * @var bool
     */
    protected $flagged;

    /**
     * @var string
     */
    protected $venueId;

    /**
     * @var string
     */
    protected $contactId;

    /**
     * Provider constructor.
     *
     * @param array $params
     *
     * @throws \Exception
     */
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->setName($this->getNamedDataItem('name'));
        $this->setPublished();
        $this->setFlagged();
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
     * @return string
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
    public function setVenueId(string $venueId): void
    {
        $this->venueId = $venueId;
    }

    /**
     * Get the contact UUID
     *
     * @return string
     */
    public function contactId()
    {
        return $this->contactId;
    }

    /**
     * Set the contact UUID.
     *
     * @param string $contactId
     */
    public function setContactId(string $contactId): void
    {
        $this->contactId = $contactId;
    }
}
