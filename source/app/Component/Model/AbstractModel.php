<?php

namespace App\Component\Model;

abstract class AbstractModel implements ModelInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * AbstractModel constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->setId($params['id']);
        $this->setType($params['type']);
        $this->setData($params['data']);
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the model object data array.
     *
     * @return array
     */
    private function getData()
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
        return empty($this->getData()[$name]) ? null : $this->getData()[$name];
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
