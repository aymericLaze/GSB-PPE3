<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



/**
 * Controleur des actions du comptable
 *
 * @author laze
 */
class ComptableController extends Controller{
    
    /**
     * Affichage de la page d'accueil du comptable
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function accueilAction(Request $request)
    {
        $id = $request->attributes->get('id');
        
        $leComptable = $this->getComptable($id);
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        return $this->render('@ALgsb/Comptable/accueil_comptable.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom));
    }
    
    /**
     * Retourne les informations sur le comptable en fonction de l'identifiant
     * 
     * @param str $id
     * @return Comptable
     */
    private function getComptable($id)
    {
        return $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('ALgsbBundle:Comptable')
                ->find($id);
    }
}
