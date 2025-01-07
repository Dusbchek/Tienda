<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\orders;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReflectionClass;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\orders>
 */
class ordersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = array_values(OrderStatus::cases());
        $status = (rand(0, 1) == 0) ? 'Nueva' : $statuses[array_rand($statuses)];
        $date = $this->randomDate("2024-08-01", now()->toDateString('Y-m-d'));
        $states = ['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Coahuila', 'Colima', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Durango', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'];

        return [
            'number' => $this->buildOrderNumber($date),
            'shipments_id' => random_int(1, 2),
            'status' => $status,
            'receiver_name' => fake()->name(),
            'receiver_phone' => fake()->phoneNumber(),
            'receiver_email' => fake()->email(),
            'receiver_street' => fake()->streetAddress(),
            'receiver_city' => fake()->city(),
            'receiver_state' => $states[array_rand($states)],
            'receiver_zip' => fake()->postcode(),
            'receiver_reference' => null,
            'shipment_price' => 160,
            'subtotal' => random_int(800, 2000),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }

    public static function buildOrderNumber($rndDate): string
    {
        $orderId = str_pad((Orders::all()->count() + 1), 3, '0', STR_PAD_LEFT);

        // Obtiene la fecha actual en formato ddmmaaaa
        $date = Carbon::parse($rndDate)->format('dmy');

        // Genera un número aleatorio de 1 a 999 y formatea el resultado
        $items = str_pad(random_int(100, 999), 3, '0', STR_PAD_LEFT);

        // Construye el número de orden
        return "OR-{$orderId}-02-{$date}-{$items}";
    }

    public function randomDate(string $start_date, string $end_date) {
        // Convertir las fechas a timestamps
        $start = strtotime($start_date);
        $end = strtotime($end_date);
    
        // Generar un timestamp aleatorio entre las dos fechas
        $randomTimestamp = mt_rand($start, $end);
    
        // Convertir el timestamp aleatorio a una fecha
        $randomDate = date("Y-m-d", $randomTimestamp);
    
        return $randomDate;
    }
}
