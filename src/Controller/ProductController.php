<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Tag;
use App\Form\ProductType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/product/create", name="create")
     * Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $product = new Product();

        $tag = new Tag();
        $product->getTag()->add($tag);

        $form = $this->createForm(ProductType::class, $product);

        // Post data from Form (POST)
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('product')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . "." . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $product->setImage($filename);

            $product = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush($product);

            // add success message when created
            $this->addFlash(
                'info',
                'Product added successfully!'
            );

            return $this->redirectToRoute('list');
        }

        return $this->render('product/create_product.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/list", name="list")
     */
    public function list()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy([],['createdAt' => 'DESC']);
        return $this->render('product/list_product.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{product}/edit", name="edit")
     */
    public function edit($product)
    {
        $edit_product = $this->getDoctrine()->getRepository(Product::class)->find($product);

        return $this->render('product/edit_product.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $edit_product,
        ]);
    }
}