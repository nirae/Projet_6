<?php

namespace NAO\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
* User
*
* @ORM\Table(name="users")
* @ORM\Entity(repositoryClass="NAO\AppBundle\Repository\UserRepository")
* @UniqueEntity(fields="username", message="Ce pseudo n'est pas disponible")
* @UniqueEntity(fields="email", message="Il y a déjà un compte lié à cet email. Vous pouvez vous connecter")
*/
class User implements AdvancedUserInterface, \Serializable
{
    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\Column(name="username", type="string")
    * @Assert\NotBlank()
    */
    private $username;

    /**
    * @SecurityAssert\UserPassword(
    *     groups = {"registration"},
    *     message = "Mauvais mot de passe"
    * )
    */
    private $oldPassword;

    /**
     * Mot de passe en clair. NE PAS FLUSHER
     *
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
    * Mot de passe encodé
    * @ORM\Column(name="password", type="string")
    */
    private $password;

    private $role;

    /**
    * @ORM\Column(name="roles", type="array")
    */
    private $roles = array();

    /**
    * @ORM\Column(name="date_creation", type="datetime")
    */
    private $creationDate;

    /**
    * @ORM\Column(name="email", type="string")
    * @Assert\NotBlank()
    * @Assert\Email()
    */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="NAO\AppBundle\Entity\Observation", mappedBy="owner", cascade={"persist"})
     */
    private $observations;

    /**
    * @ORM\Column(name="is_active", type="boolean")
    */
    private $isActive;

    public function __construct() {
        $this->creationDate = new \DateTime();
        $this->roles = array('ROLE_USER');
        $this->observations = new ArrayCollection();
        $this->isActive = false;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            ) = unserialize($serialized);
        }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add observation
     *
     * @param \NAO\AppBundle\Entity\Observation $observation
     *
     * @return User
     */
    public function addObservation(\NAO\AppBundle\Entity\Observation $observation)
    {
        $this->observations[] = $observation;

        return $this;
    }

    /**
     * Remove observation
     *
     * @param \NAO\AppBundle\Entity\Observation $observation
     */
    public function removeObservation(\NAO\AppBundle\Entity\Observation $observation)
    {
        $this->observations->removeElement($observation);
    }

    /**
     * Get observations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservations()
    {
        return $this->observations;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function setIsActive($isActive) {

        $this->isActive = $isActive;
    }

    public function getIsActive() {

        return $this->isActive;
    }

    public function activate() {

        $this->isActive = true;
    }

    public function disable() {

        $this->isActive = false;
    }

    public function getOldPassword() {

        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword) {

        $this->oldPassword = $oldPassword;

        return $this;
    }

}
