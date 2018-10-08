<?php

namespace App\Twig\Extension;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Twig\Environment;

class FiltrosGrid extends \Twig_Extension {
    private $env = null;
    public function __construct(Environment $env) {
        $this->env = $env;
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('filtros_grilla', [$this, "dibujarFiltros"], ['is_safe' => ['html']]),
        ];
    }

    public function dibujarFiltros(FormView $form) {
        $fieldsHtml= [];
        foreach($form as $key=>$field) {
            $fieldsHtml[] = $this->renderField($key, $field);
        }
        return implode($fieldsHtml);
    }

    public function renderField($name, $field) {
        return $this->env->render('generic/filters.template.html.twig', [
            'field' => $field,
        ]);
    }
}