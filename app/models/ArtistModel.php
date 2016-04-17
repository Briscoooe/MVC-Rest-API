<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/ArtistsDAO.php";
require_once "Validation.php";
class ArtistModel {
	private $ArtistsDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	private $validationSuite; // contains functions for validating inputs
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->ArtistsDAO = new ArtistsDAO( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	public function getArtists() {
		return ($this->ArtistsDAO->get ());
	}
	public function getArtist($artistID) {
		if (is_numeric ( $artistID ))
			return ($this->ArtistsDAO->get ( $artistID ));
		
		return false;
	}

	public function createNewArtist($newArtist) {
		// validation of the values of the new artist
		
		// compulsory values
		if (! empty ( $newArtist ["name"] ) && ! empty ( $newArtist ["description"] ) && ! empty ( $newArtist ["active_since"] )) {
			if (	($this->validationSuite->isLengthStringValid ( $newArtist ["name"], TABLE_ARTISTS_NAME_LENGTH)) && 
					($this->validationSuite->isLengthStringValid ( $newArtist ["description"], TABLE_ARTISTS_DESC_LENGTH )) && 
					($this->validationSuite->isYearValid ( $newArtist ["active_since"]))) {
				if ($newId = $this->ArtistsDAO->insert ( $newArtist ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function searchArtists($string) {
		if (! empty ( $string )) {
			$resultSet = $this->ArtistsDAO->search ( $string );
			return $resultSet;
		}
		
		return false;
	}
	public function deleteArtist($artistID) {
		if (is_numeric ( $artistID )) {
			$deletedRows = $this->ArtistsDAO->delete ( $artistID );
			
			if ($deletedRows > 0)
				return (true);
		}
		return (false);
	}
	
	public function updateArtist($artistID, $newArtistRepresentation) {
		if (! empty ( $artistID ) && is_numeric ( $artistID )) {
			// compulsory values
			if (! empty ( $newArtistRepresentation ["name"] ) && ! empty 
					( $newArtistRepresentation ["description"] ) && ! empty 
					( $newArtistRepresentation ["active_since"] )) {
				/*
				 * the model knows the representation of a user in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
				 */
				if (($this->validationSuite->isLengthStringValid ( $newArtistRepresentation ["name"], TABLE_USER_NAME_LENGTH )) 
						&& ($this->validationSuite->isLengthStringValid ( $newArtistRepresentation ["description"], TABLE_USER_SURNAME_LENGTH )) && 
						($this->validationSuite->isYearValid ( $newArtistRepresentation ["active_since"]))) {
					$updatedRows = $this->ArtistsDAO->update ( $newArtistRepresentation, $artistID );
					if ($updatedRows > 0)
						return (true);
				}
			}
		}
		return (false);
	}
	
	public function __destruct() {
		$this->ArtistsDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>