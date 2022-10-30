<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class GameType extends AbstractType
{
    //build form for GAME!!!!!
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('Ram')
            ->add('DiskSpace')
            ->add('UpdateTime',DateType::class, ['widget' => 'single_text',])
            //this will create an option to choose image in form
            ->add('imgURL', FileType::class, [
                'label' => 'Image File',
                'mapped' => false,
                'required' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            'no_edit' => false
        ]);
    }
}
