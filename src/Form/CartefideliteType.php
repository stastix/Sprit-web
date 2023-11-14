<?php

namespace App\Form;

use App\Entity\Cartefidelite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver; 
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Validator\Context\ExecutionContextInterface;



class CartefideliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ptsfidelite', null, [
            'constraints' => [
                new Assert\GreaterThanOrEqual([
                    'value' => 0, 

                    'message' => '',
                ]),
            ],
        ])
        ->add('datedebut', DateType::class)
        ->add('datefin', DateType::class)
        ->add('etatcarte', ChoiceType::class, [
                'choices' => [
                    'Active' => 'Active',
                    'Suspended' => 'suspended',
                    'Inactive' => 'inactive',
                ],
            ])
            ->add('niveaucarte', ChoiceType::class, [
                'choices' => [
                    'bronze' => 'bronze',
                    'silver' => 'silver',
                    'gold' => 'gold',
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'prenom',
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cartefidelite::class,
        ]); 
    } 

    public function validateDates($value, ExecutionContextInterface $context)
    {
        $form = $context->getObject();
    
        // Replace 'datedebut' with the actual name of your datedebut field
        $datedebutField = $form->get('datedebut');
    
        // Check if the datedebut field exists
        if (!$datedebutField) {
            return;
        }
    
        $datedebut = $datedebutField->getData();
    
        if ($datedebut && $value <= $datedebut) {
            $context
                ->buildViolation('Datefin must be greater than Datedebut.')
                ->atPath('datefin')
                ->addViolation();
        }
    }

}