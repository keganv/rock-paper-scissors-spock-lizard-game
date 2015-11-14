<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;

class UserController extends FOSRestController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postUserRegistrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createRegistrationForm($user);

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Start the user session
            $token = new UsernamePasswordToken($user, null, 'user_provider', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            // Send the response
            $response = ['success' => 'You\'ve successfully registered, you may now battle!'];
            $view = $this->view($response, 200)
                ->setTemplate('registration/register.html.twig')
                ->setTemplateData(['form' => $form->createView()]);
            return $this->handleView($view);
        }

        $errors = $this->getErrorMessages($form);
        $view = $this->view($errors, 400);

        return $this->handleView($view);
    }

    /**
     * POST Route annotation.
     * @Post("/user/login/")
     */
    public function userLoginAction(Request $request)
    {
        //var_dump($request->request->get('login'));die;
        $username = trim($request->request->get('login')['username']);
        $password = trim($request->request->get('login')['password']);
        $repo = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:User');
        $user = $repo->findOneBy(['username' => $username]);

        if ($user) {
            // Get the encoder for the users password
            $encoderService = $this->get('security.encoder_factory');
            $encoder = $encoderService->getEncoder($user);

            if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
                // Start the user session
                $token = new UsernamePasswordToken($user, null, 'user_provider', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);

                // Return the response
                $response = ['success' => 'Login success, you may now battle!'];
                $view = $this->view($response, 200)->setFormat('json');
                return $this->handleView($view);
            } else {
                // Password is bad
                $response = ['error' => 'Sorry, the password is incorrect.'];
                $view = $this->view($response, 400)->setFormat('json');
                return $this->handleView($view);
            }
        }

        $response = ['error' => 'Sorry, cannot find that username.'];
        $view = $this->view($response, 400)->setFormat('json');
        return $this->handleView($view);
    }

    private function createRegistrationForm(User $user)
    {
        return $this->createForm(new RegistrationType(), $user, [
            'action' => $this->generateUrl('post_user_registration'),
            'method' => 'POST'
        ]);
    }

    private function getErrorMessages(Form $form) {
        $errors = [];
        foreach($form->getErrors(true, true) as $key => $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
}