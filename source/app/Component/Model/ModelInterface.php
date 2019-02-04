<?php

namespace App\Component\Model;

interface ModelInterface
{
    /**
     * Get the model object UUID.
     *
     * @return string
     */
    public function id();

    /**
     * Set the model UUID.
     *
     * @param string $id
     *
     * @return void
     */
    public function setId(string $id);

    /**
     * Get the model object type.
     *
     * @return string
     */
    public function type();

    /**
     * Set the model object type.
     *
     * @param string $type
     *
     * @return void
     */
    public function setType(string $type);

    /**
     * Get the label name of the model object.
     *
     * @return string
     */
    public function name();

    /**
     * Set the model object label name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name);

    /**
     * Set the model object data.
     *
     * @param array $data
     *
     * @return void
     */
    public function setData(array $data);
}
