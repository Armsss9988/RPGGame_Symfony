<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\GameCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //this form just set Category, Game of this entity we set after summit in controller
            ->add('Category', EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'Name',
                ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameCategory::class,
        ]);
    }
}
