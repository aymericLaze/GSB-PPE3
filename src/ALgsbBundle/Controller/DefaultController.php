<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use ALgsbBundle\Form\ConnexionForm\ConnexionFormClass;
use ALgsbBundle\Form\ConnexionForm\ConnexionFormType;

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
        // creation du formulaire
        $connexionClass = new ConnexionFormClass();
        $form = $this->createForm(ConnexionFormType::class, $connexionClass);
        
        // recuperation du contenu du formulaire
        $form->handleRequest($request);
        
        /*// analyse des données et tentative de connexion
        if($form->isSubmitted() && $form->isValid())
        {
            $login = $form->get('login')->getData();
            $motDePasse = $form->get('passwd')->getData();
            $profil = $form->get('role')->getData();
            
            // interogation de la base
            $repository = $this->getDoctrine()->getManager()->getRepository('BatiInterimBundle:'.ucfirst($profil));
            $donneeUtilisateur = $repository->findOneBy(array('login'=>$login, 'passwd'=>$motDePasse));

            if(isset($donneeUtilisateur))
            {
                // redirection vers la route du role
                return $this->redirectToRoute('bati_interim_accueil_'.$profil, array('login'=>$login));
            }
            // affichage message d'erreur
            return new Response('Login, mot de passe ou rôle incorrect');
        }*/
        
        return $this->render('@ALgsb/Default/index.html.twig', array('form'=>$form->createView()));
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
