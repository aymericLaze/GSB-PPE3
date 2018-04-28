<?php

namespace ALgsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicule
 *
 * @ORM\Table(name="vehicule")
 * @ORM\Entity
 */
class Vehicule
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="typeEssence", type="string", length=15, nullable=true)
     */
    private $typeessence;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreCheveau", type="string", length=5, nullable=true)
     */
    private $nombrecheveau;

    /**
     * @var float
     *
     * @ORM\Column(name="prixAuKm", type="float", precision=10, scale=0, nullable=true)
     */
    private $prixaukm;



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
     * Set typeessence
     *
     * @param string $typeessence
     *
     * @return Vehicule
     */
    public function setTypeessence($typeessence)
    {
        $this->typeessence = $typeessence;

        return $this;
    }

    /**
     * Get typeessence
     *
     * @return string
     */
    public function getTypeessence()
    {
        return $this->typeessence;
    }

    /**
     * Set nombrecheveau
     *
     * @param string $nombrecheveau
     *
     * @return Vehicule
     */
    public function setNombrecheveau($nombrecheveau)
    {
        $this->nombrecheveau = $nombrecheveau;

        return $this;
    }

    /**
     * Get nombrecheveau
     *
     * @return string
     */
    public function getNombrecheveau()
    {
        return $this->nombrecheveau;
    }

    /**
     * Set prixaukm
     *
     * @param float $prixaukm
     *
     * @return Vehicule
     */
    public function setPrixaukm($prixaukm)
    {
        $this->prixaukm = $prixaukm;

        return $this;
    }

    /**
     * Get prixaukm
     *
     * @return float
     */
    public function getPrixaukm()
    {
        return $this->prixaukm;
    }
}
