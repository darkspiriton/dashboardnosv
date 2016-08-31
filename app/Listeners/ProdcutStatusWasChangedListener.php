<?php

namespace Dashboard\Listeners;

use Dashboard\Events\ProductStatusWasChanged;
use Dashboard\Models\Experimental\ProductDetailStatus;
use Dashboard\Models\Experimental\ProductStatusDetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProdcutStatusWasChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductStatusWasChanged  $event
     * @return void
     */
    public function handle(ProductStatusWasChanged $event)
    {
        $product = $event->getProduct();
        $status_detail = $event->getStatusDetail();

        if ($status_detail) {
            $product_status = ProductStatusDetail::find($status_detail);

            if ($product_status) {
                $detail_status = new ProductDetailStatus();
                $detail_status->status_id = $status_detail;
                $product->detail_statuses()->save($detail_status);

                $product->status = $product_status->product_status_id;
                $product->save();
            }
        } else {
            $last_detail_status = ProductDetailStatus::orderBy("created_at", "desc")->first();
            $last_detail_status->resolved = 1;
            $last_detail_status->save();

            $product->status = 1;
            $product->save();
        }
    }
}
