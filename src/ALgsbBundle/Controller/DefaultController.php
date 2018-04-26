<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



/**
 * Contrôleur par défaut
 */
class DefaultController extends Controller
{
    /**
     * Redirection vers la page d'accueil de l'application
     */
    public function indexAction(Request $request)
    {
        
        
        return $this->render('@ALgsb/Default/index.html.twig');
    }
    
    /**
     * Redirection vers la page d'erreur
     */
    public function erreurAction(Request $request)
    {
        $idErreur = $request->attributes->get($error);
        $libelleErreur = NULL;
                
        switch($idErreur)
        {
            case 1:
                $libelleErreur = "Identifiant ou rôle incorrect";
                break;
        }
        
        return $this->render('@ALgsb/Default/erreur.html.twig', array("libelleErreur"=>$libelleErreur));
    }
}
