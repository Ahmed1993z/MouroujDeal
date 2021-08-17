<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Product;
use App\Form\SearchForm;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product")
     */
    public function index(ProductRepository $repository, Request $request)
    {
        $data = new SearchData();
        $data->page = $request->get('page',1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        [$min , $max] = $repository->findMinMax($data);
        $products = $repository->findSearch($data);
        if ($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('product/_products.html.twig', ['products' => $products]),
                'sorting' => $this->renderView('product/_sorting.html.twig', ['products' => $products]),
                'pagination' => $this->renderView('product/_pagination.html.twig', ['products' => $products]),
                'pages' => ceil($products->getTotalItemCount()/$products->getItemNumberPerPage()),
                'min' => $min,
                'max' => $max
            
            ]);
        }
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max
        ]);
    }

    /**
     * @Route("/product/new", name="product_create")
     * @Route("/product/{id}/edit" , name="product_edit")
     */
    public function form(Product $product = null ,Request $request,  ManagerRegistry $manager ) {

        if(!$product){
        $product = new Product();
        }

        $form = $this->createFormBuilder($product)
        ->add('name' )
        ->add('price' )
        ->add('description' )
        ->add('content' )
        ->add('image')
        ->add('promo')
        ->add('categories')
        ->add('quantity')
        
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->getManager()->persist($product);
            $manager->getManager()->flush();

            return $this->redirectToRoute('product' ,['id' => $product->getId()]);

        }


        return $this->render('product/create.html.twig',[
            'formProduct'=>$form->createView(),
            'editMode' => $product->getId() !== null
        ]);
    }
    /**
     * @Route("/product/remove/{id}", name="product_remove")
     */
    public function remove(Product $product, ManagerRegistry $manager)
    {
        
        if (!$product)
        {
            throw $this->createNotFoundException('No user found');
        }
        
       
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();

        return $this->render("Product/deleted.html.twig");

    }
}
