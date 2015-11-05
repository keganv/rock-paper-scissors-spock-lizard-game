<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Battle;
use AppBundle\Form\BattleType;

class BattleController extends FOSRestController
{
    public function getBattlesAction()
    {
        $battles = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Battle')->findAll();

        $view = $this->view($battles, 200);

        return $this->handleView($view);
    }

    /**
     * Creates a new Battle entity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postBattleAction(Request $request)
    {
        $battle = new Battle();
        $form = $this->createForm(new BattleType(), $battle, array(
            'action' => $this->generateUrl('post_battle'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($battle);
            $em->flush();

            $data = [0 => 'You won the game!'];
            $view = $this->view($data, 200);
            return $this->handleView($view);
        }

        $errors = [];
        foreach($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }
        $view = $this->view($errors, 500);
        return $this->handleView($view);
    }
}
