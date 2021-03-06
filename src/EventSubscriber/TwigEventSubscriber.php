<?php

namespace App\EventSubscriber;//se ejecuta antes de los controladores

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\Repository\ConferenceRepository;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $conferenceRepository;
    
        public function __construct(Environment $twig, ConferenceRepository $conferenceRepository)
        {
            $this->twig = $twig;
            $this->conferenceRepository = $conferenceRepository;
        }
    public function onControllerEvent(ControllerEvent $event)
    { $this->twig->addGlobal('conferences', $this->conferenceRepository->findAll());//para todas mis twig
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
