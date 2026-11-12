<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
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
     * @return mixed
     */
    public function getProducts(): mixed
    {
        return response()->json(self::PRODUCTS, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getProductItem(string $id): mixed
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function createProduct(Request $request): mixed
    {
        $requestData = json_decode($request->getContent(), true);

        $productId = random_int(1, 100);

        $newProductData = [
            'id'          => $productId,
            'name'        => $requestData['name'],
            'description' => $requestData['description'],
            'price'       => $requestData['price']
        ];

        return response()->json(['data' => $newProductData], Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function updateProduct(string $id, Request $request): mixed
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
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

        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function deleteProduct(string $id): mixed
    {
        $product = $this->getProductItemById(self::PRODUCTS, $id);

        if (!$product) {
            return response()->json(['data' => ['error' => 'Not found product by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
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
