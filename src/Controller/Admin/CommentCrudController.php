<?php

namespace App\Controller\Admin;
use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }
    public function configureCrud(Crud $crud): Crud //modificando el crud
    {
        return $crud
          ->setEntityLabelInSingular('Conference Comment')
            ->setEntityLabelInPlural('Conference Comments')
            ->setSearchFields(['author', 'text', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC']);
       ;
    }

    
    public function configureFields(string $pageName): iterable
    {
       /* return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];*/
   
        //ordenando del modo deseado field->especificando el tpo
        yield AssociationField::new('conference');//campo relacionado -especificando la tabla
        yield TextField::new('author');
        yield EmailField::new('email');
        yield TextareaField::new('text')->hideOnIndex();//ocultando en la tabla
        yield ImageField::new('photo_filename')->setBasePath('/uploads/photos')->setLabel('Photo')->onlyOnIndex();//onlyindex oculto al editar crear etc
        
     //  yield TextField::new('photo_filename');//el url :]

        $createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([// Asc,desc ->flechabtn y mostrarnos un tipo de modif
            'html5' => true,
            'years' => range(date('Y'), date('Y') + 5),
            'widget' => 'single_text',
        ]);
        if (Crud::PAGE_EDIT === $pageName) {//inabilitar al editar el input
            yield $createdAt->setFormTypeOption('disabled', true);
        } else {
            yield $createdAt;
        }
    }
    public function configureFilters(Filters $filters): Filters //agregar un boton para filtrar 
    {
        return $filters
            ->add(EntityFilter::new('conference'));
  }


    
}
