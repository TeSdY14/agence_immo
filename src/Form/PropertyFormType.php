<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat', ChoiceType::class, [
                'choices' => $this->getChoices(strtoupper('heat'))
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true
                ])
            ->add('city')
            ->add('address')
            ->add('postalCode')
            ->add('status', ChoiceType::class, [
                'choices' => $this->getChoices(strtoupper('status'))
            ])
        ;
    }

    /**
     * Permet de définir les options du formulaire de manière globale
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms'
        ]);
    }

    /**
     * Récupération des valeurs au format String
     * Inverse la clé de la constante avec la valeur associée
     * Avant : [1 => 'test'] et Après : ['test' => 1]
     * @param $constanteName
     * @return array
     */
    private function getChoices($constanteName)
    {
        return array_flip(constant("App\Entity\Property::{$constanteName}"));
    }
}
