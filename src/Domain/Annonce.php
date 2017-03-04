<?php

namespace MicroImmo\Domain;

class Annonce
{
    /**
     * Annonce id.
     *
     * @var integer
     */
    private $id;

		/**
     * Annonce title.
     *
     * @var string
     */
    private $title;

    /**
     * Annonce prix.
     *
     * @var double
     */
    private $prix;

    /**
     * Annonce surface.
     *
     * @var double
     */
    private $surface;

		/**
     * Annonce nb_pieces.
     *
     * @var int
     */
    private $nbPieces;

		/**
     * Annonce dateAjout.
     *
     * @var date
     */
    private $dateAjout;

		/**
     * Annonce dateMaj.
     *
     * @var date
     */
    private $dateMaj;

		/**
     * Annonce ville.
     *
     * @var string
     */
    private $ville;

		/**
     * Annonce quartier.
     *
     * @var string
     */
    private $quartier;

		/**
     * Annonce url.
     *
     * @var string
     */
    private $url;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

		public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
        return $this;
    }

    public function getSurface() {
        return $this->surface;
    }

    public function setSurface($surface) {
        $this->surface = $surface;
        return $this;
    }

		public function getNbPieces() {
        return $this->nb_pieces;
    }

    public function setNbPieces($nbPieces) {
        $this->nb_pieces = $nbPieces;
        return $this;
    }

		public function getDateAjout() {
        return $this->dateAjout;
    }

    public function setDateAjout($dateAjout) {
        $this->dateAjout = $dateAjout;
        return $this;
    }

		public function getDateMaj() {
        return $this->dateMaj;
    }

    public function setDateMaj($dateMaj) {
        $this->dateMaj = $dateMaj;
        return $this;
    }

		public function getVille() {
        return $this->ville;
    }

    public function setVille($ville) {
        $this->ville = $ville;
        return $this;
    }

		public function getQuartier() {
        return $this->quartier;
    }

    public function setQuartier($quartier) {
        $this->quartier = $quartier;
        return $this;
    }

		public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

}
