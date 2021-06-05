<?php

namespace Model\Entities;

class Lecturer
{ 
    public $name, $caphedra;

    function search(?Int $chat = 0, ?String $guid = '', Int $limit = 0):self|array|null){
        
        $result = [];
        foreach (['chat', 'guid'] as $var)
            if ($$var)
                $filters[$var] = $$var;
        $db = self::getDB();
        $users = $db -> select(['Users' => []]);
        if(!empty($filters))
            $users->where(['Users' => $filters]);
        foreach ($users->many($limit) as $user) {
                $class = __CLASS__;
                $result[] = new $class($user['id'], $chat, $user['guid'], $user['message'], $user['service'], $user['input']);
        }
        return $limit == 1 ? (isset($result[0]) ? $result[0] : null) : $result;
    }
    function getName(){
        return $name;
    }

    function putMark(string $runnerid){
        //Runner->Search();
    };
}
