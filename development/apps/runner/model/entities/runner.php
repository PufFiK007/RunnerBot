<?php

namespace Model\Entities;

use Entities\Student;

class Runner
{

    use \Library\Shared;
    use \Library\Entity;

    private ?\Library\MySQL $db;

    public function __construct(public int $id = 0, public ?string $student = null, public ?string $lecturer = null, public ?string $date = null, public ?string $subject = null, public ?int $mark = null, public int $input = 0)
    {
        $this->db = $this->getDB();
    }

    public static function search(?string $student = "", ?string $subject = '', ?int $limit = 1): self|array|null
    {
        $result = [];
        foreach (['student', 'subject'] as $var)
            if ($$var)
                $filters[$var] = $$var;
        $db = self::getDB();
        $runners = $db->select(['Runners' => []]);
        if (!empty($filters))
            $runners->where(['Runners' => $filters]);
        foreach ($runners->many($limit) as $runner) {
            $class = __CLASS__;
            $result[] = new $class($runner['id'], $runner['student'], $runner['lecturer'], $runner['date'], $runner['subject'], $runner['mark'], $runner['Input']);
        }
        return $limit == 1 ? ($result[0] ?? null) : $result;
    }

    public static function searchById(?int $id = 0, ?int $limit = 1): self|array|null
    {
        $result = [];
        foreach (['id'] as $var)
            if ($$var)
                $filters[$var] = $$var;
        $db = self::getDB();
        $runners = $db->select(['Runners' => []]);
        if (!empty($filters))
            $runners->where(['Runners' => $filters]);
        foreach ($runners->many($limit) as $runner) {
            $class = __CLASS__;
            $result[] = new $class($runner['id'], $runner['student'], $runner['lecturer'], $runner['date'], $runner['subject'], $runner['mark'], $runner['Input']);
        }
        return $limit == 1 ? ($result[0] ?? null) : $result;
    }

    public static function getAllRunners(string $student = ""): self|array|null
    {
        $result = [];
        foreach (['student'] as $var)
            if ($$var)
                $filters[$var] = $$var;
        $db = self::getDB();
        $runners = $db->select(['Runners' => []]);
        if (!empty($filters))
            $runners->where(['Runners' => $filters]);
        foreach ($runners->many(10) as $runner) {
            $class = __CLASS__;
            $result[] = new $class($runner['id'], $runner['student'], $runner['lecturer'], $runner['date'], $runner['subject'], $runner['mark'], $runner['Input']);
        }
        return $result;
    }
    
   public static function getAll(): self|array|null
    {
        $isFirst = true;
        $result = [];
        $db = self::getDB();
        $runners = $db->select(['Runners' => null]);
        foreach ($runners->many(10) as $runner) {
            if ($isFirst)
            {
                $isFirst = false;
                continue;
            }
            $class = __CLASS__;
            $result[] = new $class($runner['id'], $runner['student'], $runner['lecturer'], $runner['date'], $runner['subject'], $runner['mark'], $runner['Input']);
        }
        return $result;
    }

    public function save(): self
    {
        $db = $this->db;
        if (!$this->id) {
            $val = [
                'student' => $this->student,
                'lecturer' => $this->lecturer,
                'date' => $this->date,
                'subject' => $this->subject,
                'mark' => $this->mark,
                'input' => $this->input
            ];
            $this->id = $db->insert([
                'Runners' => $val
            ])->run(true)->storage['inserted'];
        }

        if (!empty($this->_changed)) {
            $db->update('Runners', (array)$this->_changed)
                ->where(['Runners' => ['id' => $this->id]])
                ->run();
        }
        return $this;
    }

    function getInput(){
        return $this->input;
    }

    public function toStr()
    {
        $run_arr = array($this->student, $this->subject, $this->lecturer, $this->date, $this->mark);
        $strRun = implode(' | ', $run_arr);
        return "$strRun";
    }

    public function getStudent()
    {
        return $this->student;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getId()
    {
        return $this->id;
    }

    function toNational($mark)
    {
        switch ($mark) {
            case 1 - 49:
                return 2;
                break;

            case 50 - 64:
                return 3;
                break;

            case 65 - 89:
                return 4;
                break;

            case 90 - 100:
                return 5;
                break;

            default:
                break;
        }
    }
}

?>