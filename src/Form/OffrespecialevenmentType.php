<?php

namespace App\Form;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use App\Entity\Offrespecialevenment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffrespecialevenmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The description cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
                ->add('dateDepart')
            ->add('prix')
            ->add('categorie')
            ->add('guideId')
            ->add('destination')
            ->add('image') 
            ->add('niveau')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offrespecialevenment::class,
        ]);
    }
}
