<?php

namespace Model\Entities;

class Student
{ 
    use \Library\Shared;
    use \Library\Entity;

    private ?\Library\MySQL $db;

    function getName(){
        return $name;
    }


    function __construct(string $name, string $group){
        
    }

    function putMark($runner){}
}

?>