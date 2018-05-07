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
    
    /**
     * Affichage de la page d'accueil du visiteur
     * 
     * @param Request $request
     * @return VisiteurController
     */
    public function accueilAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        
        $lesMois = ModelVisiteur::getLesMoisPourFormulaire($leVisiteur->getLesFichesFrais()->toArray());
        
        $form = $this->createFormBuilder()
                    ->add('mois', ChoiceType::class, array('label'=>'Mois : ', 'choices'=>$lesMois))
                    ->add('submit', SubmitType::class, array('label'=>'Valider'))
                    ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            return $this->redirectToRoute('consulterFiche_visiteur', array('id'=>$id, 'idFiche'=>$form->get('mois')->getData()));
        }
        
        return $this->render('@ALgsb/Visiteur/selectionnerFiche.html.twig', array('id'=>$id, 'nom'=>$nom, 'prenom'=>$prenom, 'form'=>$form->createView()));
    }
    
    
    /**
     * Affichage de la fiche de frais sélectionné
     * 
     * @param Request $request
     */
    public function consulterFicheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        
        // récupération des données pour la vue
        $lesDonnees['libelleEtat'] = $laFiche->getIdetat()->getLibelle();
        $lesDonnees['dateModif'] = $laFiche->getDatemodif();
        $lesDonnees['nbJustificatifs'] = $laFiche->getNbjustificatifs();
        $lesDonnees['montantValide'] = $laFiche->getMontantvalide();
        $lesDonnees['quantititeFraisForfait'] = ModelBase::getquantiteFraisForfait($laFiche);
        $lesDonnees['lesFraisHorsForfait'] = ModelBase::getLignesHorsForfait($laFiche);
        
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        
        $leVisiteur = ModelBase::getLeRepository($em, $id, 'Visiteur');
        
        $nom = $leVisiteur->getNom();
        $prenom = $leVisiteur->getPrenom();
        $dateDuJour = new DateTime();
        
        $lesFiches = $leVisiteur->getLesFichesFrais()->toArray();
        if(!ModelVisiteur::isFicheDuMois(end($lesFiches)))
        {
            ModelBase::creationNouvelleFicheFrais($leVisiteur, $em);
            $lesFiches = $leVisiteur->getLesFichesFrais()->toArray();
        }
        
        $laFiche = end($lesFiches);
        $lesLignesFraisForfait = $laFiche->getLigneFicheFraisForfait()->toArray();
        
        $formFraisForfait = $this->createFormBuilder()
                ->add('ETP',    TextType::class, array('label'=>'Forfait étape : ', 'data'=>$lesLignesFraisForfait[0]->getQuantite()))
                ->add('KM',     TextType::class, array('label'=>'Frais Kilométrique : ', 'data'=>$lesLignesFraisForfait[1]->getQuantite()))
                ->add('NUI',    TextType::class, array('label'=>'Nuitée Hôtel : ', 'data'=>$lesLignesFraisForfait[2]->getQuantite()))
                ->add('REP',    TextType::class, array('label'=>'Repas Restaurant : ', 'data'=>$lesLignesFraisForfait[3]->getQuantite()))
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        
        $formFraisHorsForfait = $this->createForm(LignefraishorsforfaitType::class, new Lignefraishorsforfait());
        
        $formFraisForfait->handleRequest($request);
        $formFraisHorsForfait->handleRequest($request);
        
        if($formFraisForfait->isSubmitted() && $formFraisForfait->isValid())
        {
            ModelBase::enregistrerFraisForfait($formFraisForfait, $lesLignesFraisForfait, $em);
            return $this->redirectToRoute('saisir_visiteur', array('id'=>$id));
        }
        
        if($formFraisHorsForfait->isSubmitted() && $formFraisHorsForfait->isValid())
        {
            $leFrais = ModelVisiteur::enregistrerFraisHorsForfait($formFraisHorsForfait, $laFiche);
            
            $em->persist($leFrais);
            $em->flush();
            
            return $this->redirectToRoute('saisir_visiteur', array('id'=>$id));
        }
        
        $parametres = array(
                        'id'=>$id,
                        'nom'=>$nom,
                        'prenom'=>$prenom,
                        'dateDuJour'=>$dateDuJour,
                        'tabFraisHorsForfait'=>$laFiche->getLigneFicheFraisHorsForfait(),
                        'formFraisForfait'=>$formFraisForfait->createView(),
                        'formFraisHorsForfait'=>$formFraisHorsForfait->createView(),
                    );
        
        return $this->render('@ALgsb/Visiteur/saisirFiche.html.twig', $parametres);
    }
}