<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'WebContent'.DIRECTORY_SEPARATOR.'UserModel.php');
class test_EvalUserModel extends UnitTestCase {
	private $model;
	
	function setUp() {
		$this->model = new UserModel("localhost", "krobbins", "abc123", "tj_data_test", "test user");
		$this->model->deleteALL();	
	}
	
	function tearDown() {
		
	}
	
  function testMakeEvalProfileModel(){
  	$this->assertTrue(class_exists('UserModel'), 
  			'The EvalProfileModel class should be defined');
  	$this->assertTrue(is_a($this->model, 'UserModel'), 
  			'EvalProfileModel should have a constructor');
  }
  
  function testOnEmptyDatabase() {
  	$this->model->deleteALL ();
  	$this->assertEqual($this->model->nextProfile(), 0,
  			'getNextProfile should return false when trying to get a profile from an empty database');
  	$this->assertEqual($this->model->getUser("user"), 0,
  			'getUser should return false on an empty database');
  	$this->assertEqual($this->model->getProfile(), 0,
  			'getProfile should return false on an empty database');
  	$this->assertEqual($this->model->getCount(), 0,
  			'getCount should return 0 rows when database is empty');
  }
  
  function testCreateSimpleInsertion(){
  	$newvals = array('name' => 'john smith',
  						'email' => '123@aol.com',
  						'sex' => 'male',
  						'available' => array('mon','tue','sat'),
  						'exp' => '1-3',
  						'trainer_description' => 'this is the trainer description');
  	$filevals = array('photo' => array('name' => 'icon.jpg',
  										'type' => 'image/jpeg',
  										'tmp_name' => 'C:\xampp\tmp\phpB7E7.tmp',
  										'error' => '0',
  										'size' => '31097'));
  	$result = $this->model->editProfile($newvals, $filevals);
  	if(!is_array($result)){
  		$iserror = 1;
  	}else{
  		$iserror = 0;
  	}
  	
  	$this->assertEqual(0, $iserror, 
  			'create should not produce an error when input is correct, error is: '.$this->model->getError().' result is ');
  	$this->assertEqual(1, $this->model->getCount(),
  			'error count should not be 0 when added row, count is '.$this->model->getCount());
  }
   
  function testInsertMultipleRows(){
  	$this->model->deleteALL();  	
  	$this->assertEqual($this->model->getCount(), 0, 
  			'It should return 0 rows when database is empty');

  	for ($k = 1; $k <= 10; $k++) {
  		$this->model->setUser("john smith$k");
  		$newvals = array('name' => "john smith$k",
  				'email' => "123$k@aol.com",
  				'sex' => 'male',
  				'available' => array('mon','tue','sat'),
  				'exp' => '1-3',
  				'trainer_description' => 'this is the trainer description');
  		$filevals = array('photo' => array('name' => "icon$k.jpg",
  				'type' => 'image/jpeg',
  				'tmp_name' => 'C:\xampp\tmp\phpB7E7.tmp',
  				'error' => '0',
  				'size' => '31097'));
  		$res = $this->model->editProfile($newvals, $filevals);
  		$this->assertEqual(is_array($res), true,
  				'There should not be an error on valid input, error:'.$this->model->getError());
  	}
  	$this->assertEqual($this->model->getCount(), 10, 
  	         'The database should have 10 rows after inserting 10 Profiles, count is '.$this->model->getCount());
  }
  
    function testGetProfile(){
  	$this->model->deleteALL();
  	$this->assertEqual($this->model->getCount(), 0,
  			'It should return 0 rows when database is empty');
    
  	for ($k = 1; $k <= 10; $k++) {
  		$this->model->setUser("john smith$k");
  		$newvals = array('name' => "john smith$k",
  				'email' => "123$k@aol.com",
  				'sex' => 'male',
  				'available' => array('mon','tue','sat'),
  				'exp' => '1-3',
  				'trainer_description' => 'this is the trainer description');
  		$filevals = array('photo' => array('name' => "icon$k.jpg",
  				'type' => 'image/jpeg',
  				'tmp_name' => 'C:\xampp\tmp\phpB7E7.tmp',
  				'error' => '0',
  				'size' => '31097'));
  		$this->model->editProfile($newvals, $filevals);
  	}
  	$this->assertEqual($this->model->getCount(), 10,
  			'The database should have 10 rows after inserting 10 profiles, count is'.$this->model->getCount());
  	
  	for ($k = 1; $k <= 10; $k++) {
  		$myTrainer = "john smith$k";
  		$myResult = $this->model->getUser($myTrainer);
  		$this->assertTrue(is_array($myResult));
  		$this->assertEqual(strcmp($myResult['name'], $myTrainer), 0, 'The returned value from getProfile should be the same');
  	}
  }
  
  function testTryConnectionWithBadCredentials () {
  	$this->expectException();
  	//$this->expectError(new PatternExpectation("/Bad connection/i"));
  	$mod = new UserModel ( "localhost", "jrobbins", "abc123", "tj_data_test" );
  	//$this->assertTrue(is_a($mod, 'EvalUrlModel'));
  	//$this->assertTrue($mod->getError());
  }
}
?>