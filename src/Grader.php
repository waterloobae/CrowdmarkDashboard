<?php

namespace Waterloobae\CrowdmarkDashboard;

class Grader{
    protected string $user_id;
    protected string $name;
    protected string $email;
 
    public function __construct(object $grader)
    {
        $this->user_id = $grader->id;
        $this->name = $grader->attributes->name ?? "NA";
        $this->email = $grader->attributes->email;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }
 
}
