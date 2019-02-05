<?php

namespace App\Component\Model;

interface ModelInterfaceWithDates extends ModelInterface
{
    /**
     * Get the date timezone object for this model object.
     *
     * @return \DateTimeZone
     */
    public function timezone();

    /**
     * Set the date timezone for this model object using the TZ of the environment.
     *
     * @return void
     */
    public function setTimezone();

    /**
     * @return \DateTime
     */
    public function created();

    /**
     * Get a formatted string of the created time.
     *
     * @return string
     */
    public function createdString();

    /**
     * Set the created datetime from a DateTime object or string.
     *
     * @param \DateTime|string $created
     *
     * @return \DateTime
     */
    public function setCreated($created);

    /**
     * @return \DateTime
     */
    public function updated();


    /**
     * Get a formatted string of the updated time.
     *
     * @return string
     */
    public function updatedString();

    /**
     * Set the updated datetime from a DateTime object or string.
     *
     * @param \DateTime|string $updated
     *
     * @return \DateTime
     */
    public function setUpdated($updated);

    /**
     * Get the date format.
     *
     * @return string
     */
    public function format();

    /**
     * Set the date format.
     *
     * @param string $format
     *
     * @return void
     */
    public function setFormat(string $format);

    /**
     * Set the default date format.
     *
     * @param string|null $format
     *
     * @return void
     */
    public function setDefaultFormat($format);
}
