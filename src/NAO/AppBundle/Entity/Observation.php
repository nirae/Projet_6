<?php

namespace NAO\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Observation
 *
 * @ORM\Table(name="observations")
 * @ORM\Entity
 */
class Observation
{
    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="NAO\AppBundle\Entity\TaxrefAves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * @ORM\Column(name="observation_date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="latitude", type="string")
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="string")
     */
    private $longitude;

    /**
     * @ORM\OneToOne(targetEntity="NAO\AppBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
     * @ORM\Column(name="user_message", type="string")
     */
    private $userMessage;

    /**
     * @ORM\Column(name="validation_message", type="string")
     */
    private $validationMessage;

    /**
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="NAO\AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Observation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set userMessage
     *
     * @param string $userMessage
     *
     * @return Observation
     */
    public function setUserMessage($userMessage)
    {
        $this->userMessage = $userMessage;

        return $this;
    }

    /**
     * Get userMessage
     *
     * @return string
     */
    public function getUserMessage()
    {
        return $this->userMessage;
    }

    /**
     * Set validationMessage
     *
     * @param string $validationMessage
     *
     * @return Observation
     */
    public function setValidationMessage($validationMessage)
    {
        $this->validationMessage = $validationMessage;

        return $this;
    }

    /**
     * Get validationMessage
     *
     * @return string
     */
    public function getValidationMessage()
    {
        return $this->validationMessage;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Observation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set species
     *
     * @param \NAO\AppBundle\Entity\TaxrefAves $species
     *
     * @return Observation
     */
    public function setSpecies(\NAO\AppBundle\Entity\TaxrefAves $species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species
     *
     * @return \NAO\AppBundle\Entity\TaxrefAves
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set image
     *
     * @param \NAO\AppBundle\Entity\Image $image
     *
     * @return Observation
     */
    public function setImage(\NAO\AppBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \NAO\AppBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set owner
     *
     * @param \NAO\AppBundle\Entity\User $owner
     *
     * @return Observation
     */
    public function setOwner(\NAO\AppBundle\Entity\User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \NAO\AppBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
