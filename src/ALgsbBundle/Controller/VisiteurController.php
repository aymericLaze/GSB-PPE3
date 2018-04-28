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
    
    public function accueilAction(Request $request)
    {
        return $this->render('@ALgsb/Visiteur/accueil_visiteur.html.twig');
    }
}
