<?php

namespace MicroImmo\DAO;

use MicroImmo\Domain\Annonce;

class AnnonceDAO extends DAO
{
    /**
     * Return a list of all annonce, sorted by date (most recent first).
     *
     * @return array A list of all annonce.
     */
    public function findAll() {
        $sql = "select * from t_annonce order by annonce_id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $annonce = array();
        foreach ($result as $row) {
            $annonceId = $row['annonce_id'];
            $annonce[$annonceId] = $this->buildDomainObject($row);
        }
        return $annonce;
    }

		/**
     * Return the id if the row (the title) is already in the db.
     *
     * @return bool.
     */
    public function findIfExist($title) {
        $sql = "select id from t_annonce where annonce_title=".$title." ";
        $result = $this->getDb()->fetchAll($sql);
        return $result;
    }

    /**
     * Returns an annonce matching the supplied id.
     *
     * @param integer $id The annonce id.
     *
     * @return \MicroImmo\Domain\Annonce|throws an exception if no matching annonce is found
     */
    public function find($id) {
        $sql = "select * from t_annonce where annonce_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No annonce matching id " . $id);
    }

		/**
		* Saves an annonce into the database.
		*
		* @param \MicroImmo\Domain\Annonce $annonce The annonce to save
		*/
	 public function save(Annonce $annonce) {
		 $annonceData = array(
				 'annonce_id' => $annonce->getId(),
				 'annonce_title' => $annonce->getTitle(),
				 'annonce_prix' => $annonce->getPrix(),
				 'annonce_surface' => $annonce->getSurface(),
				 'annonce_pieces' => $annonce->getNbPieces(),
				 'annonce_dateAjout' => $annonce->getDateAjout(),
				 // to do : dateMaj if maj
				 'annonce_ville' => $annonce->getVille(),
				 'annonce_quartier' => $annonce->getQuartier(),
				 'annonce_url' => $annonce->getUrl(),
		);

		$sql = "select annonce_id from t_annonce where annonce_prix='".$annonce->getPrix()."' ";
		$result = $this->getDb()->fetchAll($sql);
		 if ($result) {
				 // The annonce has already been saved : update it
				 $this->getDb()->update('t_annonce', $annonceData, array('annonce_id' => $annonce->getId()));
		 } else {
				 // The comment has never been saved : insert it
				 $this->getDb()->insert('t_annonce', $annonceData);
				 // Get the id of the newly created comment and set it on the entity.
				 $id = $this->getDb()->lastInsertId();
				 $annonce->setId($id);
		 }
	 }

    /**
     * Creates an Annonce object based on a DB row.
     *
     * @param array $row The DB row containing Annonce data.
     * @return \MicroImmo\Domain\Annonce
     */

    protected function buildDomainObject(array $row) {
        $annonce = new Annonce();
        $annonce->setId($row['annonce_id']);
        $annonce->setTitle($row['annonce_title']);
        $annonce->setPrix($row['annonce_prix']);
				$annonce->setSurface($row['annonce_surface']);
				$annonce->setNbPieces($row['annonce_pieces']);
				$annonce->setDateAjout($row['annonce_dateAjout']);
				// to do : dateMaj if maj
				$annonce->setVille($row['annonce_ville']);
				$annonce->setQuartier($row['annonce_quartier']);
				$annonce->setUrl($row['annonce_url']);

        return $annonce;
    }
}
