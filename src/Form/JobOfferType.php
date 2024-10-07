<?php

namespace App\Form;

use App\Entity\JobOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'A postuler' => 'A postuler',
                    'En attente' => 'En attente',
                    'Entretien' => 'Entretien',
                    'Refusé' => 'Refusé',
                    'Accepté' => 'Accepté'
                ],
                'label' => "Statut de la demande :",
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('title', TextType::class, [
                'label' => "Nom de votre offre :",
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('company', TextType::class, [
                'label' => "Nom de la compagnie :",
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('link', TextType::class, [
                'label' => "Lien de l'offre :",
                'required' => false,
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('location', TextType::class, [
                'label' => "Adresse de l'offre :",
                'required' => false,
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('salary', TextType::class, [
                'label' => "Salaire :",
                'required' => false,
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('contactPerson', TextType::class, [
                'label' => "Nom du contact :",
                'required' => false,
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('contactEmail', TextType::class, [
                'label' => "Email de ce contact :",
                'required' => false,
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('applicationDate', DateType::class, [
                'label' => "Date de postulation :",
                'widget' => 'single_text',
                'attr' => ['class' => 'block w-full mt-1 p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm'],
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'mt-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
