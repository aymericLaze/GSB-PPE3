<?php

namespace ALgsbBundle\Model;

use ALgsbBundle\Entity\Fichefrais;
use ALgsbBundle\Entity\Visiteur;
use ALgsbBundle\Entity\Lignefraisforfait;

use Symfony\Component\HttpFoundation\Request;

use DateTime;

/**
 * Methodes du comptalbe et du visiteur
 *
 * @author laze
 */
class ModelBase {
    
    /**
     * Retourne la quantite de chaque frais forfait
     * 
     * @param Fichefrais $laFiche
     * @return array
     */
    public static function getQuantiteFraisForfait(Fichefrais $laFiche) : array
    {
        $lesQuantites = array('ETP'=>0, 'KM'=>0, 'NUI'=>0, 'REP'=>0);
        
        // parcours de la liste des frais forfait et recuperation des quantites
        foreach($laFiche->getLigneFicheFraisForfait()->toArray() as $leFrais)
        {
            $lesQuantites[$leFrais->getIdfraisforfait()->getId()] = $leFrais->getQuantite();
        }
        
        return $lesQuantites;
    }
    
    /**
     * Retourne le tableau des lignes de frais hors forfait
     * 
     * @param Fichefrais $laFiche
     * @return array
     */
    public static function getLignesHorsForfait(Fichefrais $laFiche) : array
    {
        $tableau = [];
        
        // parcours la liste et ajoute les frais hors forfait au tableau
        foreach($laFiche->getLigneFicheFraisHorsForfait()->toArray() as $laLigne)
        {
            array_push($tableau, array(
                                        'id'=>$laLigne->getId(),
                                        'date'=>$laLigne->getDate(),
                                        'libelle'=>$laLigne->getLibelle(),
                                        'montant'=>$laLigne->getMontant()
                                        )
            );
        }
        
        return $tableau;
    }
    
    /**
     * Procedure de creation d'une nouvelle fiche de frais
     * 
     * @param Visiteur $leVisiteur
     * @param type $em
     */
    public static function creationNouvelleFicheFrais(Visiteur $leVisiteur, $em)
    {
        $etatRepo = $em->getRepository('ALgsbBundle:Etat');
        $fraisForfaitRepo = $em->getRepository('ALgsbBundle:Fraisforfait');
        
        $ficheFrais = new Fichefrais();
        $date = new DateTime();
        
        $ficheFrais
                ->setMois($date->format('Ym'))
                ->setNbjustificatifs(0)
                ->setMontantvalide(0)
                ->setDatemodif($date)
                ->setIdetat($etatRepo->find('CR'))
                ->setIdvisiteur($leVisiteur);
        
        foreach(array('ETP', 'KM', 'NUI', 'REP') as $idForfait)
        {
            $ligneFraisForfait = new Lignefraisforfait();
            $ligneFraisForfait
                    ->setQuantite(0)
                    ->setIdfichefrais($ficheFrais)
                    ->setIdfraisforfait($fraisForfaitRepo->find($idForfait));
            $em->persist($ligneFraisForfait);
        }
        $em->persist($ficheFrais);
        
        $em->flush();
    }
    
    /**
     * Enregistre les frais forfait du visiteur
     * 
     * @param $leFomulaire
     * @param array $lesLignesFrais
     * @param $em
     */
    public static function enregistrerFraisForfait($leFomulaire, array $lesLignesFrais, $em)
    {
        foreach($lesLignesFrais as $laLigne)
        {
            $idEtat = $laLigne->getIdfraisforfait()->getId();
            $laLigne->setQuantite($leFomulaire->get($idEtat)->getData());
            $em->persist($laLigne);
        }
        
        $em->flush();
    }
    
    /**
     *  Retourne un objet de type $classe en fonction de l'identifiant
     * 
     * @param type $em
     * @param string $id
     * @param string $classe
     * @return type
     */
    public static function getLeRepository($em, string $id, string $classe)
    {
        return $em
                ->getRepository('ALgsbBundle:'.$classe)
                ->find($id);
    }
    
    /**
     * Retourne vrai si l'utilisateur est connecte
     * 
     * @param Request $request
     * @param string $id
     * @param string $role
     * @return bool
     */
    public static function estConnecte(Request $request, string $id, string $role) : bool
    {
        return $request->getSession()->get($role) === $id;
    }
}
