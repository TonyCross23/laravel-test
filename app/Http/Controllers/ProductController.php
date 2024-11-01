<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{

    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productRepositoryInterface->index();

        return ApiResponseClass::sendResponse(ProductResource::collection($data),"",200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $details = [
            "name"=> $request->name,
            "details" => $request->details,
        ];

        try {
             DB::beginTransaction();
            $product = $this->productRepositoryInterface->store($details);

            DB::commit();
            return ApiResponseClass::sendResponse(new ProductResource($product),"",200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $porduct = $this->productRepositoryInterface->getById($id);
        return ApiResponseClass::sendResponse(new ProductResource($porduct),"",200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $updateDetail = [
            "name"=> $request->name,
            "details" => $request->details,
        ];

        try {
            $porduct = $this->productRepositoryInterface->update($updateDetail, $id);

            DB::commit();
            return ApiResponseClass::sendResponse('Product Update Successful',"",200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $this->productRepositoryInterface->destroy($id);

        return ApiResponseClass::sendResponse('Product Delete Successful','',204);
    }
}
