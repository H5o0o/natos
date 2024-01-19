<?php

namespace App\Form;

use App\Entity\Banner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class BannerFormType extends AbstractType
{
    private array $allowedMimeTypes = [
        "jpg"=>"image/jpeg",
        "png"=> "image/png",
        "webp"=> "image/webp",
    ];
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('main', FileType::class,[
                'label'=> 'Image de bannière pour les écrans larges',
                'required'=>true,
                'data_class'=> null,
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez remplir ce champ'
                    ]),
                    new File([
                        'maxSize'=> '10M',
                        'maxSizeMessage'=> 'Le fichier est trop volumineux ({{size}} {{suffix}}). Taille maximum autorisée : {{limit}}{{suffix}}',
                        'mimeTypes'=> $this->allowedMimeTypes,
                        'mimeTypesMessage' => 'Le format {{type}} n\'est pas autorisé, vous devez ajouter des fichiers aux formats {{types}}. ',
                ])
            ]])
            ->add('mobile', FileType::class,[
                'label'=> 'Image de bannière pour les écrans mobiles (facultatif)',
                'required'=>false,
                'data_class'=> null,
                'constraints'=>[
                    new File([
                        'maxSize'=> '10M',
                        'maxSizeMessage'=> 'Le fichier est trop volumineux ({{size}} {{suffix}}). Taille maximum autorisée : {{limit}}{{suffix}}',
                        'mimeTypes'=> $this->allowedMimeTypes,
                        'mimeTypesMessage' => 'Le format {{type}} n\'est pas autorisé, vous devez ajouter des fichiers aux formats {{types}}. ',
                ])
            ]])
            ->add('submit', SubmitType::class, [
                'label'=> 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Banner::class,
        ]);
    }
}
