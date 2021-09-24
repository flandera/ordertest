<?php

namespace App\Tests;

use App\Controller\OrderController;
use App\Entity\Order;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class OrdersControllerTest extends TestCase
{
    private string $data;
    private string $failData;

    public function setUp(): void
    {
    $this->data = '{
                    "orderId": "test1"
                    "partnerId": "neco neco",
                    "deliveryDate": "15.08.2021",
                    "orderTotal": 36.8,
                    "products": [{
                        "name": "pohovka",
                        "price": 28.65,
                        "pieces": 2
                    }]
                }';
    $this->failData = '{
                    "partnerId": "neco neco",
                    "deliveryDate": "15.08.2021",
                    "orderTotal": 36.8,
                    "products": [{
                        "name": "pohovka",
                        "price": 28.65,
                        "pieces": 2
                    }]
                }';
    }

    public function testValidation()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $controller = new OrderController($em);
        $request =new Request([],[],[],[],[],[],$this->data);
        $this->assertSame(true, $controller->validateSaveRequest($request));
    }

    public function testFailedValidation()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $controller = new OrderController($em);
        $request =new Request([],[],[],[],[],[],$this->failData);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Array[orderId]:\n    This field is missing. (code 2fa2158c-2a7f-484b-98aa-975522539ff8)\n");
        $controller->validateSaveRequest($request);
    }

    //TODO add more complex test of controller
//    public function testSaveEndpoint()
//    {
//        $em = $this->createMock(EntityManagerInterface::class);
//        $fakeOrder =$this->createMock(Order::class);
//        $fakeOrder->expects(self::once())->method('getId')->willReturn(1);
//        $em->expects(self::once())
//        ->method('any')
//        ->willReturn($fakeOrder);
//                $this->assertSame(200, $controller->validateSaveRequest($request)->getStatusCode());
//    }
}
