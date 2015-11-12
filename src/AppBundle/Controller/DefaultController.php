<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Battle;
use AppBundle\Entity\User;
use AppBundle\Form\BattleType;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\LoginType;

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
            'action' => $this->generateUrl('post_battle'),
            'method' => 'POST'
        ));

        $user = new User();
        $regForm = $this->createForm(new RegistrationType(), $user, array(
            'action' => $this->generateUrl('post_user_registration'),
            'method' => 'POST'
        ));

        $loginForm = $this->createForm(new LoginType(), $user, array(
            'action' => $this->generateUrl('post_user_login'),
            'method' => 'POST'
        ));

        return $this->render('default/index.html.twig', [
            'form'      => $form->createView(),
            'regForm'   => $regForm->createView(),
            'loginForm' => $loginForm->createView()
        ]);
    }
}
