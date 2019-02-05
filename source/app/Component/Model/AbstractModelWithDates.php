<?php

namespace App\Component\Model;

use Exception;
use DateTime;

abstract class AbstractModelWithDates extends AbstractModel implements ModelInterfaceWithDates
{
    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * @var string
     */
    protected $formatShort = 'd/m/y';

    /**
     * @var string
     */
    protected $formatShortTime = 'd/m/y H:i:s';

    /**
     * @var string
     */
    protected $formatMedium = 'j M Y';

    /**
     * @var string
     */
    protected $formatMediumTime = 'j M Y H:i:s';

    /**
     * @var string
     */
    protected $formatLong = 'j F, Y';

    /**
     * @var string
     */
    protected $formatLongTime = 'j F, Y H:i:s';

    /**
     * @var string
     */
    protected $defaultFormat;

    /**
     * @var string
     */
    private $format;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * AbstractModelWithDates constructor.
     *
     * @param array $params
     *
     * @throws \Exception
     */
    public function __construct(array $params)
    {
        parent::__construct($params);
        // Set the timezone for handling created/updated dates.
        $this->setTimezone();
        $this->setDefaultFormat();
        $this->setCreated($this->getNamedDataItem('created'));
        $this->setUpdated($this->getNamedDataItem('updated'));
    }

    /**
     * {@inheritdoc}
     */
    public function timezone()
    {
        return $this->timezone;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimezone(): void
    {
        $tz = env('TZ', 'Europe/London');
        $timezone = new \DateTimeZone($tz);
        $this->timezone = $timezone;
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return $this->format ? $this->format : $this->defaultFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    public function setDefaultFormat($format = null)
    {
        $this->defaultFormat = $format ? $format : $this->formatMediumTime;
    }

    /**
     * {@inheritdoc}
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    public function createdString()
    {
        return $this->created()->format($this->format());
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function setCreated($created)
    {
        switch (gettype($created)) {
            case 'string':
                $time = new DateTime($created, $this->timezone());
                $this->created = $time;
                break;
            case 'object':
                if (get_class($created) == 'DateTime') {
                    $this->created = $created;
                } else {
                    throw new Exception('Created date is not a DateTime object.');
                }
                break;
            default:
                throw new Exception('Cannot set created date.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updated()
    {
        return $this->updated;
    }

    /**
     * {@inheritdoc}
     */
    public function updatedString()
    {
        return $this->updated()->format($this->format());
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function setUpdated($updated)
    {
        switch (gettype($updated)) {
            case 'string':
                $time = new DateTime($updated, $this->timezone());
                $this->updated = $time;
                break;
            case 'object':
                if (get_class($updated) == 'DateTime') {
                    $this->updated = $updated;
                } else {
                    throw new Exception('Updated date is not a DateTime object.');
                }
                break;
            default:
                throw new Exception('Cannot set updated date.');
        }
    }
}
