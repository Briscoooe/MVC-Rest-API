<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/SongsDAO.php";
require_once "Validation.php";
class SongModel {
	private $SongsDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	private $validationSuite; // contains functions for validating inputs
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->SongsDAO = new SongsDAO( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	public function getSongs() {
		return ($this->SongsDAO->get ());
	}
	public function getSong($songID) {
		if (is_numeric ( $songID ))
			return ($this->SongsDAO->get ( $songID ));
		return false;
	}

	public function createNewSong($newSong) {		
		// compulsory values
		if (!empty ($newSong ["artistId"]) && !empty ($newSong ["title"]) && !empty ($newSong ["length"]) && !empty ($newSong ["genre"])) {
			if (	($this->validationSuite->isLengthStringValid ( $newSong ["title"], TABLE_SONGS_TITLE_LENGTH)) && 
					($this->validationSuite->isLengthStringValid ( $newSong ["genre"], TABLE_SONGS_GENRE_LENGTH )) && 
					($this->validationSuite->isLengthTimeValid ( $newSong ["length"]))) {
				if ($newId = $this->SongsDAO->insert ( $newSong ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function searchSongs($string) {
		if (! empty ( $string )) {
			$resultSet = $this->SongsDAO->search ( $string );
			return $resultSet;
		}
		
		return false;
	}
	
	public function deleteSong($songID) {
		if (is_numeric ( $songID )) {
			$deletedRows = $this->SongsDAO->delete ( $songID );
			
			if ($deletedRows > 0)
				return (true);
		}
		return (false);
	}
	
	public function updateSong($songID, $newSongRepresentation) {
		if (! empty ( $songID ) && is_numeric ( $songID )) {
			// compulsory values
			if (!empty ($newSongRepresentation ["artistId"]) && !empty ($newSongRepresentation ["title"]) && 
				!empty ($newSongRepresentation ["genre"] ) && ! empty ($newSongRepresentation ["length"] )) {
			
				if (	($this->validationSuite->isLengthStringValid ( $newSongRepresentation ["title"], TABLE_SONGS_TITLE_LENGTH )) &&
						($this->validationSuite->isLengthStringValid ( $newSongRepresentation ["genre"], TABLE_USER_GENRE_LENGTH )) && 
						($this->validationSuite->isLengthTimeValid ( $newSongRepresentation ["length"]))) {
					$updatedRows = $this->SongsDAO->update ( $newSongRepresentation, $songID );
					if ($updatedRows > 0)
						return (true);
				}
			}
		}
		return (false);
	}
	
	public function __destruct() {
		$this->SongsDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>