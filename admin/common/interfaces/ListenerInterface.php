<?php


namespace common\interfaces;


interface ListenerInterface
{
    public function handle($event): void;
}