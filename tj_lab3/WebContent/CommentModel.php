<?php
class CommentModel{
	private $db;
	private $error;
	private $result;

	public function __construct($hostname, $username, $userpass, $dbname){
		$this->error = "";
		$hostStr = "mysql:host=$hostname;dbname=$dbname;charset=utf8";
		$this->db = new PDO ( $hostStr, $username, $userpass );
		$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->db->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
	}
	
	public function deleteALL(){
		return $this->db->query ( "DELETE FROM comments" );
	}
	
	public function getCommentsForUser($user){
		if (!isset ( $this->results ) or ! $this->results) {
			$this->results = $this->db->query ( 'SELECT * FROM comments WHERE recipient=\''.$user.'\' ORDER BY prof_id DESC');
		}
		return $this->results->fetch ( PDO::FETCH_ASSOC );
	}
	
	public function makeCommentForUser($vals){
		$returnVals = $this->validate ($vals);
		
		if($vals == 0){
			return false;
		}
		$stmt = $this->db->prepare ( "INSERT INTO comments (recipient, owner, comment)
			VALUES (:recipient, :owner, :comment)");
		if($stmt->execute($returnVals) === false){
			$this->error = "insert into database error".implode(', ', $this->db->errorInfo());
			return false;
		}		
		
		return $returnVals['comment'];
	}
	
	public function validate($contents){
		if (!is_array($contents)) {
			$this->error = "Input argument not an array";
			return 0;
		}
		if (!array_key_exists('comment', $contents) or !array_key_exists('user', $contents) or !array_key_exists('owner', $contents)) {
			$this->error = "Missing form field on new_comment form";
			return 0;
		}
		$stmt = $this->db->prepare("SELECT * FROM profentry WHERE name=?");
		$stmt->execute(array($contents['user']));
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );
		if($result == false){
			$this->error = "user profile does not exsist";
			return 0;
		}		
		
		$comment_body = trim ( filter_var ( $contents['comment'], FILTER_SANITIZE_STRING ) );
		
		return array('recipient' => $contents['user'],
					 'owner' => $contents['owner'],
					 'comment' => $comment_body);
	}
	
	public function getError(){
		return $this->error;
	}
}
?>