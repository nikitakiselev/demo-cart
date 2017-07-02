<?php

namespace Nikitakiselev\DemoCart\Storage;

use Illuminate\Support\Manager;

class StorageManager extends Manager
{
    public function createDatabaseDriver()
    {
        return $this->app->make(DatabaseStorageDriver::class);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']->get('demo-cart.storage');
    }
}
