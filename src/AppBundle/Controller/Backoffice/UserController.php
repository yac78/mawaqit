<?php

namespace AppBundle\Controller\Backoffice;

use AppBundle\Entity\User;
use AppBundle\Form\EmailType;
use AppBundle\Form\UserSearchType;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/backoffice/superadmin/user")
 */
class UserController extends Controller
{

    /**
     * send email to all users
     * @Route("/send-email", name="users_send_email")
     */
    public function sendEmailAction(Request $request)
    {

        $form = $this->createForm(EmailType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get("app.user_service")->sendEmailToAllUsers($form->getData());
            $this->addFlash('success', "email.send.success");

            return $this->redirectToRoute('mosque_index');
        }

        return $this->render('user/send_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * get users by search term
     * @Route("/search-ajax", name="users_search_ajax")
     */
    public function searchAjaxAction(Request $request)
    {
        $term = $request->query->get("term");
        if (empty($term)) {
            return new JsonResponse();
        }

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("AppBundle:User")
            ->search(['search' => $term])
            ->select("u.id, u.email as label")
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($users);
    }

}
