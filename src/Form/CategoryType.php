<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType
{
    // build form Category
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Game of this category is set in Entity GameCategory
        $builder
            ->add('Name')
            ->add('Description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
