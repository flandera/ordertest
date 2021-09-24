<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Validation;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/orders', name: 'orders')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        foreach ($orders as $key => $order) {
             $order->getProducts()->getValues();
        }
        if (count($orders) > 0) {
            return new JsonResponse($orders, Response::HTTP_OK);
        }
        return new JsonResponse('No Orders found', Response::HTTP_NOT_FOUND);
    }

    #[Route('/order/create', name: 'create_order')]
    public function save(Request $request): Response
    {
        try {
            $this->validateSaveRequest($request);
            $order = new Order();
            $resource = $request->getContent();
            $content = json_decode($resource);
            $order->setOrderId($content->orderId);
            $order->setPartnerId($content->partnerId);
            $order->setOrderTotal($content->orderTotal);
            $order->setDeliveryDate(new \DateTime($content->deliveryDate));
            try {
                $this->entityManager->persist($order);
                $this->entityManager->flush();
                foreach ($content->products as $product) {
                    $productEntity = new OrderProduct();
                    $productEntity->setName($product->name);
                    $productEntity->setPieces($product->pieces);
                    $productEntity->setPrice($product->price);
                    $productEntity->setOrder($order);
                    $this->entityManager->persist($productEntity);
                    $this->entityManager->flush();
                }
                return new JsonResponse('Success', Response::HTTP_OK);
            } catch (OptimisticLockException $e) {
                //log some error
            } catch (ORMException $e) {
                //log some error
            }
            return new JsonResponse('Error in saving to DB', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }
    }

    protected function validateSaveRequest(Request $request)
    {
        $constraints = new Collection([
            'orderId' => (new Required(new NotBlank())),
            'partnerId' => (new Required(new NotBlank())),
            'orderTotal' => (new Required([new NotBlank(), ])),
            'deliveryDate' => (new Required(new NotBlank())),
            'products' => (new Required([new NotBlank(),])),
        ]);

        //Creates validator
        $validator = Validation::createValidator();
        $validation = $validator->validate(json_decode($request->getContent(), true), $constraints);

        //Check for errors
        if(count($validation) > 0) {
            //Return bad fields
            $errorsString = (string) $validation;
            throw new ValidationException($errorsString);
        }
        return true;
    }
}
