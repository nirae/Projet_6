<?php

namespace NAO\AppBundle\Form\Model;


class Index
{
    private $species;

    public function getSpecies() {
        return $this->species;
    }

    public function setSpecies($species) {
        $this->species = $species;
    }
}
