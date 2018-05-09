<?php

namespace ALgsbBundle\Model;

use ALgsbBundle\Entity\Fichefrais;
use ALgsbBundle\Entity\Lignefraishorsforfait;

/**
 * Methodes specifiques du visiteur
 *
 * @author laze
 */
class ModelVisiteur {
    
    /**
     * Retourne un tableau pour créer le formulaire de sélection de mois
     * 
     * @param array $lesFiches
     * @return array
     */
    public static function getLesMoisPourFormulaire(array $lesFiches)
    {
        $lesMois = [];
        
        // parcours des fiches et creation d'un dictionnaire
        foreach($lesFiches as $uneFiche)
        {
            $mois = $uneFiche->getMois();
            $moisKey = $mois[4].$mois[5].'-'.$mois[0].$mois[1].$mois[2].$mois[3];
            $lesMois[$moisKey] = $uneFiche->getId();
        }
        
        return $lesMois;
    }
    
    /**
     * Verification que la fiche du mois existe
     * 
     * @param Fichefrais $laFiche
     * @return bool
     */
    public static function isFicheDuMois(Fichefrais $laFiche)
    {
        return $laFiche->getIdetat()->getId() == 'CR';
    }
    
    /**
     * Enregistre les frais hors forfait du visiteur
     * 
     * @param type $leFormulaire
     * @param Fichefrais $laFiche
     * @return Lignefraishorsforfait
     */
    public static function enregistrerFraisHorsForfait($leFormulaire, Fichefrais $laFiche) : Lignefraishorsforfait
    {
        $frais = new Lignefraishorsforfait();
        
        $frais->setDate($leFormulaire->get('date')->getData());
        $frais->setLibelle($leFormulaire->get('libelle')->getData());
        $frais->setMontant($leFormulaire->get('montant')->getData());
        $frais->setIdfichefrais($laFiche);
       
        return $frais;
    }
}
