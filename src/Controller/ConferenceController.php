<?php

namespace App\Controller;
use Twig\Environment; //para usar el twig
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use App\Repository\ConferenceRepository;//para poder enviar los datos de la conferencia
use App\Entity\Comment; //para enviar commo argumento 
use App\Repository\CommentRepository; // datos del easyadmin "db"
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;//para la excepcion de photo form
use App\Form\CommentFormType; //el form creado usando el make: bundle
use Doctrine\ORM\EntityManagerInterface;//cre el insert ,envia a la db
use Symfony\Component\HttpFoundation\Request; //get funcion para obtener 
use App\Entity\Conference; //mi clase para usar el valor donde nos devolvera  __string()
//*2use App\Repository\ConferenceRepository;

class ConferenceController extends AbstractController


{ private $entityManager,$twig;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)//para no llenar de argumentos nuestras funciones  :]
    {  
        $this->twig = $twig; //invoco en las funciones 
       $this->entityManager = $entityManager;
    }    


    #[Route('/', name: 'homepage')] //name:referencia a la pagina de inicio -_- para twig

    public function index(/*,ConferenceRepository $conferenceRepository*/): Response
    {
        return new Response($this->twig->render('conference/index.html.twig', [ //solo rendizamos ya que es global en conference en twig
            // 'conferences' => $conferenceRepository->findAll(),//imagina querer obtener los datos en varias funciones por eso,
            //un oyente "TwigEventSubscriber" que hage global 
        ]));
    }
    //hace una peticion por medio del id
    #[Route('/conference/{slug}', name: 'conference')] //name:referencia a la pagina de inicio console ingresar el nombre y el id :i(slug unico)

//es un string photodir creado en services.yaml para saber donde almacenarlo
    public function show(Request $request, Conference $conference, CommentRepository $commentRepository,string $photoDir): Response
    { /*mostrar un form del comentario 2 clases  */
        $comment = new Comment();//entity vacio
        $form = $this->createForm(CommentFormType::class, $comment);//crear de acuerdo al entity
        $form->handleRequest($request);/*recibiremos los valores asigna de cada valor al form */
        if($form->isSubmitted() && $form->isValid()){/*detecta si fue enviado   */
            
            $comment->setConference($conference);//a la cual pertenece nuestro comentario
/**photo */
            if($photo=$form['photo']->getData()){//si tiene un dato le almacenamos :o
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();//nombre random no duplicados,extension orginal
                try {
                    $photo->move($photoDir,$filename);//de mi carpeta temporal ha la direccion
                    } catch (FileException $e) {
                  // unable to upload the photo, give up
                                    }
              $comment->setPhotoFilename($filename);
            }

/* */




            $this->entityManager->persist($comment);//hey te falta esto :`]
            $this->entityManager->flush();//insertamos
            return $this->redirectToRoute('conference',['slug'=>$conference->getSlug()]);//redireccion
         

        }
        /* */
        $offset = max(0, $request->query->getInt('offset', 0)); //coje de la peticion,por defecto lo coloca 0, le devuelve int
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            //createAD->la relacion desc de:ultimo a inicio respecto al  id(coment)
            'comments' => $commentRepository->findBy(['conference' => $conference], ['createdAt' => 'DESC']),


            'comments' => $paginator,
            //hace los calculos 
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form'=>$form->createView()/*el form para twig */
        ]));
    }
}
