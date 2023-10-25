<?php

namespace App\Controller;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;



class AuthorController extends AbstractController
{

    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
    );
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showemail', name: 'app_author')]
    public function showemail(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findByExampleField();
        //var_dump($authors);
        return $this->render('author/showbyemail.html.twig', [
            'authors' => $authors,
        ]);
    }
    

    #[Route('/showauthors', name: 'app_showauthors')]
    public function showauthor(): Response 
    {
        
        return $this->render('author/show.html.twig', [
           
            'authorshtml' => $this->authors,
        ]);  

    }


   #[Route('/authorDetails/{id}', name: 'authorDetails')]
    public function authorDetails($id): Response
    {
        $author = null;
        
        foreach ($this->authors as $authorData) {
            if ($authorData['id'] == $id) {
                $author = $authorData;
            }
        }
      return $this->render('author/details.html.twig', [
            'author' =>$author ,
       ]);
    }

    #[Route('/showDBauthor', name: 'showDBauthor')]
    public function showDBauthor(AuthorRepository $AuthorRepository): Response
    {
        $authors = $AuthorRepository->findAll();
    
        return $this->render('author/showDBauthor.html.twig', [
            'author' => $authors,
        ]);
    }

    #[Route('/addstactic', name: 'addstactic')]
     public function addStatic(ManagerRegistry $manager): Response
    {
        $em = $manager ->getManager();
        $author = new Author();
        $author -> setUsername("ala");
        $author->setEmail("ala.mhadhbi@esprit.tn");
        
        $em->persist($author);
        $em->flush();

        return new Response  ("added succefully");
       
    }
    #[Route('/addAuthor', name: 'addAuthor')]
    public function addAuthor(ManagerRegistry $manager,Request $req): Response
    {
        $em =$manager->getManager();
        $author= new Author();
        $form = $this->createForm(AuthorType::class,  $author);
        $form->HandleRequest($req);
        if ($form->isSubmitted() && $form->isValid()){
        $em->persist($author);
        $em->flush();   
        return $this->redirectToRoute('showDBauthor');
    }
        return $this->renderForm('author/add.html.twig', [
            'x'=> $form,
        ]);
    }

    #[Route('/editAuthor/{id}', name: 'editAuthor')]
    public function editAuthor($id,ManagerRegistry $manager,AuthorRepository $repo,Request $req): Response

    {
        $em =$manager->getManager();
        $idData=$repo->find($id);
        $form = $this->createForm(AuthorType::class,$idData);
        $form->HandleRequest($req);
        if ($form->isSubmitted() && $form->isValid()){
        $em->persist($idData);    
        $em->flush();
        return $this->redirectToRoute('showDBauthor');
        }

        return $this->renderForm('author/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/deleteAuthor/{id}', name: 'deleteAuthor')]
    public function deleteAuthor($id,ManagerRegistry $manager,AuthorRepository $repo,Request $req): Response
    {
        $em=$manager->getManager();
        $idRemove=$repo->find($id);
        $em->remove($idRemove);
        $em->flush();
        return $this->redirectToRoute('showDBauthor');
  
    }

    
}
