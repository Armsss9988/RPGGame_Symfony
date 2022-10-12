<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('Ram')
            ->add('DiskSpace')
            ->add('UpdateTime',DateType::class, ['widget' => 'single_text',])
            ->add('Category', EntityType::class,
            [
                'class' => Category::class,
                'choice_label' =>'Name',
                'disabled' => $options['no_edit'],
                'multiple' => true,
                'expanded' => true,
                ])
            ->add('imgURL', FileType::class, [
                'label' => 'Image File',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
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
