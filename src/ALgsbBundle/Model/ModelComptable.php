<?php

namespace ALgsbBundle\Model;

/**
 * Methodes specifiques au Comptable
 *
 * @author laze
 */
class ModelComptable {
    
    /**
     * Retourne un tableau associatif de mois valide pour la selection
     * 
     * @param array $lesMois
     * @return array
     */
    public static function getLesMoisValide(array $lesMois)
    {
        $lesMoisValide = [];
        
        // parcours de la liste et creation du dictionnaire
        foreach($lesMois as $mois)
        {
            $lesMoisValide[$mois->getDateModif()->format('m-Y')] = $mois->getMois(); 
        }
        
        return $lesMoisValide;
    }
    
    /**
     * Retourne la liste des visiteur ayant une fiche de frais a valider
     * 
     * @param array $lesVisiteurs
     * @return array
     */
    public static function getLesVisiteursValide(array $lesVisiteurs)
    {
        $lesVisiteursValide = [];
        
        // parcours de la liste et creation du dictionnaire
        foreach($lesVisiteurs as $visiteur)
        {
            $key = $visiteur->getIdVisiteur()->getNom().' '.$visiteur->getIdVisiteur()->getPrenom();
            $value = $visiteur->getIdVisiteur()->getId();
            $lesVisiteursValide[$key] = $value; 
        }
        
        return $lesVisiteursValide;
    }
}
