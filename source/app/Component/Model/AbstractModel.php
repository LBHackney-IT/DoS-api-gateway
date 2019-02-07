<?php

namespace App\Component\Model;

use App\Component\Model\HttpResponse\HttpModelResponse;

abstract class AbstractModel implements ModelInterface
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    protected $responseItems = [];

    /**
     * AbstractModel constructor.
     *
     * @param object $params
     */
    public function __construct(object $params)
    {
        $this->setId($params->id);
        $this->setType($params->type);
        $this->setData((object) $params->data);
    }

    /**
     * {@inheritdoc}
     */
    public function setData(object $data)
    {
        $this->data = $data;
    }

    /**
     * Get the model object data array.
     *
     * @return array
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Get a named data item.
     *
     * @param $name
     *
     * @return mixed|null
     */
    protected function getNamedDataItem($name)
    {
        return empty($this->getData()->$name) ? null : $this->getData()->$name;
    }

    /**
     * {@inheritdoc}
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
