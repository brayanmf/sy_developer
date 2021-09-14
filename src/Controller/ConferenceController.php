<?php

namespace App\Controller;

use Twig\Environment;//para usar el twig
//use App\Repository\ConferenceRepository;//para poder enviar los datos de la conferencia
use Symfony\Component\HttpFoundation\Request;//get funcion para obtener 
use App\Entity\Conference;//mi clase donde esta __string()
use App\Entity\Comment;//prueba
use App\Repository\CommentRepository;// datos del easyadmin "db"
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//*2use App\Repository\ConferenceRepository;

class ConferenceController extends AbstractController
{

    
    #[Route('/', name: 'homepage')]//name:referencia a la pagina de inicio -_- para twig
    
       public function index(Environment $twig/*,ConferenceRepository $conferenceRepository*/): Response
       {
         return new Response($twig->render('conference/index.html.twig', [//solo rendizamos ya que es global en conference en twig
           // 'conferences' => $conferenceRepository->findAll(),//imagina querer obtener los datos en varias funciones por eso,
            //un oyente "TwigEventSubscriber" que hage global 
        ]));
     }
    //hace una peticion por medio del id
    #[Route('/conference/{slug}', name: 'conference')]//name:referencia a la pagina de inicio console ingresar el nombre y el id :i(slug unico)
    

    public function show(Request $request, Environment $twig, Conference $conference, CommentRepository $commentRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));//coje de la peticion,por defecto lo coloca 0, le devuelve int
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($twig->render('conference/show.html.twig', [
           'conference' => $conference,
            //createAD->la relacion desc de:ultimo a inicio respecto al  id(coment)
            'comments' => $commentRepository->findBy(['conference' => $conference], ['createdAt' => 'DESC']),
          
        
            'comments' => $paginator,
           //hace los calculos 
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]));

    }



 
}
