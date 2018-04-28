<?php

namespace ALgsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fichefrais
 *
 * @ORM\Table(name="fichefrais", indexes={@ORM\Index(name="idEtat", columns={"idEtat"}), @ORM\Index(name="idUtilisateur", columns={"idUtilisateur"})})
 * @ORM\Entity
 */
class Fichefrais
{
    /**
     * @var string
     *
     * @ORM\Column(name="mois", type="string", length=6, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $mois;

    /**
     * @var string
     *
     * @ORM\Column(name="idUtilisateur", type="string", length=4, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idutilisateur;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbJustificatifs", type="integer", nullable=true)
     */
    private $nbjustificatifs;

    /**
     * @var string
     *
     * @ORM\Column(name="montantValide", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantvalide;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="date", nullable=true)
     */
    private $datemodif;

    /**
     * @var string
     *
     * @ORM\Column(name="idEtat", type="string", length=2, nullable=true)
     */
    private $idetat = 'CR';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Fraisforfait", mappedBy="idutilisateur")
     */
    private $idfraisforfait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idfraisforfait = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set mois
     *
     * @param string $mois
     *
     * @return Fichefrais
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return string
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set idutilisateur
     *
     * @param string $idutilisateur
     *
     * @return Fichefrais
     */
    public function setIdutilisateur($idutilisateur)
    {
        $this->idutilisateur = $idutilisateur;

        return $this;
    }

    /**
     * Get idutilisateur
     *
     * @return string
     */
    public function getIdutilisateur()
    {
        return $this->idutilisateur;
    }

    /**
     * Set nbjustificatifs
     *
     * @param integer $nbjustificatifs
     *
     * @return Fichefrais
     */
    public function setNbjustificatifs($nbjustificatifs)
    {
        $this->nbjustificatifs = $nbjustificatifs;

        return $this;
    }

    /**
     * Get nbjustificatifs
     *
     * @return integer
     */
    public function getNbjustificatifs()
    {
        return $this->nbjustificatifs;
    }

    /**
     * Set montantvalide
     *
     * @param string $montantvalide
     *
     * @return Fichefrais
     */
    public function setMontantvalide($montantvalide)
    {
        $this->montantvalide = $montantvalide;

        return $this;
    }

    /**
     * Get montantvalide
     *
     * @return string
     */
    public function getMontantvalide()
    {
        return $this->montantvalide;
    }

    /**
     * Set datemodif
     *
     * @param \DateTime $datemodif
     *
     * @return Fichefrais
     */
    public function setDatemodif($datemodif)
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    /**
     * Get datemodif
     *
     * @return \DateTime
     */
    public function getDatemodif()
    {
        return $this->datemodif;
    }

    /**
     * Set idetat
     *
     * @param string $idetat
     *
     * @return Fichefrais
     */
    public function setIdetat($idetat)
    {
        $this->idetat = $idetat;

        return $this;
    }

    /**
     * Get idetat
     *
     * @return string
     */
    public function getIdetat()
    {
        return $this->idetat;
    }

    /**
     * Add idfraisforfait
     *
     * @param \ALgsbBundle\Entity\Fraisforfait $idfraisforfait
     *
     * @return Fichefrais
     */
    public function addIdfraisforfait(\ALgsbBundle\Entity\Fraisforfait $idfraisforfait)
    {
        $this->idfraisforfait[] = $idfraisforfait;

        return $this;
    }

    /**
     * Remove idfraisforfait
     *
     * @param \ALgsbBundle\Entity\Fraisforfait $idfraisforfait
     */
    public function removeIdfraisforfait(\ALgsbBundle\Entity\Fraisforfait $idfraisforfait)
    {
        $this->idfraisforfait->removeElement($idfraisforfait);
    }

    /**
     * Get idfraisforfait
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdfraisforfait()
    {
        return $this->idfraisforfait;
    }
}
