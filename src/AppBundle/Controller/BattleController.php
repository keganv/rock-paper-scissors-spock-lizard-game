<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class BattleController extends FOSRestController
{
    public function getBattlesAction()
    {
        $battles = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Battle')->findAll();

        $view = $this->view($battles, 200)
            ->setTemplate("default/index.html.twig")
            ->setTemplateVar('battles')
            ->setTemplateData(['battles' => $battles])
        ;

        return $this->handleView($view);
    }
}
