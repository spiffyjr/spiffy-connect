<?php

namespace SpiffyConnect\Storage;

interface StorageInterface
{
    /**
     * @return mixed
     */
    public function read();

    /**
     * @param mixed $data
     * @return StorageInterface
     */
    public function write($data);
}