<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;//unique validar en app indica un mensaje al usuario(no error 500)
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity(repositoryClass=ConferenceRepository::class)
 * 
 * @UniqueEntity("slug")
 */
class Conference//uniqueentity valide antes de enviar a la db en la app 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $year;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInternational;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="conference", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;//ORM le agregamos que sea unique unico 

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }
    public function __toString(): string //la salida para usarlo en el controller  para twig
        {
           return $this->city.' '.$this->year;
    }
    public function computeSlug(SluggerInterface $slugger)
        {
              //(-) no va hacer vacio por que en el backern le dcmos que es requerido con @UniqueEntity anteriormente 
            if (!$this->slug || '-' === $this->slug) {//leelo inversamente si mi slug es vacio o (-) ejcmtmos
                $this->slug = (string) $slugger->slug((string) $this)->lower();
              //(string)herramienta   sluggerinterface(clase de string)   como es url cuidado con caracters especiales asentos,etc
                // $this    (string) slugger->slug[(string)(tostring)]->minuscula
            }
       }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getIsInternational(): ?bool
    {
        return $this->isInternational;
    }

    public function setIsInternational(bool $isInternational): self
    {
        $this->isInternational = $isInternational;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setConference($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getConference() === $this) {
                $comment->setConference(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
