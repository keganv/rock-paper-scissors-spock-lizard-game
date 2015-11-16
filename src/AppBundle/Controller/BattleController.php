<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Battle;
use AppBundle\Form\BattleType;

class BattleController extends FOSRestController
{
    public function getBattlesAction(Request $request)
    {
        if ($user = ($this->getUser())) {
            $em = $this->get('doctrine.orm.entity_manager');
            $q = $em->createQuery('SELECT b FROM AppBundle\Entity\Battle b WHERE b.user = :user')
                ->setParameter('user', $user->getId());
            $battles = $q->getArrayResult();

            $response = [
                'user_victories'     => 0,
                'computer_victories' => 0,
                'total_ties'         => 0
            ];

            foreach ($battles as $battle) {
                if ($battle['victor'] === 0) {
                    $response['user_victories']++;
                } elseif ($battle['victor'] === 1) {
                    $response['computer_victories']++;
                } else {
                    $response['total_ties']++;
                }
            }

            $view = $this->view($response, 200);
            return $this->handleView($view);
        }
    }

    /**
     * Creates a new Battle entity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postBattleAction(Request $request)
    {
        // Check if there is a user
        if (!$this->getUser()) {
            $response = ['error' => 'You must login or create a user to battle.'];
            $view = $this->view($response, 400);
            return $this->handleView($view);
        }

        $battle = new Battle();
        $form = $this->createForm(new BattleType(), $battle, array(
            'action' => $this->generateUrl('post_battle'),
            'method' => 'POST'
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            $em = $this->get('doctrine.orm.entity_manager');
            $battle->setUser($user);
            $em->persist($battle);
            $em->flush();

            $qb = $em->createQueryBuilder();
            $qb->select('b')
                ->from('AppBundle:Battle', 'b')
                ->where('b.user = :user')
                ->setMaxResults(1)
                ->orderBy('b.id', 'DESC')
                ->setParameter('user', $user);
            $query = $qb->getQuery();
            $lastBattle = $query->getArrayResult();
            $response = ['battle-info' => $lastBattle];
            $view = $this->view($response, 200);
            return $this->handleView($view);
        }

        $errors = [];
        foreach($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }
        $view = $this->view($errors, 500);
        return $this->handleView($view);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function deleteBattlesAction()
    {
        // Check if there is a user
        if (!$user = ($this->getUser())) {
            $response = ['error' => 'You cannot delete battles.'];
            $view = $this->view($response, 400);
            return $this->handleView($view);
        }

        $em = $this->get('doctrine.orm.entity_manager');
        try {
            $q = $em->createQuery('DELETE FROM AppBundle\Entity\Battle b WHERE b.user = :user')
                ->setParameter('user', $user->getId());
            $q->execute();
            $response = ['success' => 'Successfully deleted all previous games.'];
            $view = $this->view($response, 200);
            return $this->handleView($view);
        } catch (\Exception $e) {
            $response = ['error' => 'Could not delete all previous games.'];
            $view = $this->view($response, 400);
            return $this->handleView($view);
        }
    }
}
