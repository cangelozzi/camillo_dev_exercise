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
     * @Route("/product/{id}/delete", name="delete")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        // add delete message when deleted
        $this->addFlash(
            'delete',
            'Product Deleted!'
        );

        // Fetch Response is expected
        $response = new Response();
        $response->send();

    }

    /**
     * @Route("/product/{product}/edit", name="edit")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $product)
    {
        $product = new Product();

        //!  Getting Error "Binding entities to query parameters only allowed for entities that have an identifier."
        $product = $this->getDoctrine()->getRepository(Product::class)->find($product);

        $tag = new Tag();
        $tag->setName('tag1');
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush($product);

            return $this->redirectToRoute('list');
        }

        return $this->render('product/edit_product.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }
}