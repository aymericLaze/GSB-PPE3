<?php

namespace ALgsbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



/**
 * Description of ComptableController
 *
 * @author laze
 */
class ComptableController extends Controller{
    
    public function accueilAction(Request $request)
    {
        return $this->render('@ALgsb/Comptable/accueil_comptable.html.twig');
    }
}
