<?php

namespace ALgsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fraisforfait
 *
 * @ORM\Table(name="fraisforfait")
 * @ORM\Entity
 */
class Fraisforfait
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=20, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $montant;
    
    /**
     * @var \Lignefraisforfait
     * 
     * @ORM\OneToMany(targetEntity="Fraisforfait", mappedBy="idfraisforfait")
     */
    private $lignefraisforfait;
            

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
     * @return Fraisforfait
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
     * Set montant
     *
     * @param string $montant
     *
     * @return Fraisforfait
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignefraisforfait = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lignefraisforfait
     *
     * @param \ALgsbBundle\Entity\Fraisforfait $lignefraisforfait
     *
     * @return Fraisforfait
     */
    public function addLignefraisforfait(\ALgsbBundle\Entity\Fraisforfait $lignefraisforfait)
    {
        $this->lignefraisforfait[] = $lignefraisforfait;

        return $this;
    }

    /**
     * Remove lignefraisforfait
     *
     * @param \ALgsbBundle\Entity\Fraisforfait $lignefraisforfait
     */
    public function removeLignefraisforfait(\ALgsbBundle\Entity\Fraisforfait $lignefraisforfait)
    {
        $this->lignefraisforfait->removeElement($lignefraisforfait);
    }

    /**
     * Get lignefraisforfait
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignefraisforfait()
    {
        return $this->lignefraisforfait;
    }
}
