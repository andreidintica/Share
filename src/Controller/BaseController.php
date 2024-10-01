<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactType;
use App\Form\CategoriesType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;

class BaseController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
           
        ]);
    }
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
            if($request->isMethod('POST')){
                $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $contact->setDateEnvoi(new \Datetime());
                $em->persist($contact);
                $em->flush();
        $this->addFlash('notice','Message envoyé');
        return $this->redirectToRoute('app_contact');
        }}
        return $this->render('base/contact.html.twig', [
            'form' => $form->createView()
        ]);
     }
     #[Route('/categories', name: 'app_categories')]
    public function categories(Request $request, EntityManagerInterface $em): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($categories);
                $em->flush();
                $this->addFlash('notice', 'Catégorie ajoutée');
                return $this->redirectToRoute('app_categories');
            }
        }

        $categoriesList = $em->getRepository(Categories::class)->findAll();

        return $this->render('base/categories.html.twig', [
            'form' => $form->createView(),
            'categories' => $categoriesList,
        ]);
    }
  
}
