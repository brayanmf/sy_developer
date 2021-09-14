<?php // (callback complejo) mucho mas elegante :p 
namespace App\EntityListener;//creado manualmente,ya que no tiene que ver nada con los controladores,luego de esto se modifica services.yaml

use App\Entity\Conference;
use Doctrine\ORM\Event\LifecycleEventArgs;//ORM la database
use Symfony\Component\String\Slugger\SluggerInterface;

class ConferenceEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Conference $conference, LifecycleEventArgs $event)//amtes de crear
    {
        $conference->computeSlug($this->slugger);//llammando la funcion de entity
    }

    public function preUpdate(Conference $conference, LifecycleEventArgs $event)//antes de actualizar
    {
        $conference->computeSlug($this->slugger);
    }
}
?>