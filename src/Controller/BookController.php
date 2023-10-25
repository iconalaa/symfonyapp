<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $manager,Request $req): Response

    {
        $em =$manager->getManager();
        $book= new Book();
        $form = $this->createForm(BookType::class,  $book);
        $form->HandleRequest($req);
        if ($form->isSubmitted() && $form->isValid()){
        $em->persist($book);
        $em->flush();   
        return $this->redirectToRoute('showDBbook');
    }
        return $this->renderForm('book/addbook.html.twig', [
            'book'=> $form,
        ]);
    }
    
     #[Route('/showbook/{id}', name: 'showbook')]
    public function showbook($id, BookRepository $bookRepository): Response
   { 
        $books = $bookRepository->findBooksByAuthor($id);
    
        return $this->render('author/showauthorbook.html.twig', [
        'booktitle' => $books,
        ]);
    }

    


    #[Route('/showDBbook', name: 'showDBbook')]
    public function showDBbook(BookRepository $repo): Response
    {
        $books = $repo->findAll();
    
        return $this->render('book/showDBbook.html.twig', [
            'books' => $books,
        ]);

    }
    



        
    
}
