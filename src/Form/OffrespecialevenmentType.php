<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use App\Entity\Offrespecialevenment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive; 
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual; 
use Symfony\Component\Validator\Constraints\Range;


class OffrespecialevenmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a title.']),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The title cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The description cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('dateDepart')

            ->add('prix', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a price.']),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'Price must be a positive value.',
                    ]),
                ],
            ])
            ->add('categorie', null, [
                'constraints' => [new Length([
                    'max' => 255,
                    'maxMessage' => 'The description cannot be longer than {{ limit }} characters.',
                ]),

                ],
            ])
            ->add('guideId', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a guideId.']),
                    new Range([
                        'min' => 1,
                        'max' => 100,
                        'minMessage' => 'GuideId must be at least {{ limit }}.',
                        'maxMessage' => 'GuideId cannot be more than {{ limit }}.',
                    ]),
                ],
            ])
                        ->add('destination', null, [
                'constraints' => [
                    // Add constraints specific to your destination field.
                ],
            ])
            ->add('image', null, [
                'constraints' => [
                    // Add constraints specific to your image field.
                ],
            ])
            ->add('niveau', ChoiceType::class, [
                'choices' => [
                    'bronze' => 'bronze',
                    'silver' => 'silver',
                    'gold' => 'gold',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offrespecialevenment::class,
        ]);
    }
}
