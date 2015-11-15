<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Battle;
use AppBundle\Form\BattleType;

class BattleController extends FOSRestController
{
    protected $em;

    public function __construct()
    {
        // $this->em = $this->get('');
    }

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
        $em = $this->get('doctrine.orm.entity_manager');
        $cmd = $em->getClassMetadata('AppBundle\Entity\Battle');
        $connection = $em->getConnection();
        $connection->beginTransaction();

        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->query('DELETE FROM '.$cmd->getTableName());
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
            $response = ['success' => 'Successfully deleted all previous games.'];
            $view = $this->view($response, 200);
            return $this->handleView($view);
        } catch (\Exception $e) {
            $connection->rollback();
            $data = ['error' => 'Could not delete all previous games.'];
            $view = $this->view($data, 200);
            return $this->handleView($view);
        }
    }
}
