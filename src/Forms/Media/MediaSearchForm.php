<?php

namespace Akkurate\LaravelMedia\Forms\Media;

use Kris\LaravelFormBuilder\Form;

class MediaSearchForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('q', 'search', ['attr' => ['class' => 'form-control'], 'value' => request('q'), 'label' => __('Rechercher')]);

    }
}
