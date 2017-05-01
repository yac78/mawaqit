<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Mosque;
use AppBundle\Form\MosqueType;

/**
 * @Route("/mosque")
 */
class MosqueController extends Controller {

    /**
     * @Route("/", name="mosque_index")
     */
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $mosques = $em->getRepository("AppBundle:Mosque")->findAll();
        return $this->render('mosque/index.html.twig', [
                    "mosques" => $mosques
        ]);
    }

    /**
     * @Route("/create", name="mosque_create")
     */
    public function createAction(Request $request) {


        $mosque = new Mosque();
        $form = $this->createForm(MosqueType::class, $mosque);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $mosque = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($mosque);
            $em->flush();

//            $this->addFlash('error', "Une erreur est survenue, votre création de kit téléchargement n'a pu être réalisée.");

            return $this->redirectToRoute('mosque_index');
        }
        
//        die(dump($form->getErrors()));

        return $this->render('mosque/create.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="mosque_edit")
     */
    public function editAction(Request $request, $id) {
        return $this->render('mosque/create.html.twig', []);
    }

    /**
     * @Route("/delete/{id}", name="mosque_delete")
     */
    public function deleteAction(Request $request, $id) {
        
    }

}
