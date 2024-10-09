<?php

namespace App\Controller;

use App\Entity\Productos;
use App\Form\FormularioProductoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="crud_show_index")
     */
    public function index(): Response
    {
        $productos = $this->em->getRepository(Productos::class)->findAll(); //Retorna todos los datos de la base de datos
        return $this->render('home/index.html.twig', [
            'productos' => $productos, //Se mandan los datos a la vista
        ]);
    }

    /**
     * @Route("/formulario", name="crud_add")
     */
    public function add(Request $request): Response
    {
        $productos = new Productos(); //Se crea una instancia de productos
        $form = $this->createForm(FormularioProductoType::class, $productos,[ //Se crea el formulario con los atributos que tiene productos
            'is_edit' => false
        ]); 
        $form->handleRequest($request); //Se maneja la respuesta

        if ($form->isSubmitted() && $form->isValid()) //Si el formulario se envio y es valido
        {
            try {
                $campos = $form->getData(); // Obtiene los datos del formulario

                // Obtener la conexión a la base de datos
                $conn = $this->em->getConnection();
                // Preparar la llamada al procedimiento almacenado
                $stmt = $conn->prepare('CALL insertar_producto(:clave_producto, :nombre, :precio, @mensaje)');
    
                // Asignar los parámetros
                $stmt->bindValue(':clave_producto', $campos->getClave());
                $stmt->bindValue(':nombre', $campos->getNombre());
                $stmt->bindValue(':precio', $campos->getPrecio());
    
                // Ejecutar el procedimiento almacenado
                $stmt->execute();
    
                // Obtener el mensaje de salida directamente desde la variable
                $stmt = $conn->query('SELECT @mensaje AS mensaje'); // Consulta para obtener el mensaje
                $mensaje = $stmt->fetchOne(); // Recuperar directamente la columna
                $mensaje == '0' ? $this->addFlash('error', 'Clave ya registrada') : $this->addFlash('success', 'Producto registrado'); //Muestra el mensaje flash

                return $this->redirectToRoute('crud_show_index'); //Redirecciona a los datos

            } catch (\Exception $e) {

                return $this->redirectToRoute('crud_add'); //Redirecciona al mismo formulario en caso de error
            }
        } else {
            return $this->render('home/formulario.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/formulario/update/{id}", name="crud_update")
     */
    public function update(int $id, Request $request): Response
    {
        $productos = $this->em->getRepository(Productos::class)->find($id); //Se hace la consulta del registro por el ID

        if (!$productos) {
            throw $this->createNotFoundException(
                'No se encontró el producto en el id' . $id //Sino se obtiene respuesta se manda un mensaje
            );
        }

        $form = $this->createForm(FormularioProductoType::class, $productos,[
            'is_edit' => true
        ]);
        $form->handleRequest($request);//Se maneja la respuesta

        if ($form->isSubmitted() && $form->isValid()) //Si el formulario se envio y es valido
        {
            try {
                $this->em->flush(); //Se mandan los datos
                $this->addFlash('success', 'Producto editado'); //Se manda el mensaje flash
                return $this->redirectToRoute('crud_show_index', ['id' => $id]);
            } catch (\Exception $e) {
                return $this->redirectToRoute('crud_update', ['id' => $id]);
            }
        } else {
            return $this->render('home/update.html.twig', [
                'form' => $form->createView(),
                'productos' => $productos
            ]);
        }
    }

    /**
     * @Route("/formulario/delete/{id}", name="crud_delete")
     */
    public function delete(int $id, Request $request): Response
    {
        $productos = $this->em->getRepository(Productos::class)->find($id); //Se hace la consulta del registro por el ID

        if (!$productos) {
            throw $this->createNotFoundException(
                'No se encontró el producto en el id' . $id //Sino se obtiene respuesta se manda un mensaje
            );
        }
        $this->em->remove($productos); //Se elimina el producto
        $this->em->flush(); 
        $this->addFlash('success', 'Producto eliminado');
        return $this->redirectToRoute('crud_show_index');
    }
}
