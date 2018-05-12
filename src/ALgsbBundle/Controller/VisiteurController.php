<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use ALgsbBundle\Form\LignefraishorsforfaitType;
use ALgsbBundle\Entity\Lignefraishorsforfait;

use ALgsbBundle\Model\ModelVisiteur;
use ALgsbBundle\Model\ModelBase;

use DateTime;

/**
 * Description of VisiteurController
 *
 * @author laze
 */
class VisiteurController extends Controller {
    
    private $role = 'visiteur';
    
    /**
     * Affichage de la page d'accueil du visiteur
     * 
     * @param Request $request
     * @return VisiteurController
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
        // recuperation du visiteur
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        // recuperation de l'identite du visiteur
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        // retourne la vue
        return $this->render('@ALgsb/Visiteur/accueil_visiteur.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom));
    }
    
    
    /**
     * Sélection des fiches de frais du visiteur
     * 
     * @param Request $request
     * @return VisiteurController
     */
    public function selectionnerFicheAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        // test si l'utilisateur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recuperation du visiteur
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        // recuperation de l'identite du visiteur
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        // recuperation des mois ou le visiteur a des fiches de frais
        $lesMois = ModelVisiteur::getLesMoisPourFormulaire($leVisiteur->getLesFichesFrais()->toArray());
        // creation du formulaire pour selectionner une fiche de frais
        $form = $this->createFormBuilder()
                    ->add('mois', ChoiceType::class, array('label'=>'Mois : ', 'choices'=>$lesMois))
                    ->add('submit', SubmitType::class, array('label'=>'Valider'))
                    ->getForm();
        // recuperation des donnees du formulaire et test si valide
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            // redirige vers la page de consultation de fiche de frais
            return $this->redirectToRoute('consulterFiche_visiteur', array('id'=>$id, 'idFiche'=>$form->get('mois')->getData()));
        }
        // retourne la vue
        return $this->render('@ALgsb/Visiteur/selectionnerFiche.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'form'=>$form->createView()));
    }
    
    
    /**
     * Affichage de la fiche de frais sélectionné
     * 
     * @param Request $request
     */
    public function consulterFicheAction(Request $request)
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
        // recuperation du visiteur et de la fiche de frais
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        // recuperation de l'identite du visiteur
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        // récupération des données pour la vue
        $lesDonnees['libelleEtat'] = $laFiche->getIdetat()->getLibelle();
        $lesDonnees['dateModif'] = $laFiche->getDatemodif();
        $lesDonnees['nbJustificatifs'] = $laFiche->getNbjustificatifs();
        $lesDonnees['montantValide'] = $laFiche->getMontantvalide();
        $lesDonnees['quantititeFraisForfait'] = ModelBase::getquantiteFraisForfait($laFiche);
        $lesDonnees['lesFraisHorsForfait'] = ModelBase::getLignesHorsForfait($laFiche);
        // retourne la vue
        return $this->render('@ALgsb/Visiteur/affichageFicheFrais.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'lesDonnees'=>$lesDonnees));
    }
    
    
    /**
     * Saisie de la fiche de frais du mois en cours
     * 
     * @param Request $request
     * @return type
     */
    public function saisirAction(Request $request)
    {
        // recuperation des variables
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        // test si le visiteur est connecte
        if(!ModelBase::estConnecte($request, $id, $this->role))
        {
            return $this->redirectToRoute('page_connexion');
        }
        // recuperation du visiteur
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        // recuperation de l'identite du visiteur et de la date du jour
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        $dateDuJour = new DateTime();
        // recuperation des fiches du visiteur
        $lesFiches = $leVisiteur->getLesFichesFrais()->toArray();
        // test si la fiche courante et la derniere fiche du visiteur
        if(!ModelVisiteur::isFicheDuMois(end($lesFiches)))
        {
            // creation d'une nouvelle fiche
            ModelBase::creationNouvelleFicheFrais($leVisiteur, $em);
            $lesFiches = $leVisiteur->getLesFichesFrais()->toArray();
        }
        // recuperation la derniere fiche et des quantites de la fiche
        $laFiche = end($lesFiches);
        $lesQuantites = ModelBase::getQuantiteFraisForfait($laFiche);
        // creation d'un formulaire de de saisie de la quantite des frais forfait
        $formFraisForfait = $this->createFormBuilder()
                ->add('ETP',    TextType::class, array('label'=>'Forfait étape : ', 'data'=>$lesQuantites['ETP']))
                ->add('KM',     TextType::class, array('label'=>'Frais Kilométrique : ', 'data'=>$lesQuantites['KM']))
                ->add('NUI',    TextType::class, array('label'=>'Nuitée Hôtel : ', 'data'=>$lesQuantites['NUI']))
                ->add('REP',    TextType::class, array('label'=>'Repas Restaurant : ', 'data'=>$lesQuantites['REP']))
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        // creation du formulaire de saisie d'un frais hors forfait
        $formFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, new Lignefraishorsforfait());
        // recuperation des donnees des formulaires
        $formFraisForfait->handleRequest($request);
        $formFraisHorsForfait->handleRequest($request);
        
        // test si le formulaires des frais forfait est valide
        if($formFraisForfait->isSubmitted() && $formFraisForfait->isValid())
        {
            // enregistrement dans la base des quantites de frais forfait
            ModelBase::enregistrerFraisForfait($formFraisForfait, $laFiche->getLigneFicheFraisForfait()->toArray(), $em);
            return $this->redirectToRoute('saisir_visiteur', array('id'=>$id));
        }
        // test si le formulaire des frais hors forfait est valide
        if($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid())
        {
            // enregistrement dans la base du nouveau frais hors forfait
            $leFrais = ModelVisiteur::enregistrerFraisHorsForfait($formFraisHorsForfait, $laFiche);
            $em->persist($leFrais);
            $em->flush();

            return $this->redirectToRoute('saisir_visiteur', array('id'=>$id));
        }
        // parametre envoye dans l'URL
        $parametres = array(
                        'id'=>$id,
                        'nom'=>$nom,
                        'prenom'=>$prenom,
                        'dateDuJour'=>$dateDuJour,
                        'tabFraisHorsForfait'=>$laFiche->getLigneFicheFraisHorsForfait(),
                        'formFraisForfait'=>$formFraisForfait->createView(),
                        'formFraisHorsForfait'=>$formFraisHorsForfait->createView(),
                    );
        // retourne la vue
        return $this->render('@ALgsb/Visiteur/saisirFiche.html.twig', $parametres);
    }
}