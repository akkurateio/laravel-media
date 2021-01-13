<?php

namespace Akkurate\LaravelMedia\Forms\Resource;

use Kris\LaravelFormBuilder\Form;

class ResourceAbstractForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('image', 'file', ['label' => __('Choisir un fichier'), 'wrapper' => ['class' => 'custom-file akk-mb-4'], 'attr' => ['class' => 'custom-file-input'], 'label_attr' => ['class' => 'custom-file-label']])
            ->add('name', 'text', ['label' => __('Nom de la ressource')])
            ->add('alt', 'text', ['label' => __('Texte alternatif'), 'attr' => ['class' => 'form-control form-control-sm']])
            ->add('legend', 'text', ['label' => __('Légende de la ressource'), 'attr' => ['class' => 'form-control form-control-sm']]);
    }
}
