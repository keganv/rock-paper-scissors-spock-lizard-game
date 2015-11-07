<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;

class RegistrationController extends FOSRestController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUserRegistrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user, [
            'action' => $this->generateUrl('post_user_registration'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $response = ['success' => 'You\'ve successfully registered, you may now battle!'];
            $view = $this->view($response, 200)
                ->setTemplate('registration/register.html.twig')
                ->setTemplateData(['form' => $form->createView()]);
            return $this->handleView($view);
        }

        $response = (string) $form->getErrors(true, true);
        $view = $this->view($response, 400);

        return $this->handleView($view);
    }
}