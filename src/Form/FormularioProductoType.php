<?php

namespace App\Form;

use App\Entity\Productos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormularioProductoType extends AbstractType
{
    private const INPUT_STYLE = "form-control";
    
    private const LABEL_STYLE = "form-label mt-3 fw-bold text-dark";

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clave_producto', IntegerType:: class,[
                'label' => 'Clave de producto',
                'required' => $options['is_edit'] ? true : false, //Si esta editando se habilita el required
                'label_attr' => [
                    'class' => self::LABEL_STYLE, //Se asignan las propiedades de label
                ],
                'attr' => [
                    'class' => self::INPUT_STYLE, //Se asignan las propiedades del input
                    'id' => 'user_form_clave',
                    'placeholder' => 'Escribre la clave del producto',
                    'readonly' => $options['is_edit'] ? true : false, //Si esta editando se habilita el readonly
                ]
            ])
            ->add('nombre', TextType:: class,[
                'label' => 'Nombre',
                'required' => $options['is_edit'] ? true : false, //Si esta editando se habilita el required
                'label_attr' => [
                    'class' => self::LABEL_STYLE, //Se asignan las propiedades de label
                ],
                'attr' => [
                    'class' => self::INPUT_STYLE,//Se asignan las propiedades del input
                    'id' => 'user_form_nombre',
                    'placeholder' => 'Escribre el nombre del producto'
                ]
            ])
            ->add('precio', MoneyType:: class,[
                'label' => 'Precio',
                'currency' => 'MXN',
                'required' => $options['is_edit'] ? true : false,//Si esta editando se habilita el required
                'label_attr' => [
                    'class' => self::LABEL_STYLE, //Se asignan las propiedades de label
                ],
                'attr' => [
                    'class' => self::INPUT_STYLE, //Se asignan las propiedades del input
                    'id' => 'user_form_precio',
                    'placeholder' => 'Escribre el precio del producto',
                    'step' => '0.01', // Permite decimales en el input
                    'min' => '0' // Establece un valor mínimo
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Productos::class,
            'is_edit' => false, //se establece la opcion de editar para que funcione la validacion
        ]);
    }
}
