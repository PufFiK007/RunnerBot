<?php
/**
 * User Controller
 *
 * @author Serhii Shkrabak
 * @global object $CORE->model
 * @package Model\Main
 */
namespace Model;

 use Library\Buttons;
 use Model\Entities\Runner;
 use Model\Entities\Student;


class Main
{
    use Buttons;

	use \Library\Shared;
	use \Library\Uniroad;

	public function uniwebhook(String $type = '', String $value = '', Int $code = 0):?array {
		$result = null;
		switch ($type) {
			case 'message':
				if ($value == 'вихід') {
					$result = ['type' => 'context', 'set' => null];
				}
				else if($value == '/start')
				{
					if($this->isStudent() == true){
					$name = $this->getName();
					$buttons = $this->getButtons('33');
					$result = [
						'to' => $GLOBALS['uni.user'],
						'type' => 'message',
						'value' => "Привіт, $name 👋, бажаєш виправити оцінку?",
						'keyboard' => [
							'buttons' => $buttons
							]
						
					];
					}
					else{
						$name = $this->getFullName();
						$buttons = $this->getButtons('33', true);
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => "Доброго дня, $name",
							'keyboard' => [
								'buttons' => $buttons
								]
							
						];
						}						
					
				}
				break;
				case 'click':
					switch ($code) {
						case 33:
							if($this->isStudent() == true){
							$name = $this->getName();
								$buttons = $this->getButtons('33');
								$result = [
									'to' => $GLOBALS['uni.user'],
									'type' => 'message',
									'value' => "Привіт, $name 👋, бажаєш виправити оцінку?",
									'keyboard' => [
										'buttons' => $buttons
									]
								
							];			
						}
						else{
							$name = $this->getFullName();
							$buttons = $this->getButtons('33', true);
							$result = [
								'to' => $GLOBALS['uni.user'],
								'type' => 'message',
								'value' => "Доброго дня, $name",
								'keyboard' => [
									'buttons' => $buttons
									]
							];
							}
						break;

						case 25:
							$result = ['type' => 'context', 'set' => null];
							break;

						case 26:
							$buttons = $this->getButtons('26');
							$result = [
								'to' => $GLOBALS['uni.user'],
								'type' => 'message',
								'value' => "Вибери предмет зі списку⬇",
								'keyboard' => [
									'buttons' => $buttons
								]
							];  
							break;

						case 41:
						case 42:
						case 43:
						case 44:
						case 45:
						case 46:
						case 47:
						case 48:
						case 49:
							$r = $this->retRunner();
							$r->set(["input" => $code]);
							$r->save();
							$buttons = $this->getButtons('41');
							$subject = $this->getCode($code);
							$result = [
								'to' => $GLOBALS['uni.user'],
								'type' => 'message',
								'value' => "Ти впевнений, що хочеш перездати предмет $subject?",
								'keyboard' => [
									'buttons' => $buttons
								]
							]; 
							break;

						case 99:
							$buttons = $this->getButtons('33');
							$r = $this->retRunner();
							$s_code = $r->getInput();
							if(!$this->checkRunner($s_code)){							
								$result = [
									'to' => $GLOBALS['uni.user'],
									'type' => 'message',
									'value' => "В тебе вже є бігунок для цього предмету😠",
									'keyboard' => [
										'buttons' => $buttons
									]
								];

							}
							else{
								$this->getRunner($s_code);
							$result = [
								'to' => $GLOBALS['uni.user'],
								'type' => 'message',
								'value' => "Бігунок створено! Бажаємо успіхів🤗",
								'keyboard' => [
									'buttons' => $buttons
								]
							];
						}
							break;
						case 100:
							$buttons = $this->getButtons('33');
								$result = [
									'to' => $GLOBALS['uni.user'],
									'type' => 'message',
									'value' => "Ні, так ні😌",
									'keyboard' => [
										'buttons' => $buttons
									]
								];
								break;
						case 27:
							$pr = $this->runnersToPrint();
							$buttons = $this->getButtons(33);
							$result = [
								'to' => $GLOBALS['uni.user'],
								'type' => 'message',
								'value' => "Список твоїх бігунків🧾 \n $pr \n❗Май на увазі - бігунки без оцнки ще не здані❗",
								'keyboard' => [
									'buttons' => $buttons
								]
							];  
							break;

						case 28:
								$buttons = $this->runnersToButtons();
								$result = [
									'to' => $GLOBALS['uni.user'],
									'type' => 'message',
									'value' => "Список ваших боржників 🧾",
									'keyboard' => [
										'buttons' => $buttons
									]
								];  
								break;
						
						
						
						case 52:
						case 53:
						case 54:
						case 55:
							$r = $this->retRunner();
							$s_code = $r->getInput();
							$this->putMark($s_code-160, $code-50);
							$buttons = $this->getButtons(33, true);
							$result = [
									'to' => $GLOBALS['uni.user'],
									'type' => 'message',
									'value' => "Оцінку виставлено, дякуємо за користування нашим ботом🤖",
									'keyboard' => [
										'buttons' => $buttons
									]
								];  
							break;
						}
					}
					if($code > 150){	
						$r = $this->retRunner();
						$r->set(["input" => $code]);
						$r->save();					
						$buttons = $this->getButtons('100');
						$result = [
							'to' => $GLOBALS['uni.user'],
							'type' => 'message',
							'value' => "Виберіть нову оцінку",
							'keyboard' => [
								'buttons' => $buttons
							]
						];  
					}



