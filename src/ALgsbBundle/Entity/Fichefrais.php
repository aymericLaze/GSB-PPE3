<?php

namespace ALgsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fichefrais
 *
 * @ORM\Table(name="fichefrais", indexes={@ORM\Index(name="fk1_fichefrais", columns={"idetat"}), @ORM\Index(name="fk2_fichefrais", columns={"idvisiteur"})})
 * @ORM\Entity
 */
class Fichefrais
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
     * @ORM\Column(name="mois", type="string", length=6, nullable=false)
     */
    private $mois;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbjustificatifs", type="integer", nullable=false)
     */
    private $nbjustificatifs;

    /**
     * @var string
     *
     * @ORM\Column(name="montantvalide", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $montantvalide;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemodif", type="date", nullable=false)
     */
    private $datemodif;

    /**
     * @var \Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat", inversedBy="fichefrais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idetat", referencedColumnName="id")
     * })
     */
    private $idetat;

    /**
     * @var \Visiteur
     *
     * @ORM\ManyToOne(targetEntity="Visiteur", inversedBy="visiteur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idvisiteur", referencedColumnName="id")
     * })
     */
    private $idvisiteur;
    
    /**
     * @var \Lignefraishorsforfait
     * 
     * @ORM\OneToMany(targetEntity="Lignefraishorsforfait", mappedBy="idfichefrais")
     */
    private $ligneFicheFraisHorsForfait;

    /**
     * @var \Lignefraisforfait
     * 
     * @ORM\OneToMany(targetEntity="Lignefraisforfait", mappedBy="idfichefrais")
     */
    private $ligneFicheFraisForfait;
    
    
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
     * @param \ALgsbBundle\Entity\Etat $idetat
     *
     * @return Fichefrais
     */
    public function setIdetat(\ALgsbBundle\Entity\Etat $idetat = null)
    {
        $this->idetat = $idetat;

        return $this;
    }

    /**
     * Get idetat
     *
     * @return \ALgsbBundle\Entity\Etat
     */
    public function getIdetat()
    {
        return $this->idetat;
    }

    /**
     * Set idvisiteur
     *
     * @param \ALgsbBundle\Entity\Visiteur $idvisiteur
     *
     * @return Fichefrais
     */
    public function setIdvisiteur(\ALgsbBundle\Entity\Visiteur $idvisiteur = null)
    {
        $this->idvisiteur = $idvisiteur;

        return $this;
    }

    /**
     * Get idvisiteur
     *
     * @return \ALgsbBundle\Entity\Visiteur
     */
    public function getIdvisiteur()
    {
        return $this->idvisiteur;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ligneFicheFraisHorsForfait = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ligneFicheFraisHorsForfait
     *
     * @param \ALgsbBundle\Entity\Lignefraishorsforfait $ligneFicheFraisHorsForfait
     *
     * @return Fichefrais
     */
    public function addLigneFicheFraisHorsForfait(\ALgsbBundle\Entity\Lignefraishorsforfait $ligneFicheFraisHorsForfait)
    {
        $this->ligneFicheFraisHorsForfait[] = $ligneFicheFraisHorsForfait;

        return $this;
    }

    /**
     * Remove ligneFicheFraisHorsForfait
     *
     * @param \ALgsbBundle\Entity\Lignefraishorsforfait $ligneFicheFraisHorsForfait
     */
    public function removeLigneFicheFraisHorsForfait(\ALgsbBundle\Entity\Lignefraishorsforfait $ligneFicheFraisHorsForfait)
    {
        $this->ligneFicheFraisHorsForfait->removeElement($ligneFicheFraisHorsForfait);
    }

    /**
     * Get ligneFicheFraisHorsForfait
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLigneFicheFraisHorsForfait()
    {
        return $this->ligneFicheFraisHorsForfait;
    }

    /**
     * Add ligneFicheFraisForfait
     *
     * @param \ALgsbBundle\Entity\Lignefraisforfait $ligneFicheFraisForfait
     *
     * @return Fichefrais
     */
    public function addLigneFicheFraisForfait(\ALgsbBundle\Entity\Lignefraisforfait $ligneFicheFraisForfait)
    {
        $this->ligneFicheFraisForfait[] = $ligneFicheFraisForfait;

        return $this;
    }

    /**
     * Remove ligneFicheFraisForfait
     *
     * @param \ALgsbBundle\Entity\Lignefraisforfait $ligneFicheFraisForfait
     */
    public function removeLigneFicheFraisForfait(\ALgsbBundle\Entity\Lignefraisforfait $ligneFicheFraisForfait)
    {
        $this->ligneFicheFraisForfait->removeElement($ligneFicheFraisForfait);
    }

    /**
     * Get ligneFicheFraisForfait
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLigneFicheFraisForfait()
    {
        return $this->ligneFicheFraisForfait;
    }
}
