<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RubroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rubros')->insert([
            ["nombre"=>"BEBIDAS","icono"=>"las la-wine-bottle"],
            ["nombre"=>"CAFE CALIENTE", "icono"=>"las la-coffee"],
            ["nombre"=>"CAFE FRIO", "icono"=>"las la-mug-hot"],
            ["nombre"=>"CAFE CON LICOR", "icono"=>"las la-wine-glass-alt"],
            ["nombre"=>"JUGOS", "icono" => "las la-blender"],
            ["nombre"=>"JARRA", "icono" => "las la-glass-whiskey"],
            ["nombre"=>"GASEOSAS" ,"icono"=> "las la-wine-bottle"],
            ["nombre"=>"DESAYUNOS", "icono"=>"las la-bacon"],
            ["nombre"=>"OMELETTES", "icono"=>"las la-bread-slice"],
            ["nombre"=>"HELADOS", "icono"=>"las la-ice-cream"],
            ["nombre"=>"HELADO CLASICO", "icono"=>"las la-ice-cream"],
            ["nombre"=>"CHOCOMANIA", "icono"=>"las la-cookie"],
            ["nombre"=>"ICE BRINDYS", "icono"=>"las la-cocktail"],
            ["nombre"=>"HELADO SABORES", "icono"=>"las la-ice-cream"],
            ["nombre"=>"COMIDA RAPIDA", "icono"=>"las la-hotdog"],
            ["nombre"=>"PIZZAS", "icono"=>"las la-pizza-slice"],
            ["nombre"=>"GRILL", "icono"=>"las la-bacon"],
            ["nombre"=>"HAMBURGUESAS", "icono"=>"las la-hamburger"],
            ["nombre"=>"PLATOS", "icono"=>"las la-drumstick-bite"],
            ["nombre"=>"ENSALADAS", "icono"=>"las la-apple-alt"],
            ["nombre"=>"SANDWICHS", "icono"=>"las la-hamburger"],
            ["nombre"=>"MASAS DULCES", "icono"=>"las la-cookie"],
            ["nombre"=>"MASAS SALADAS", "icono"=>"las la-apple-alt"],
            ["nombre"=>"TORTAS", "icono"=>"las la-pepper-hot"],
            ["nombre"=>"POSTRES", "icono"=>"las la-ice-cream"],
            ["nombre"=>"ALMUERZO", "icono"=>"las la-drumstick-bite"],
            ["nombre"=>"JUGUETES", "icono"=>"las la-gamepad"],
        ]);
    }
}