		return $result;
	}
	
	 function isStudent()
	{
		//lect - 468328a4-a4f8-11eb-b425-0242ac130004
		//stud - 31e80b14-a4f8-11eb-b425-0242ac130004
		$response = $this->uni()->get('accounts', ['type' => 'person', 'user' => '31e80b14-a4f8-11eb-b425-0242ac130004'], 'account/list')->one()[0];
		printme($response);
		if ($response['isLecturer'] != true)
		return false;
	}

	function getName(){
		$response = $this->uni()->get('accounts', ['type' => 'person', 'user' => '31e80b14-a4f8-11eb-b425-0242ac130004'], 'account/list')->one()[0];
		return $response['name'];

	}
	function getFullName(){
		$response = $this->uni()->get('accounts', ['type' => 'person', 'user' => '31e80b14-a4f8-11eb-b425-0242ac130004'], 'account/list')->one()[0];
		$l_name = (string)$response['name'];
		$l_surname = (string)$response['surname'];
		$res = "$l_name $l_surname";
		return $res;

	}
	function getStudInfo()
	{
	    $response = $this->uni()->get('accounts', ['type' => 'person', 'user' => '31e80b14-a4f8-11eb-b425-0242ac130004'], 'account/list')->one()[0];
	    $studName = $this->getFullName();
	    $studGroup = $response['party'];
		$res = "$studName $studGroup";
		return $res;

	}

	function putMark(int $p_id, int $p_mark){
		$run = Runner::searchById(id: $p_id);
		printme($run);
		$run->set(["mark" => $p_mark]);
		$run->save();
	}

	function runnersToPrint()
	{
		$runnerList = [];
		$result = "";
		$runnersList = Runner::getAllRunners(student:$this->getStudInfo());
		$i = 0;
		while( $i < count($runnersList)) {
			$result .= $runnersList[$i]->toStr() . "\n";
			$i++;
		}
		return $result;

	}
	function runnersToButtons()
	{
		$runners_arr = [];
		$id_arr = [];
		$result = [];
		$runnersList = Runner::getAllRunners(student:$this->getStudInfo());
		$i = 0;
		foreach($runnersList as $run) {
			$result[$i]['id'] =  $run->getId()+160;
			$result[$i]['title'] = $run->getStudent() . ' | ' . $run->getSubject();

			$i++;
		}
		return array_chunk($result, 1);
	}
	
	function getresult()
	{
		return Runner::getAll();
	}
	
	function getRunner($p_code){

		$run = new Runner( student:$this->getStudInfo(), lecturer:$this->getText($p_code), date:date('Y-m-d'), subject:$this->getCode($p_code), mark:null);
		$run->save();
	}
	function retRunner(){
		$run = Runner::searchById(1);

		return $run;

	}

	function checkRunner($p_code){
		$run = null;
		$run = Runner::search(student: $this->getStudInfo(),subject: $this->getCode($p_code));
		if($run == null)
		return true;

		return false;
	}

	public function __construct() {
		$this->db = new \Library\MySQL('core',
			\Library\MySQL::connect(
				$this->getVar('DB_HOST', 'e'),
				$this->getVar('DB_USER', 'e'),
				$this->getVar('DB_PASS', 'e')
			) );
		$this->setDB($this->db);}
	
}