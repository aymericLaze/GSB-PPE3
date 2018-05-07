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
    
    /**
     * Affichage de la page d'accueil du comptable
     * 
     * @param Request $request
     * @return ComptableController
     */
    public function accueilAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $lesMois = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais')->findBy(array('idetat'=>'CL'));
        
        $form = $this->createFormBuilder()
                ->add('leMois', ChoiceType::class, array('label'=>'Mois : ', 'choices'=> ModelComptable::getLesMoisValide($lesMois)))
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            return $this->redirectToRoute('selectionnerVisiteur_comptable', array('id'=>$id, 'mois'=>$form->get('leMois')->getData()));
        }
        
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $mois = $request->attributes->get('mois');
        
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $ficheRepo = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais');
        $lesVisiteurs = $ficheRepo->findBy(array('idetat'=>'CL', 'mois'=>$mois));
        
        $form = $this->createFormBuilder()
                ->add('leVisiteur', ChoiceType::class, array('label'=>'Visiteur : ', 'choices'=> ModelComptable::getLesVisiteursValide($lesVisiteurs))) 
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $idFiche = $ficheRepo->findOneBy(array('mois'=>$mois, 'idetat'=>'CL', 'idvisiteur'=>$form->get('leVisiteur')->getData()));
            return $this->redirectToRoute('afficherFiche_comptable', array('id'=>$id, 'idFiche'=>$idFiche->getId()));
        }
        
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        
        $lesDonnees['libelleEtat'] = $laFiche->getIdetat()->getLibelle();
        $lesDonnees['dateModif'] = $laFiche->getDatemodif();
        $lesDonnees['nbJustificatifs'] = $laFiche->getNbjustificatifs();
        $lesDonnees['montantValide'] = $laFiche->getMontantvalide();
        $lesDonnees['quantititeFraisForfait'] = modelBase::getquantiteFraisForfait($laFiche);
        $lesDonnees['lesFraisHorsForfait'] = ModelBase::getLignesHorsForfait($laFiche);
        
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        $idFrais = $request->attributes->get('idFrais');
        
        $leFraisHorsForfait = ModelBase::getLeRepository($em, $idFrais, 'Lignefraishorsforfait');
        $leFraisHorsForfait->setLibelle('[REFUSE]'.$leFraisHorsForfait->getLibelle());
        
        $em->persist($leFraisHorsForfait);
        $em->flush();
        
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        
        $leComptable = ModelBase::getLeRepository($em, $id, 'Comptable');
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');

        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $lesQuantites = ModelBase::getQuantiteFraisForfait($laFiche);
        
        $form = $this->createFormBuilder()
                ->add('ETP',    TextType::class, array('label'=>'Forfait étape : ', 'data'=>$lesQuantites['ETP']))
                ->add('KM',     TextType::class, array('label'=>'Frais Kilométrique : ', 'data'=>$lesQuantites['KM']))
                ->add('NUI',    TextType::class, array('label'=>'Nuitée Hôtel : ', 'data'=>$lesQuantites['NUI']))
                ->add('REP',    TextType::class, array('label'=>'Repas Restaurant : ', 'data'=>$lesQuantites['REP']))
                ->add('submit', SubmitType::class, array('label'=>'Valider'))
                ->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            ModelBase::enregistrerFraisForfait($form, $laFiche->getLigneFicheFraisForfait()->toArray(), $em);
            return $this->redirectToRoute('afficherFiche_comptable', array('id'=>$id, 'idFiche'=>$idFiche));
        }
        
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
        
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        $idFrais = $request->attributes->get('idFrais');
        
        $leFraisHorsForfait = ModelBase::getLeRepository($em, $idFrais, 'Lignefraishorsforfait');
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        $leVisiteur = $laFiche->getIdVisiteur();
        
        $lesFiches = $leVisiteur->getLesFichesFrais()->toArray();
        $laDerniereFiche = end($lesFiches);
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
        $em = $this->getDoctrine()->getManager();
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        
        $laFiche = ModelBase::getLeRepository($em, $idFiche, 'Fichefrais');
        $unEtat = ModelBase::getLeRepository($em, 'VA', 'Etat');
        
        $laFiche->setIdetat($unEtat);
        $em->persist($laFiche);
        $em->flush();
        
        return $this->redirectToRoute('accueil_comptable', array('id'=>$id));
    }
}
