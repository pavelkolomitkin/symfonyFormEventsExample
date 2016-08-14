<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CountryVehicle
 *
 * @ORM\Table(name="country_vehicle", uniqueConstraints={
        @ORM\UniqueConstraint(name="country_vehicle_index", columns={"country_id", "vehicle_id"})
 *     })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CountryVehicleRepository")
 * @UniqueEntity(
 *     fields={"country", "vehicle"},
 *     errorPath="country",
 *     message="This country is already in use on that vehicle."
 * )
 */
class CountryVehicle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAvailable", type="boolean")
     */
    private $isAvailable;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="vehicleLinks")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $country;

    /**
     * @var Vehicle
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Vehicle", inversedBy="countryLinks")
     * @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $vehicle;


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
     * Set isAvailable
     *
     * @param boolean $isAvailable
     * @return CountryVehicle
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return boolean 
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    public function getVehicle()
    {
        return $this->vehicle;
    }

    public function setVehicle(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }
}
