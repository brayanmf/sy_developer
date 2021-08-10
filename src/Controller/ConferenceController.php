<?php

namespace App\Controller;

use Twig\Environment;//para usar el twig
//use App\Repository\ConferenceRepository;//para poder enviar los datos de la conferencia
//use Symfony\Component\HttpFoundation\Request;get funcion para obtener 
use App\Entity\Conference;//mi clase 
use App\Repository\CommentRepository;//para datos
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferenceController extends AbstractController
{
    #[Route('/conference/{id}', name: 'conference')]//name:referencia a la pagina de inicio
    public function show(Environment $twig, Conference $conference,CommentRepository $commentRepository): Response
    {
        return new Response($twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $commentRepository->findBy(['conference'=>$conference],['createdAt'=>'DESC']),
                ]));

    }
   /* public function index(): Response */ //el por dfecto
   
        /*return $this->render('conference/index.html.twig', [
            'controller_name' => 'ConferenceController',
        ]);*/


  /* public function index(Request $request): Response//lo basico para ver el funcionamiento get
    {
        $greet='';
        if($name=$request->query->get('hello')){//el get de la url a recibir
            $greet=sprintf('<h1>Hello %s!</h1>',htmlspecialchars($name));
        }
        return new Response(<<<EOF
        <html>
          <body>
          $greet
              <img src="/images/mouse.jpg" />
          </body>
        </html>
        EOF
              ); */
}
