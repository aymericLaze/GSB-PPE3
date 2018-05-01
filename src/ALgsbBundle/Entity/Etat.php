<?php

namespace ALgsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etat
 *
 * @ORM\Table(name="etat")
 * @ORM\Entity
 */
class Etat
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=2, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $libelle;
    
    /**
     * @var \Fichefrais
     * 
     * @ORM\OneToMany(targetEntity="FicheFrais", mappedBy="idetat")
     */
    private $fichefrais;


    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Etat
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fichefrais = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fichefrai
     *
     * @param \ALgsbBundle\Entity\FicheFrais $fichefrai
     *
     * @return Etat
     */
    public function addFichefrai(\ALgsbBundle\Entity\FicheFrais $fichefrai)
    {
        $this->fichefrais[] = $fichefrai;

        return $this;
    }

    /**
     * Remove fichefrai
     *
     * @param \ALgsbBundle\Entity\FicheFrais $fichefrai
     */
    public function removeFichefrai(\ALgsbBundle\Entity\FicheFrais $fichefrai)
    {
        $this->fichefrais->removeElement($fichefrai);
    }

    /**
     * Get fichefrais
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichefrais()
    {
        return $this->fichefrais;
    }
}
