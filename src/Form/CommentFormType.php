<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author',null,['label'=>'Tu nombre :',])//null por que string por defecto text
            ->add('text')
            ->add('email',EmailType::class,['label'=>'Tu Correo :',])
            //->add('createdAt')lo calcula al crear
            ->add('photo', FileType::class, [//cambiamos la referencia para usarlo en el controladr
                                'required' => false,//no obligatorio
                                'mapped' => false,//no se encuentra mapeado en el entity,"campo extra"
                                'constraints' => [//el contenido debe ser de tipo imgen de 1
                                    new Image(['maxSize' => '1024k'])
                                ],
                            ])
            ->add('submit',SubmitType::class)
            //->add('conference')resolvemos en el controller
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
