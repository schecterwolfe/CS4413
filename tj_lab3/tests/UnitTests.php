<?php
class myGroupTest extends GroupTest {
  function myGroupTest() {
    parent::GroupTest('');
    $this->addTestFile(dirname(__FILE__).'/test_EvalUserModel.php');
    $this->addTestFile(dirname(__FILE__).'/test_EvalUserModel_validate.php');
    $this->addTestFile(dirname(__FILE__).'/test_EvalCommentModel.php');
  }
}
?>