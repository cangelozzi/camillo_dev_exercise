<?php

namespace App\Controller;

use App\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;

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
     */
    public function create()
    {
        return $this->render('product/create_product.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/product/list", name="list")
     */
    public function list()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        
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