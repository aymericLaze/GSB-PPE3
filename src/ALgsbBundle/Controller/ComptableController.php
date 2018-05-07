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
        $id = $request->attributes->get('id');
        
        $leComptable = $this->getComptable($id);
        
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
        $id = $request->attributes->get('id');
        
        $leComptable = $this->getComptable($id);
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $lesMois = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais')->findBy(array('idetat'=>'CL'));
        
        $form = $this->createFormBuilder()
                ->add('leMois', ChoiceType::class, array('label'=>'Mois : ', 'choices'=> ModelComptable::getLesMoisValide($lesMois)))
                /*->add('leMois', ChoiceType::class, array('label'=>'Mois : ', 'choices'=>$this->getLesMoisValide($lesMois)))*/
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
        $id = $request->attributes->get('id');
        $mois = $request->attributes->get('mois');
        
        $leComptable = $this->getComptable($id);
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $ficheRepo = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais');
        $lesVisiteurs = $ficheRepo->findBy(array('idetat'=>'CL', 'mois'=>$mois));
        
        $form = $this->createFormBuilder()
                ->add('leVisiteur', ChoiceType::class, array('label'=>'Visiteur : ', 'choices'=> ModelComptable::getLesVisiteursValide($lesVisiteurs))) 
                /*->add('leVisiteur', ChoiceType::class, array('label'=>'Visiteur : ', 'choices'=>$this->getLesVisiteursValide($lesVisiteurs)))*/
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
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        
        $leComptable = $this->getComptable($id);
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $laFiche = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais')->find($idFiche);
        
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
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        $idFrais = $request->attributes->get('idFrais');
        
        $em = $this->getDoctrine()->getManager();
        $leFraisHorsForfait = $em->getRepository('ALgsbBundle:Lignefraishorsforfait')->find($idFrais);
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
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        
        $leComptable = $this->getComptable($id);
        
        $nom = $leComptable->getNom();
        $prenom = $leComptable->getPrenom();
        
        $laFiche = $this->getDoctrine()->getManager()->getRepository('ALgsbBundle:Fichefrais')->find($idFiche);
        
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
            $this->enregistrerFraisForfait($form, $laFiche->getLigneFicheFraisForfait());
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
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        $idFrais = $request->attributes->get('idFrais');
        
        $em = $this->getDoctrine()->getManager();
        $leFraisHorsForfait = $em->getRepository('ALgsbBundle:Lignefraishorsforfait')->find($idFrais);
        
        $laFiche = $em->getRepository('ALgsbBundle:FicheFrais')->find($idFiche);
        $leVisiteur = $laFiche->getIdVisiteur();
        
        $laDerniereFiche = $this->array_get_last($leVisiteur->getLesFichesFrais()->toArray());
        
        if($laDerniereFiche->getId() == $laFiche->getId() && $laDerniereFiche->getIdetat()->getId() == 'CL')
        {
            ModelBase::creationNouvelleFicheFrais($leVisiteur, $em);
        }
        
        $laDerniereFiche = $this->array_get_last($leVisiteur->getLesFichesFrais()->toArray());
        
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
        $id = $request->attributes->get('id');
        $idFiche = $request->attributes->get('idFiche');
        
        $em = $this->getDoctrine()->getManager();
        
        $laFiche = $em->getRepository('ALgsbBundle:Fichefrais')->find($idFiche);
        $unEtat = $em->getRepository('ALgsbBundle:Etat')->find('VA');
        
        $laFiche->setIdetat($unEtat);
        $em->persist($laFiche);
        $em->flush();
        
        return $this->redirectToRoute('accueil_comptable', array('id'=>$id));
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
    
    /**
     * Enregistre les frais forfait du visiteur
     * 
     * @param FormBuilder $form
     * @param array $lesLignesFrais
     */
    private function enregistrerFraisForfait($form, $lesLignesFrais)
    {
        $em = $this->getDoctrine()->getManager();
        
        foreach($lesLignesFrais as $laLigne)
        {
            $idEtat = $laLigne->getIdfraisforfait()->getId();
            $laLigne->setQuantite($form->get($idEtat)->getData());
            $em->persist($laLigne);
        }
        
        $em->flush();
    }
    
    /**
     * Retourne le dernier element d'un tableau
     * 
     * @param array $tab
     * @return array
     */
    private function array_get_last(array $tab)
    {
        $res = count($tab) - 1;
    return $tab[$res];
    }
}
