<?php
/**
 * Spiral skeleton application
 *
 * @author Wolfy-J
 */

use Spiral\Core\Core;
use Spiral\Debug;

/**
 * Application core. You can rename this class to reflect your project name.
 */
class App extends Core
{
    /**
     * List of classes and bootloaders to be initiated with your application.
     *
     * Attention, bootloader's bindings are compiled and cached, to reload application cache run
     * command "app:reload" (disabled by default in .env).
     *
     * @see \Spiral\Core\Bootloaders\Bootloader
     * @var array
     */
    const LOAD = [
        //Short bindings to spiral services (eg http, db, ...)
        \Spiral\Core\Bootloaders\SpiralBindings::class,

        //Application specific bindings and bootloading
        \Bootloaders\AppBootloader::class,

        //Routes, middlewares and etc
        \Bootloaders\HttpBootloader::class,
    ];

    /**
     * Application core bootloading, you can configure your environment here.
     */
    protected function bootstrap()
    {
        /*
         * Debug mode automatically enables spiral profiler or any other bootloaders listed
         * in a following method. In addition, it sets different snapshot class which provides 
         * ability to render error information in a nicely form.
         */
        env('DEBUG') && $this->enableDebugging();
    }

    /**
     * Debug packages.
     */
    private function enableDebugging()
    {
        //Initiating all needed binding (no need to use memory caching)
        $this->getBootloader()->bootload([
            \Spiral\Profiler\ProfilerBootloader::class,

            //Other debug modules, for example automatic orm/odm schema refresh middleware/service
            //can be enabled here
        ]);

        /*
         * This snapshot class provides ability to render exception trace in a nicely form, you
         * can keep this implementation enabled in your production env - rendered snapshots are stored
         * on a disk in `app/runtime/snapshots` directory, user will only see 500 error.
         */
        $this->container->bind(Debug\SnapshotInterface::class, Debug\Snapshot::class);

        //P.S. You can always overwrite method App->getSnapshot($exception)
    }
}

if (!function_exists('app')) {
    /**
     * You can change this function to any form or remove it. Attention,
     * can only be executed inside your application.
     *
     * @return App
     * @throws \Spiral\Core\Exceptions\ScopeException
     */
    function app(): App
    {
        return App::sharedContainer()->get(App::class);
    }
}
