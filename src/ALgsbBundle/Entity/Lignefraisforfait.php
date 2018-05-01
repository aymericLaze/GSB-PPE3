<?php

namespace ALgsbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lignefraisforfait
 *
 * @ORM\Table(name="lignefraisforfait", indexes={@ORM\Index(name="fk1_lignefraisforfait", columns={"idfichefrais"}), @ORM\Index(name="fk2_lignefraisforfait", columns={"idfraisforfait"})})
 * @ORM\Entity
 */
class Lignefraisforfait
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
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Fichefrais
     *
     * @ORM\ManyToOne(targetEntity="Fichefrais", inversedBy="ligneFicheFraisForfait")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idfichefrais", referencedColumnName="id")
     * })
     */
    private $idfichefrais;

    /**
     * @var \Fraisforfait
     *
     * @ORM\ManyToOne(targetEntity="Fraisforfait", inversedBy="lignefraisforfait")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idfraisforfait", referencedColumnName="id")
     * })
     */
    private $idfraisforfait;



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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Lignefraisforfait
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set idfichefrais
     *
     * @param \ALgsbBundle\Entity\Fichefrais $idfichefrais
     *
     * @return Lignefraisforfait
     */
    public function setIdfichefrais(\ALgsbBundle\Entity\Fichefrais $idfichefrais = null)
    {
        $this->idfichefrais = $idfichefrais;

        return $this;
    }

    /**
     * Get idfichefrais
     *
     * @return \ALgsbBundle\Entity\Fichefrais
     */
    public function getIdfichefrais()
    {
        return $this->idfichefrais;
    }

    /**
     * Set idfraisforfait
     *
     * @param \ALgsbBundle\Entity\Fraisforfait $idfraisforfait
     *
     * @return Lignefraisforfait
     */
    public function setIdfraisforfait(\ALgsbBundle\Entity\Fraisforfait $idfraisforfait = null)
    {
        $this->idfraisforfait = $idfraisforfait;

        return $this;
    }

    /**
     * Get idfraisforfait
     *
     * @return \ALgsbBundle\Entity\Fraisforfait
     */
    public function getIdfraisforfait()
    {
        return $this->idfraisforfait;
    }
}
