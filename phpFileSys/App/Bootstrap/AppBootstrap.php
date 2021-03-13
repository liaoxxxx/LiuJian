<?php


namespace App\Bootstrap;


class AppBootstrap
{

    public static function boot()
    {
        HotReload::boot();
        CrossOrigin::boot();
        Queue::boot();
    }
}
