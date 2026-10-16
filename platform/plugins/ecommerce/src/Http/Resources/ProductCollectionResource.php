<?php

namespace Botble\Ecommerce\Http\Resources;

use Botble\Ecommerce\Models\ProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductCollection
 */
class ProductCollectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
