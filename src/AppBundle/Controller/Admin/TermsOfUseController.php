<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\TermsOfUseType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/cgu")
 */
class TermsOfUseController extends Controller
{
    /**
     * @Route(name="terms_of_use_index")
     */
    public function indexAction(Request $request)
    {

        $form = $this->createForm(TermsOfUseType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $user->setTou(true);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('mosque_index');
        }

        return $this->render('terms_of_use/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
