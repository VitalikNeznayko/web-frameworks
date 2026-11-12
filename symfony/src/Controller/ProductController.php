<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ProductController extends AbstractController
{

    public const PRODUCTS = [
        [
            'id'          => '1',
            'name'        => 'Pillow',
            'description' => 'Soft feather pillow',
            'price'       => 250
        ],
        [
            'id'          => '2',
            'name'        => 'Blanket',
            'description' => 'Warm wool blanket',
            'price'       => 700
        ],
        [
            'id'          => '3',
            'name'        => 'Towel',
            'description' => 'Cotton bath towel',
            'price'       => 180
        ]
    ];

    /**
     * @return JsonResponse
     */
    #[Route('/products', name: 'get_products', methods: [Request::METHOD_GET])]
    public function getProducts(): JsonResponse
    {
        return new JsonResponse(['data' => self::PRODUCTS], Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'get_product_item', methods: [Request::METHOD_GET])]
    public function getProductItem(string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['data' => $product], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Random\RandomException
     */
    #[Route('/products', name: 'post_products', methods: [Request::METHOD_POST])]
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $productId = random_int(1, 100);

        $newProductData = [
            'id'          => $productId,
            'name'        => $requestData['name'],
            'description' => $requestData['description'],
            'price'       => $requestData['price']
        ];

        return new JsonResponse(['data' => $newProductData], Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'patch_product', methods: [Request::METHOD_PATCH])]
    public function updateProduct(string $id, Request $request): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['name'])) {
            $product['name'] = $requestData['name'];
        }

        if (isset($requestData['description'])) {
            $product['description'] = $requestData['description'];
        }

        if (isset($requestData['price'])) {
            $product['price'] = $requestData['price'];
        }

        return new JsonResponse(['data' => $product], Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/products/{id}', name: 'delete_product', methods: [Request::METHOD_DELETE])]
    public function deleteProduct(string $id): JsonResponse
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return new JsonResponse(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param array $products
     * @param string $id
     * @return array|null
     */
    private function getProductItemById(array $products, string $id): ?array
    {
        foreach ($products as $product) {
            if ($product['id'] === $id) {
                return $product;
            }
        }

        return null;
    }

}
