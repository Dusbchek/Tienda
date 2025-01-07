<?php

namespace App\Livewire;


use App\Enums\OrderStatus;
use App\Models\colors;
use App\Models\images;
use App\Models\measures;
use App\Models\products;
use App\Models\orders;
use App\Models\shipments;
use App\Models\sizes;
use App\Notifications\NewOrderNotification;
use App\Traits\CacheType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Traits\ProductCache;

#[Layout('layouts.app')]
class singleProdTest extends Component
{
    use ProductCache;

    public string $slug;
    public products $product;

    public bool $openModal = false;

    #[Url(as: 'variante')]
    public string $color = "";
    #[Url(as: 'talla')]
    public string $size = "1";
    #[Url(as: 'cantidad')]
    public string $amount = "1";

    public Collection $images;
    public string $image;

    public Collection $measures;

    public function decreseStock(): void
    {

        $stock = $this->product->stock()->where('colors_id', $this->color)->where('sizes_id', $this->size)->first();
        $stock->stock -= $this->amount;
        $stock->save();
    }

    public function addToCart(): void
    {
        $this->addToCache(CacheType::CART, $this->product->id, $this->color, $this->size, $this->amount);
    }




    public static function buildOrderNumber(): string
    {
        $orderId = str_pad((Orders::all()->count() + 1), 3, '0', STR_PAD_LEFT);

        // Obtiene la fecha actual en formato ddmmaaaa
        $date = Carbon::now()->format('dmy');

        // Genera un número aleatorio de 1 a 999 y formatea el resultado
        $items = str_pad(random_int(100, 999), 3, '0', STR_PAD_LEFT);

        // Construye el número de orden
        return "OR-{$orderId}-02-{$date}-{$items}";
    }

    public function order(): void
    {
        $orderNumber = $this->buildOrderNumber();
        $shipments_id = 2;
        $status = OrderStatus::New;
        $receiver_name = auth()->user()->name;
        $receiver_phone = '9831670529';
        $receiver_email = auth()->user()->email;
        $receiver_street = 'Boulevard Primavera #3207';
        $receiver_city = 'Monterrey';
        $receiver_state = 'Nuevo León';
        $receiver_zip = '64830';
        $shipment_price = shipments::find($shipments_id)->price;
        $subtotal = floatval($this->product->price);

        $order = new orders([
            'number' => $orderNumber,
            'shipments_id' => $shipments_id,
            'status' => $status,
            'receiver_name' => $receiver_name,
            'receiver_phone' => $receiver_phone,
            'receiver_email' => $receiver_email,
            'receiver_street' => $receiver_street,
            'receiver_city' => $receiver_city,
            'receiver_state' => $receiver_state,
            'receiver_zip' => $receiver_zip,
            'shipment_price' => $shipment_price,
            'subtotal' => $subtotal,
        ]);

        $order->save();

        $productData = [
            'product_name' => $this->product->name,
            'size' => sizes::find($this->size)->size,
            'color' => colors::find($this->color)->color,
            'quantity' => $this->amount,
            'unit_price' => $this->product->price
        ];

        $order->orderProducts()->create($productData);

        $notification = new NewOrderNotification;
        $notification->toDatabase(auth()->user(), $order);
    }

    public function mount(): void
    {
        $this->product = products::where('slug', $this->slug)->first();
        if (!$this->product->is_visible) {
            $this->redirectRoute('products');
        }
        $this->color = is_numeric($this->color) ? $this->color : $this->product->colors->first()->id;
        $this->size = is_numeric($this->size) ? $this->size : 1;
        $this->amount = is_numeric($this->amount) ? $this->amount : 1;


        $this->images = images::where('products_id', $this->product->id)->get();
        $this->image = collect($this->images)->firstwhere('colors_id', $this->color)->image;
    }

    public function render(): View
    {
        $sizes = $this->product->sizes;
        $measures = measures::where('products_id', $this->product->id)->get();

        $parts = [];
        foreach ($measures as $measure) {
            if (!isset($parts[$measure->part])) {
                $parts[$measure->part] = [];
            }
            $parts[$measure->part][$measure->sizes->size] = $measure->measure;
        }

        $this->measures = measures::where('products_id', $this->product->id)->get();
        return view('livewire.singleProdTest', ['parts' => $parts, 'sizes' => $sizes]);
    }
}
