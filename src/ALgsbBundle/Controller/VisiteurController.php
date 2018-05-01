<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



/**
 * Description of VisiteurController
 *
 * @author laze
 */
class VisiteurController extends Controller {
    
    /**
     * Affichage de la page d'accueil du visiteur
     * 
     * @param Request $request
     * @return VisiteurController
     */
    public function accueilAction(Request $request)
    {
        $id = $request->attributes->get('id');
        
        $leComptable = $this->getVisiteur($id);
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        return $this->render('@ALgsb/Visiteur/accueil_visiteur.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom));
    }
    
    
    /**
     * Retourne les informations sur le visiteur en fonction de l'identifiant
     * 
     * @param str $id
     * @return Visiteur
     */
    private function getVisiteur($id)
    {
        return $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('ALgsbBundle:Visiteur')
                ->find($id);
    }
}
