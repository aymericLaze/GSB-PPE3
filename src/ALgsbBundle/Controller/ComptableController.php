<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use DateTime;

use ALgsbBundle\Model\ModelComptable;
use ALgsbBundle\Model\ModelBase;

/**
 * Controleur des actions du comptable
 *
 * @author laze
 */
class ComptableController extends Controller{
    
    private $role = 'comptable';
    
    /**
     * Affichage de la page d'accueil du comptable
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function accueilAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recupere le comptable
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        // recupere l'identite du comptable
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        // retourne la vue
        return $this->render('@ALgsb/Comptable/accueil_comptable.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom));
    }
    
    /**
     * Affichage de la page de selection des mois
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function selectionnerMoisAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recupere le comptable
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        // recupere l'identite du comptable
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        // recupere les mois a l'etat cloture
        $lesMois = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais')->findBy(array('idetat'=>'CL'));
        // creation du formulaire pour les frais forfait
        $form = $this->createFormBuilder()
                ->add('leMois', ChoiceType::class, array('label'=>'Mois : ', 'choices'=> ModelComptable::getLesMoisValide($lesMois)))
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        //recupere les donnees du formulaire et test sa validite
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            // redirige vers la selection des visiteurs
            return $this->redirectToRoute('selectionnerVisiteur_comptable', array('id'=>$id, 'mois'=>$form->get('leMois')->getData()));
        }
        // retourne la vue
        return $this->render('@ALgsb/Comptable/selectionMois.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'form'=>$form->createView()));
    }
    
    /**
     * Affiche la vue de selection des visiteurs
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function selectionnerVisiteurAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $mois = $request->attributes->get('mois');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recupere le comptable
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        // recupere l'identite du comptable
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        // recupere la liste des visiteurs valide
        $ficheRepo = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais');
        $lesVisiteurs = $ficheRepo->findBy(array('idetat'=>'CL', 'mois'=>$mois));
        // creation du formulaire de selection des visiteurs valide
        $form = $this->createFormBuilder()
                ->add('leVisiteur', ChoiceType::class, array('label'=>'Visiteur : ', 'choices'=> ModelComptable::getLesVisiteursValide($lesVisiteurs))) 
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        // recupere les donnees du formulaire et test sa validite
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            // redirige vers l'affichage de la fiche du visiteur
            $idFiche = $ficheRepo->findOneBy(array('mois'=>$mois, 'idetat'=>'CL', 'idvisiteur'=>$form->get('leVisiteur')->getData()));
            return $this->redirectToRoute('afficherFiche_comptable', array('id'=>$id, 'idFiche'=>$idFiche->getId()));
        }
        // retourne la vue
        return $this->render('@ALgsb/Comptable/selectionVisiteur.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'form'=>$form->createView()));
    }
    
    /**
     * Affiche la fiche de frais selectionne
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function afficherFicheAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recupere le comptable
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        // recupere l'identite du comptable
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        // recupere le frais
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        // creation du dictionnaire avec les informations de la fiche de frais
        $lesDonnees['libelleEtat'] = $laFiche->getIdetat()->getLibelle();
        $lesDonnees['dateModif'] = $laFiche->getDatemodif();
        $lesDonnees['nbJustificatifs'] = $laFiche->getNbjustificatifs();
        $lesDonnees['montantValide'] = $laFiche->getMontantvalide();
        $lesDonnees['quantititeFraisForfait'] = modelBase::getquantiteFraisForfait($laFiche);
        $lesDonnees['lesFraisHorsForfait'] = ModelBase::getLignesHorsForfait($laFiche);
        // retourne la vue
        return $this->render('@ALgsb/Comptable/ficheFrais.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'lesDonnees'=>$lesDonnees, 'idFiche'=>$idFiche));
    }
    
    /**
     * Inscrit le libelle "[REFUSE]" pour un frais refuse
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function refuserFraisHorsForfaitAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        $idFrais = $request->attributes->get('idFrais');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recupere le frais hors forfait et ajout du refus
        $leFraisHorsForfait = ModelBase::getLeRepository($em, $idFrais, 'Lignefraishorsforfait');
        $leFraisHorsForfait->setLibelle('[REFUSE]'.$leFraisHorsForfait->getLibelle());
        // sauvegarde de la modification
        $em->persist($leFraisHorsForfait);
        $em->flush();
        // retour de la vue
        return $this->redirectToRoute('afficherFiche_comptable', array('id'=>$id, 'idFiche'=>$idFiche));
    }
    
    /**
     * Page de mise a jour des frais forfaitises
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function modifierFraisForfaitAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recupere le comptable et la fiche
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        // recupere l'identite du comptable
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        // recupere les quantites des frais forfait
        $lesQuantites = ModelBase::getQuantiteFraisForfait($laFiche);
        // creation du formulaire de modification des frais forfait
        $form = $this->createFormBuilder()
                ->add('ETP',    TextType::class, array('label'=>'Forfait étape : ', 'data'=>$lesQuantites['ETP']))
                ->add('KM',     TextType::class, array('label'=>'Frais Kilométrique : ', 'data'=>$lesQuantites['KM']))
                ->add('NUI',    TextType::class, array('label'=>'Nuitée Hôtel : ', 'data'=>$lesQuantites['NUI']))
                ->add('REP',    TextType::class, array('label'=>'Repas Restaurant : ', 'data'=>$lesQuantites['REP']))
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        // recupere les donnees du formulaire et test si le formulaire est valide
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            // enregistrement des nouvelles quantites et redirecation vers l'affichage de la fiche de frais
            ModelBase::enregistrerFraisForfait($form, $laFiche->getLigneFicheFraisForfait()->toArray(), $em);
            return $this->redirectToRoute('afficherFiche_comptable', array('id'=>$id, 'idFiche'=>$idFiche));
        }
        // retour de la vue
        return $this->render('@ALgsb/Comptable/modifierFraisForfait.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'form'=>$form->createView()));
    }
    
    /**
     * Reporte un frais hors forfait au mois suivant
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function reporterFraisHorsForfaitAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        $idFrais = $request->attributes->get('idFrais');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recuperation du frais hors forfait et du visiteur
        $leFraisHorsForfait = ModelBase::getLeRepository($em, $idFrais, 'Lignefraishorsforfait');
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        $leVisiteur = $laFiche->getIdVisiteur();
        //recuperation de la derniere fiche de frais du visiteur
        $lesFiches = $leVisiteur->getLesFichesFrais()->toArray();
        $laDerniereFiche = end($lesFiches);
        // test si la fiche courante et la derniere fiche de frais du visiteur
        if($laDerniereFiche->getId() == $laFiche->getId() || $laDerniereFiche->getIdetat()->getId() != 'CR')
        {
            // creation d'une fiche de frais
            ModelBase::creationNouvelleFicheFrais($leVisiteur, $em);
            // recuperation des fiches de frais du visiteur
            //$leVisiteur = $laFiche->getIdVisiteur();
            $em->refresh($leVisiteur);
            $lesFiches = $leVisiteur->getLesFichesFrais()->toArray();
            return $this->render('@ALgsb/test.html.twig', array('test'=>$lesFiches));
            // recuperation de la derniere fiche de frais du visiteur
            $laDerniereFiche = end($lesFiches);
        }
        // enregistrement du frais hors forfait dans la fiche de frais a l'etat CR
        $leFraisHorsForfait->setIdfichefrais($laDerniereFiche);
        $em->persist($leFraisHorsForfait);
        $em->flush();
        // retour de la vue
        return $this->redirectToRoute('afficherFiche_comptable', array('id'=>$id, 'idFiche'=>$idFiche));
    }
    
    /**
     * Validation d'une fiche de frais
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function validerFicheAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recuperation de la fiche et de l'etat 'Valider'
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        $unEtat = ModelBase::getLeRepository($em, 'VA', 'Etat');
        // validation de la fiche et enregistrement en base
        $laFiche->setIdetat($unEtat);
        $em->persist($laFiche);
        $em->flush();
        // retour de la vue
        return $this->redirectToRoute('accueil_comptable', array('id'=>$id));
    }
}
