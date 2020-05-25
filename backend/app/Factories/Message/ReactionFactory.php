<?php

namespace App\Factories\Message;
use App\Implementations\Message\SaveReaction;
use App\Implementations\Message\UpdateReaction;
use App\Implementations\Message\DeleteReaction;

class ReactionFactory 
{
    public function initializeReaction($type) 
    {
        if($type == "save") {
            return new SaveReaction();
        } else if ($type == "update") {
            return new UpdateReaction();
        } else {
            return new DeleteReaction();
        }
    }
}