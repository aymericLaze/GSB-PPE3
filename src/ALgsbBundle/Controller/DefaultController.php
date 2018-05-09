<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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
        
        // analyse des données et tentative de connexion
        if($form->isSubmitted() && $form->isValid())
        {
            $login = $form->get('login')->getData();
            $motDePasse = $form->get('passwd')->getData();
            $profil = $form->get('role')->getData();
            
            // interogation de la base
            $repository = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:'.ucfirst($profil));
            $donneeUtilisateur = $repository->findOneBy(array('login'=>$login, 'mdp'=>$motDePasse));
            
            if(isset($donneeUtilisateur))
            {
                //ajout de l'identifiant en session
                $session = $request->getSession();
                //$session->start();
                $session->set($profil, $donneeUtilisateur->getId());
                //return $this->render('@ALgsb/test.html.twig', array('test'=>$session));
                // redirection vers la route du role
                return $this->redirectToRoute('accueil_'.$profil, array('id'=>$donneeUtilisateur->getId()));
            }
            // affichage message d'erreur
            return $this->redirectToRoute('page_erreur', array('error'=>1));
        }
        
        return $this->render('@ALgsb/Default/index.html.twig', array('form'=>$form->createView()));
    }
    
    /**
     * Redirection vers la page d'erreur
     */
    public function erreurAction(Request $request)
    {
        // recuperation du numero d'erreur
        $idErreur = $request->attributes->get('error');
        $libelleErreur = NULL;
        // recuperation du libelle de l'erreur
        switch($idErreur)
        {
            case '1':
                $libelleErreur = "Identifiants ou rôle incorrect";
                break;
        }
        // retourne la vue
        return $this->render('@ALgsb/Default/erreur.html.twig', array("libelleErreur"=>$libelleErreur));
    }
    
    /**
     * Destruction de la session et redirection vers la page de connexion
     * 
     * @param Request $request
     */
    public function deconnexionAction(Request $request)
    {
        // detruit la session
        $session = $request->getSession();
        $session->clear();
        // redirige vers la page de connexion
        return $this->redirectToRoute('page_connexion');
    }
}
