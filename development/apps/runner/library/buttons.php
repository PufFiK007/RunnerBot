<?php

namespace Library;

use Model\Entities\Message;

trait Buttons
{
    
    public function getButtons(string $parent, ?bool $isLect = null): array|null
    {
        $result = [];
    if($parent == '33'){
        if(!$isLect){
        $buttons = Message::search(parent: (int)$parent, limit: 10);
        foreach ($buttons as $button) {
            if($button->id < 28)
            $result [] = ['id' => (string)$button->id, 'title' => $button->title];
            }
            return  array_chunk($result, 2);
        }
        else{
        $buttons = Message::search(parent: (int)$parent, limit: 10);
        foreach ($buttons as $button) {
            if($button->id == 25 || $button->id > 27)
            $result [] = ['id' => (string)$button->id, 'title' => $button->title];
            }
        }
        return array_chunk($result, 2);
    }

    else if($parent == '26'){
        $buttons = Message::search(parent: (int)$parent, limit: 10);
        foreach ($buttons as $button) {
            $result [] = ['id' => (string)$button->id, 'title' => $button->title];
        }
        return array_chunk($result, 1);
    }
    else {
        $buttons = Message::search(parent: (int)$parent, limit: 10);
        foreach ($buttons as $button) {
            $result [] = ['id' => (string)$button->id, 'title' => $button->title];
        }
        return array_chunk($result, 2);
    }
    }

    public function getText(string $id): string
    {
        $message = Message::search((int)$id, limit: 1);
        $text = $message->text;
        return $text;
    }
    public function getCode(string $id): string
    {
        $message = Message::search((int)$id, limit: 1);
        $return_code = $message->code;
        return $return_code;
    }
}
    ?>