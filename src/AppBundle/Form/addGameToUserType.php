<?php

namespace AppBundle\Form;

use AppBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class addGameToUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('game', EntityType::class, [
        'class' => 'AppBundle:Game',
        'choice_label' => function ($game) {
            return $game->getName();
        },
        'multiple' => true,
        'expanded' => false,
        'attr' => [
            'class' => 'form-control',
        ],

    ]);




    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundleadd_game_to_user';
    }
}
