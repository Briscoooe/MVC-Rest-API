<?php

class SongsDAO {
	private $dbManager;
	function SongsDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	
	public function get($id = null) {
		$sql = "SELECT * ";
		$sql .= "FROM songs ";
		if ($id != null)
			$sql .= "WHERE songs.songId=? ";
		$sql .= "ORDER BY songs.title ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO songs (artistId, title, length, genre) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["artistId"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["title"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["length"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["genre"], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	
	public function update($parametersArray, $songID) {
		// /create an UPDATE sql statement (reads the parametersArray - this contains the fields submitted in the HTML5 form)
		$sql = "UPDATE songs SET artistId = ?, title = ?, length = ?, genre = ? WHERE songId = ?";
		
		$this->dbManager->openConnection ();
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["artistId"], PDO::PARAM_INT );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["title"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["length"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["genre"], PDO::PARAM_STR );
		$this->dbManager->bindValue ( $stmt, 5, $songID, PDO::PARAM_INT );
		$this->dbManager->executeQuery ( $stmt );
		
		//check for number of affected rows
		$rowCount = $this->dbManager->getNumberOfAffectedRows($stmt);
		return ($rowCount);
	}
	
	public function delete($songID) {
		$sql = "DELETE FROM songs ";
		$sql .= "WHERE songs.songId = ?";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $songID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rowCount = $this->dbManager->getNumberOfAffectedRows ( $stmt );
		return ($rowCount);
	}
	
	public function search($str) {
		$sql = "SELECT * ";
		$sql .= "FROM songs ";
		$sql .= "WHERE songs.title LIKE CONCAT('%', ?, '%') or songs.genre LIKE CONCAT('%', ?, '%')  ";
		$sql .= "ORDER BY songs.title ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $str, $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $str, $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
}
?>
