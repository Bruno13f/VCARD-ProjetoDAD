<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vcard' => new VcardResource($this->vcardOfTransaction),
            'date' => $this->date,
            'datetime' => $this->datetime,
            'type' => $this->getTypeOfTransactionAttribute(),
            'value' => $this->value,
            'old_balance' => $this->old_balance,
            'new_balance' => $this->new_balance,
            'payment_type' => $this->payment_type == 'MB' ? 'Multibanco' : $this->payment_type,
            'payment_reference' => $this->payment_reference,
            'pair_transaction' => $this->pair_transaction,
            'pair_vcard' => $this->pair_vcard,
            'category_id' => new CategoryResource($this->category),
            'description' => $this->descripion
            // custom_data e custom_options??

        ];
    }
}
