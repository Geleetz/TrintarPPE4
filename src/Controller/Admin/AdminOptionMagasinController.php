<?php

namespace App\Controller\Admin;

use App\Entity\optionMagasin;
use App\Form\OptionMagasinType;
use App\Repository\OptionMagasinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOptionMagasinController extends AbstractController
{

    /**
     * @var OptionMagasinRepository
     */

    private $OMrepository;

    /**
     * @var EntityManagerInterface
     */

    private $em;

    public function __construct(EntityManagerInterface $em, OptionMagasinRepository $OMrepository)
    {
        $this->OMrepository = $OMrepository;
        $this->em = $em;
    }

    /**
     * @route("/adminOption", name = "admin.option.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index()
    {
        $options = $this->OMrepository->findAll();
        return $this->render('admin/optionMagasin/index.html.twig', compact('options'));
    }

    /**
     * @Route("/adminOption/create", name="admin.option.new")
     * @param optionMagasin $option
     */

    public function new (Request $request)
    {
        $option = new optionMagasin();
        $form = $this->createForm(optionMagasinType::class, $option);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($option);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès !');
            return $this->redirectToRoute('admin.option.index');
        }
        return $this->render('admin/optionMagasin/new.html.twig', [
            'option' => $option,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/adminOption/{id}", name="admin.option.edit", methods="GET|POST")
     * @param optionMagasin $option
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(optionMagasin $option, Request $request)
    {
        $form = $this->createForm(optionMagasinType::class, $option);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès !');
            return $this->redirectToRoute('admin.option.index');
        }

        return $this->render('admin/optionMagasin/edit.html.twig', [
            'option' => $option,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/adminOptionMagasin/{id}", name="admin.option.delete", methods="DELETE")
     * @param optionMagasin $option
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function delete(optionMagasin $option, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $option->getId(), $request->get('_token'))){
            $this->em->remove($option);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès !');
        }
        return $this->redirectToRoute('admin.option.index');
    }
}