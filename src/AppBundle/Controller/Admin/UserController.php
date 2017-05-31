<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/admin/user", requirements={"_locale"= "en|fr|ar"}, defaults={"_local"="fr"})
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserController extends Controller {

    /**
     * @Route("/", name="user_index")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")->findAll();
        return $this->render('user/index.html.twig', [
                    "users" => $users
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     */
    public function deleteAction(Request $request, User $user) {
        if (!$this->getUser()->isAdmin() || $this->getUser() === $user) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', $this->get("translator")->trans("form.delete.success", [
                    "%object%" => "du user", "%name%" => $user->getUsername()
        ]));
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/show/{id}", name="user_show")
     */
    public function showAction(Request $request, User $user) {
        return $this->render('user/show.html.twig', [
                    "user" => $user
        ]);
    }
    
    /**
     * @Route("/enable/{id}/{status}", name="ajax_user_enable")
     */
    public function enableAction(Request $request, User $user, $status) {
        $em = $this->getDoctrine()->getManager();
        $user->setEnabled($status === "true");
        $em->persist($user);
        $em->flush();
        return new Response();
    }

}
