<?php

namespace App\Factories\Message;
use App\Implementations\Message\RemoveForMyself;
use App\Implementations\Message\RemoveForEveryone;
use App\Implementations\Message\RemoveAllMessages;

class RemoveMessageFactory 
{
    public function initializeRemoveMessage($type) 
    {
        if($type == "removeForAll") {
            return new RemoveForEveryone();
        } else if ($type == "removeForMyself") {
            return new RemoveForMyself();
        } else {
            return new RemoveAllMessages();
        }
    }
}