<?php

namespace App\Form;

use App\Entity\Biography;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class BiographyFormType extends AbstractType
{
    private array $allowedMimeTypes = [
        "jpg"=>"image/jpeg",
        "png"=> "image/png",
        "webp"=> "image/webp",
    ];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class,[
                'label'=> 'Photo de la biographie',
                'required'=>true,
                'data_class'=> null,
                // indique de ne pas lier automatiquement le champ de fichier à une classe, approprié dans ce cas avec des fichiers 
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez remplir ce champ'
                    ]),
                    new File([
                        'maxSize'=> '10M',
                        'maxSizeMessage' => 'Le fichier est trop volumineux({{size}} {{suffix}}). Taille maximum autorisée : {{limit}}{{suffix}}',
                        'mimeTypes' => $this->allowedMimeTypes,
                        'mimeTypesMessage' => 'Le format {{type}} n\'est pas autorisé, vous devez ajouter des fichiers aux formats {{types}}. ',
                    ])
                ]])
            ->add('text', TextType::class,[
                'label'=> 'Texte de votre biographie',
                'required'=>true,
                'attr' => [
                    'placeholder' => 'Saisissez le texte ici...',
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=> 'Veuillez remplir ce champ'
                    ]),
                    new Length([
                        'min'=> 10,
                        'minMessage' => 'Le texte doit contenir au moins {{ limit }} caractères.',
                    ])
            ]])
            ->add('video', UrlType::class, [
                'label'=> 'Lien video',
                'required'=>false,
                'attr' => [
                    'placeholder'=> 'Entrez le lien de la video.',
                ],
                'constraints' => [
                    new Url([
                        'message'=> 'Veuillez saisir un lien video valide.',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Biography::class,
        ]);
    }
}
