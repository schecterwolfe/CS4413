<?php
require_once(dirname(__FILE__). '/../WebContent/CommentModel.php');
class test_EvalCommentModel_validate extends UnitTestCase {
	private $model;

	function setUp() {
		$this->model = new CommentModel("localhost", "krobbins", "abc123", "tj_data_test");
	}

	function tearDown() {

	}
	
	function testReturnsErrorWhenUserDoesNotExist(){
		$tempModel = new UserModel("localhost", "krobbins", "abc123", "tj_data_test", "test user");
		$tempModel->deleteALL();
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
		$result = $tempModel->editProfile($newvals, $filevals);
		if(!is_array($result)){
			$iserror = 1;
		}else{
			$iserror = 0;
		}
		 
		$this->assertEqual(0, $iserror,
				'create should not produce an error when input is correct, error is: '.$tempModel->getError().' result is ');
		$this->assertEqual(1, $tempModel->getCount(), 
				'error count should not be 0 when added row, count is '.$tempModel->getCount());
		
		$inarray = array ('user' => 'NONEXISTANTUSER',
				          'comment' => 'Test description');
		$outarray = $this->model->validate($inarray);
		$this->assertEqual($outarray, 0, 
		         'It should return 0 when user does not exist when making a comment to');
		$this->assertTrue($this->model->getError(),
				 'The CommentModel error should be set to indicate error');
	}
	
	function testValidCommentReponse(){
		$tempModel = new UserModel("localhost", "krobbins", "abc123", "tj_data_test", "test user");
		$tempModel->deleteALL();
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
		$result = $tempModel->editProfile($newvals, $filevals);
		if(!is_array($result)){
			$iserror = 1;
		}else{
			$iserror = 0;
		}
			
		$this->assertEqual(0, $iserror,
				'create should not produce an error when input is correct, error is: '.$tempModel->getError().' result is ');
		$this->assertEqual(1, $tempModel->getCount(),
				'error count should not be 0 when added row, count is '.$tempModel->getCount());
		
		$inarray = array ('user' => 'john smith',
				'comment' => 'Test description');
		$outarray = $this->model->makeCommentForUser($inarray);
		$this->assertNotEqual($outarray, false,
				'It should not return on error on a valid comment response. Error:'.$this->model->getError());
		$this->assertTrue($this->model->getError(),
				'The CommentModel error should be set to indicate error');
		
		$outarray = $this->model->getCommentsForUser('john smith');
		$this->assertNotEqual($outarray, false,
				'It should not return on error on a vail comment fetch from user');
		$this->assertTrue($this->model->getError(),
				'The CommentModel error should be set to indicate error');
	}
	
	function testReturnsErrorParameterNotArray(){
		$inarray = 'comment_to_user';
		$outarray = $this->model->validate($inarray);
		$this->assertEqual($outarray, 0,
				'It should return 0 when the the parameter is not an array');
		$this->assertTrue($this->model->getError(),
				'The CommentModel error should be set to indicate error');
	
	}
	
	function testReturnsErrorWhenFieldsAreMissing(){
		$inarray = array ('user' => 'john smith');
		$outarray = $this->model->validate($inarray);
		$this->assertEqual($outarray, 0,
				'It should return 0 when the there is no comment body argument');
		$this->assertTrue($this->model->getError(),
				'The CommentModel error should be set to indicate error');
		$inarray = array ('comment' => 'Test description');
		$this->assertEqual($outarray, 0,
				'It should return 0 when the there is no user argument');
		$this->assertTrue($this->model->getError(),
				'The CommentModel error should be set to indicate error');
	}
}
?>