<?php

namespace Nikitakiselev\DemoCart\Storage;

interface CartStorage
{
    /**
     * Store a data.
     *
     * @param string $key
     * @param array $data
     *
     * @return mixed
     */
    public function store(string $key, array $data);

    /**
     * Get the data by key.
     *
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array;

    /**
     * Forget data.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function forget(string $key);
}
