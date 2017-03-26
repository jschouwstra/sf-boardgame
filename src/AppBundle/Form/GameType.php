<?php

namespace AppBundle\Form;

use AppBundle\Entity\PlayLog;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
        $builder
            ->add('type', EntityType::class, [
                'class' => 'AppBundle:Type',
                'choice_label' => function ($type) {
                    return $type->getName();
                },
                'multiple' => false,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control',
                ],

            ]);

        $builder->add('playlogs', CollectionType::class, array(
            'entry_type' => PlayLogType::class,
            'label' => false
        ));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Game'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_game';
    }


}
