<?php

namespace App\Component\Model;

class Service extends AbstractModelWithDates
{
    /**
     * @var bool
     */
    public $flagged;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var string
     */
    public $providerId;

    /**
     * @var string|null
     */
    public $eligibilityId;

    /**
     * @var string|null
     */
    public $eventId;

    /**
     * @var string|null
     */
    public $costOptionId;

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
        $this->setFlagged();
        $this->setDescription($this->getNamedDataItem('description'));
        $this->setProviderId($this->getNamedDataItem('provider_id'));
        $this->setEventId($this->getNamedDataItem('event_id'));
        $this->setEligibilityId($this->getNamedDataItem('eligibility_id'));
        $this->setCostOptionId($this->getNamedDataItem('costoption_id'));
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

    /**
     * @return string
     */
    public function providerId(): string
    {
        return $this->providerId;
    }

    /**
     * @param string $providerId
     */
    public function setProviderId(string $providerId): void
    {
        $this->providerId = $providerId;
    }

    /**
     * @return string|null
     */
    public function eventId(): string
    {
        return $this->eventId;
    }

    /**
     * @param string|null $eventId
     */
    public function setEventId($eventId = null): void
    {
        $this->eventId = $eventId;
    }

    /**
     * @return string|null
     */
    public function eligibilityId(): string
    {
        return $this->eligibilityId;
    }

    /**
     * @param string|null $eligibilityId
     */
    public function setEligibilityId($eligibilityId = null): void
    {
        $this->eligibilityId = $eligibilityId;
    }

    /**
     * @return string|null
     */
    public function costOptionId(): string
    {
        return $this->costOptionId;
    }

    /**
     * @param string|null $costOptionId
     */
    public function setCostOptionId($costOptionId = null): void
    {
        $this->costOptionId = $costOptionId;
    }
}
