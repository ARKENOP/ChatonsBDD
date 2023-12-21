<?php

namespace App\Form;

use App\Entity\Chaton;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; //pour le bouton OK
use Symfony\Component\Form\Extension\Core\Type\FileType; //pour le bouton OK

class ChatonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Photo')
            ->add('Photo', FileType::class, [
                'label' => 'Photo (JPG, PNG, GIF)',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => '.jpg,.jpeg,.png,.gif'],
                ])
            ->add('Categorie',EntityType::class, [
                'class' => 'App\Entity\Categorie',
                'choice_label' => 'Titre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chaton::class,
        ]);
    }
}
