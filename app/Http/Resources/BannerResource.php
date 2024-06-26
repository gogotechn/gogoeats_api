<?php

namespace App\Http\Resources;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Banner|JsonResource $this */
        $locales = $this->relationLoaded('translations') ?
            $this->translations->pluck('locale')->toArray() : null;
        return [
            'id'              => (int) $this->id,
            'url'             => $this->url,
            'img'             => $this->img,
            'active'          => $this->active,
            'clickable'       => $this->clickable,
            'input'           => $this->input,
            'type'            => $this->type,
            'subscription_id' => $this->subscription_id,
            'likes'           => $this->whenLoaded('likes', $this->likes_count),
            'shops_count'     => $this->when($this->shops_count, $this->shops_count),
            'created_at'      => $this->when($this->created_at, $this->created_at?->format('Y-m-d H:i:s') . 'Z'),
            'updated_at'      => $this->when($this->updated_at, $this->updated_at?->format('Y-m-d H:i:s') . 'Z'),
            'deleted_at'      => $this->when($this->deleted_at, $this->deleted_at?->format('Y-m-d H:i:s') . 'Z'),

            // Relations
            'translation'   => TranslationResource::make($this->whenLoaded('translation')),
            'translations'  => TranslationResource::collection($this->whenLoaded('translations')),
            'locales'       => $this->when($locales, $locales),
            'galleries'     => GalleryResource::collection($this->whenLoaded('galleries')),
            'subscription'  => SubscriptionResource::make($this->whenLoaded('subscription')),
            'shops'         => ShopResource::collection($this->whenLoaded('shops')),
        ];
    }
}
