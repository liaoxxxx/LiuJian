<?php


namespace common\jobs;


use common\interfaces\JobInterface;
use think\queue\Job;

class TestJob implements JobInterface
{
    public function fire(Job $job, $data): void
    {
        var_dump($data);
        $job->delete();
    }
}