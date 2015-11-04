<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Battle;
use AppBundle\Form\BattleType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $battle = new Battle();
        $form = $this->createForm(new BattleType(), $battle, array(
            'action' => $this->generateUrl('index'),
            'method' => 'POST'
        ));

        return $this->render('default/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
