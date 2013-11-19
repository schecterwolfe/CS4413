<?php
require_once(dirname(__FILE__). '/../WebContent/UserModel.php');
class test_EvalUserModel_validate extends UnitTestCase {
	private $model;

	function setUp() {
		$this->model = new UserModel("localhost", "krobbins", "abc123", "tj_data_test", "test user");
		$this->model->deleteAll();
	}

	function tearDown() {
		$this->model->deleteALL();
	}
	
	function testReturnsErrorWhenFieldIsBlank(){
		//email is blank
		$newvals = array('name' => 'john smith',
  						'sex' => 'male',
						'email' => '',
  						'available' => array('mon','tue','sat'),
  						'exp' => '1-3',
  						'trainer_description' => 'this is the trainer description');
  		$filevals = array('photo' => array('name' => 'icon.jpg',
  										'type' => 'image/jpeg',
  										'tmp_name' => 'C:\xampp\tmp\phpB7E7.tmp',
  										'error' => '0',
  										'size' => '31097'));
		$result = $this->model->editProfile($newvals, $filevals);
		$this->assertEqual($result, 0, 
		         'It should return 0 when field is blank');
		$this->assertTrue($this->model->getError(),
				 'The UserModel error should be set to indicate error');
	}
	
	function testReturnsErrorParameterNotArray(){
		$newvals = 'temp';
		$filevals = 'temp;';
		$result = $this->model->editProfile($newvals, $filevals);
		$this->assertEqual($result, 0,
				'It should return 0 when the the parameter is not an array');
		$this->assertTrue($this->model->getError(),
				'The UserModel error should be set to indicate error');
	
	}
	
	function testReturnsErrorWhenFieldsAreMissing(){
		$newvals = array ('name' => 'tim');
		$filevals = array('photo' => array('name' => 'icon.jpg',
				'type' => 'image/jpeg',
				'tmp_name' => 'C:\xampp\tmp\phpB7E7.tmp',
				'error' => '0',
				'size' => '31097'));
		$result = $this->model->editProfile($newvals, $filevals);
		$this->assertEqual($result, 0,
				'It should return 0 when there are missing categories');
		$this->assertTrue($this->model->getError(),
				'The UserModel error should be set to indicate error');
	}

	function testReturnsErrorWhenPhotoIsNotUploadedCorrectly(){
		$newvals = array('name' => 'john smith',
				'sex' => 'male',
				'email' => '123@aol.com',
				'available' => array('mon','tue','sat'),
				'exp' => '1-3',
				'trainer_description' => 'this is the trainer description');
		$filevals = array('photo' => array('name' => 'icon.jpg',
				'type' => 'image/jpeg',
				'tmp_name' => '',
				'error' => '0',
				'size' => '0'));
		$result = $this->model->editProfile($newvals, $filevals);
		$this->assertEqual($result, 0,
				'It should return 0 when the photo is not uploaded correctly');
		$this->assertTrue($this->model->getError(),
				'The UserModel error should be set to indicate error');
	}

}
?>